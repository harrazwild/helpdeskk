<?php
/** 
 * Fail ini mengandungi segala yang berkaitan dengan Sub Menu Maklumat Aduan Baru
 * By : MUHD SYARIZAN B. YAACOB
 * Date Created : Mei 2021
 **/

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

use App\Mail\ComplaintMail;
use App\Mail\NotifyMail;

use Carbon\Carbon;

use App\Helper\Audit;
use App\Helper\Utilities;
use App\Helper\EncID;
use App\Helper\Notify;
use App\Models\Complaintlist;
use App\Models\Complaint_Attachment;
use App\Models\Task;
use App\Models\User;
use App\Models\Category;
use App\Models\Sector;
use App\Models\User_JAN;

use DataTables;

class NewComplaintController extends Controller
{
  /** 
   * Dapatkan Maklumat Pengadu Dari DB eAudit production
   **/
  public function index()
  {
    // Dapatkan senarai sektor                         
    $sectors = Sector::where('active', 1)
      ->get();

    // Dapatkan senarai kategori
    $categories = Category::where('active', 1)
      ->get();

    $staff = User::where('users.active', 1)
      ->where(function ($query) {
        $query->where('users.role_id', 2)
          ->orWhere('users.role_id', 3);
      })
      ->select('users.id', 'users.name')
      ->get();

    return view('newcomplaint.index', compact('sectors', 'categories', 'staff'));
  }

  /** 
   * Dapatkan Senarai Pelaksana
   **/
  public function getStaff($id)
  {
    // Dapatkan senarai pelaksana mengikut ID kategori diatas
    $staff = User::leftJoin('sections', 'users.section_id', '=', 'sections.id')
      ->leftJoin('categories', 'sections.id', '=', 'categories.section_id')
      ->where('users.active', 1)
      ->where('categories.id', $id)
      ->where(function ($query) {
        $query->where('users.role_id', 2)
          ->orWhere('users.role_id', 3);
      })
      ->select('users.id', 'users.name')
      ->get();

    return response()->json($staff); // Hantar data dalam bentuk JSON
  }

  /** 
   * Simpan Maklumat Aduan
   **/
  public function store(Request $request)
  {
    // Validate data yang diperlukan
    $validatedData = $request->validate(
      [
        'sector' => 'required',

        'block' => 'required',
        'level' => 'required',
        'zone' => 'required',
        'telephone' => 'required',
        'handphone' => 'required',
        'email' => 'required|email',
        'category' => 'required',
        'subcategory' => 'required_if:category,10',
        'remarks' => 'required',
        'attachment.*' => 'file|mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx|max:20480'
      ]
    );

    // umpuk data yang dihantar ke field di DB
    $post = new Complaintlist;
    //$post->user_id = $request->user_id;
    $post->name = $request->name;
    $post->ic_number = $request->ic_number;
    $post->email = $request->email;
    $post->grade_code = $request->grade_id;
    $post->position_code = $request->position_id;
    $post->sector_code = $request->sector;
    $post->department_code = $request->department;
    $post->block = $request->block;
    $post->level = $request->level;
    $post->zone = $request->zone;
    $post->telephone = $request->telephone;
    $post->handphone = $request->handphone;
    $post->category_id = $request->category;
    $post->subcategory_id = $request->subcategory;
    $post->remarks = $request->remarks;

    if ($request->category == 10) {
      $post->status_id = 2; // Auto Dalam Tindakan if Aplikasi
    } else {
      $post->status_id = 1;
    }

    $post->date_open = date('Y-m-d H:i:s');
    $post->save();
    $id = $post->id;

    /********** Proses untuk generate No Aduan **********/
    $number = str_pad($id, 6, "0", STR_PAD_LEFT); // tambah 0 pada last id
    $year = date('Y'); // dapatkan tahun semasa

    $app_no = $year . $number; // combine tahun dan number
    /******* !end Proses untuk generate No Aduan ********/

    // update balik no permohonan
    $post = Complaintlist::find($id);
    $post->application_no = $app_no;
    $post->save();

    if ($request->hasfile('attachment')) {
      foreach ($request->file('attachment') as $file) {
        $fileName = $app_no . '_' . time() . '.' . $file->extension();
        $file->move(public_path() . '/uploads/' . $app_no . '/', $fileName);

        $file = new Complaint_Attachment;
        $file->complaint_id = $id;
        $file->attachment = $fileName;
        $file->save();
      }
    }

    if ($request->staff != '') { // Jika pilih pelaksana
      $task = new Task;
      $task->complaint_id = $id;
      $task->user_id = $request->staff;
      $task->save();

      $post = Complaintlist::find($id);
      $post->status_id = 2;
      $post->save();
    }

    $lokasi = '';
    if ($request->block != '')
      $lokasi = "Blok " . $request->block;
    if ($request->level != '')
      $lokasi .= ", " . $request->level;
    if ($request->zone != '')
      $lokasi .= ", Zon " . $request->zone;

    // Maklumat yang hendak dipaparkan di dalam emel notifikasi
    $data = [
      'id' => $id,
      'app_no' => $app_no,
      'name' => $request->name,
      'remarks' => $request->remarks,
      'sector' => \App\Helper\Utilities::getSector($request->sector),
      'department' => \App\Helper\Utilities::getDepartment($request->department),
      'lokasi' => $lokasi,
      'location' => $request->location
    ];

    if ($request->category == 10) {
      $assignedUsers = User::leftJoin('user_subcategories', 'users.id', '=', 'user_subcategories.user_id')
        ->where('user_subcategories.subcategory_id', $request->subcategory)
        ->whereIn('users.role_id', [7, 8])
        ->where('users.active', 1)
        ->select('users.email')
        ->get();

      foreach ($assignedUsers as $u) {
        if (!empty($u->email)) {
          Log::info('[NewComplaintController] Attempting NotifyMail', ['to' => $u->email, 'app_no' => $app_no]);
          try {
            Mail::to($u->email)->send(new NotifyMail($data));
            Log::info('[NewComplaintController] NotifyMail SENT OK', ['to' => $u->email]);
          } catch (\Throwable $e) {
            Log::error('[NewComplaintController] NotifyMail FAILED', ['to' => $u->email, 'error' => $e->getMessage()]);
          }
          sleep(10);
        }
      }
    }

    // Audit trail
    Audit::create($id, $app_no, 'Log Aduan Baru (BTM)', $request->staff, null, $request->category, $request->subcategory, null, 1, $request->remarks, null);

    // Hantar emel pengesahan kepada pengadu
    sleep(5);
    Log::info('[NewComplaintController] Attempting ComplaintMail', ['to' => $request->email, 'app_no' => $app_no]);
    try {
      Mail::to($request->email)->send(new ComplaintMail($data));
      Log::info('[NewComplaintController] ComplaintMail SENT OK', ['to' => $request->email]);
    } catch (\Throwable $e) {
      Log::error('[NewComplaintController] ComplaintMail FAILED', ['to' => $request->email, 'error' => $e->getMessage()]);
    }

    if ($post) {
      Notify::flash('success', __('Maklumat berjaya disimpan.'), 'BERJAYA');
    } else {
      Notify::flash('error', __('Maklumat tidak berjaya disimpan.'), 'GAGAL');
    }

    return redirect('/complaintlist');
  }

}
