<?php
/** 
  * Fail ini mengandungi segala yang berkaitan dengan Sub Menu Maklumat Aduan
  * By : MUHD SYARIZAN B. YAACOB
  * Date Created : Disember 2020
**/

namespace App\Http\Controllers;

use App\Helper\Audit;
use App\Helper\Utilities;
use App\Helper\EncID;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Mail\NewUserMail;
use App\Mail\ComplaintMail;
use App\Mail\NotifyMail;
use App\Models\Complaint;
use App\Models\Sector;
use App\Models\Department;
use App\Models\Grade;
use App\Models\Role;
use App\Models\Position;
use App\Models\Category;
use App\Models\Task;
use App\Models\Staff;
use App\Models\User;
use App\Models\User_JAN;
use App\Models\Faq;
use App\Helper\Notify;
use Laravel\Fortify\Actions\CompletePasswordReset;

class FrontController extends Controller
{
  /** 
    * Paparan Halaman Depan
  **/
  public function index()
  {
    // Dapatkan senarai sektor
    $sectors = Sector::where('active', 1)
                     ->orderBy('order', 'ASC')
                     ->get();

    // Dapatkan senarai bahagian
    $departments = Department::leftJoin('sectors', 'departments.sector_code', '=', 'sectors.sector_code')
                             ->where('sectors.active', 1)
                             ->where('departments.active', 1)
                             ->get();

    // Dapatkan senarai kategori
    $categories = Category::where('active', 1)
                          ->get();


    $col_1 = Faq::where('column', 1)
                ->where('active', 1)
                ->get();

    $col_2 = Faq::where('column', 2)
                ->where('active', 1)
                ->get();

    $col_3 = Faq::where('column', 3)
                ->where('active', 1)
                ->get();         
    
    return view('front')->with(['sectors' => $sectors, 'departments' => $departments, 'categories' => $categories, 'col_1' => $col_1, 'col_2' => $col_2, 'col_3' => $col_3]);
  }

  /** 
    * Dapatkan Senarai Bahagian
  **/
  public function getDepartments($sector_id=0)
  {
    // Dapatkan senarai bahagian mengikut ID diatas
    $departments = Department::where('active', 1)
                             ->where('sector_code', $sector_id)
                			       ->get();

    return response()->json($departments); // Hantar data dalam bentuk JSON
  }


  public function newUser()
  {
    // Dapatkan senarai seksyen
    $sectors = Sector::where('active', 1)
                     ->orderBy('order', 'ASC')
                     ->get();

    // Dapatkan senarai seksyen
    $departments = Department::where('active', 1)
                             ->get();

    $grades = Grade::all();

      // Dapatkan senarai jawatan
    $positions = Position::all();

    return view('auth.new_user', compact('sectors', 'departments', 'grade', 'positions'));
  }

  public function save_new_user(Request $request)
  {
    // Validate data yang diperlukan
    $validatedData = $request->validate(
      [
          'ic_number' => 'required|numeric|unique:users',
          'name' => 'required',
          'grade_id' => 'required',
          'position_id' => 'required',
          'sector_id' => 'required',
          'department_id' => 'required',
          'block' => 'required',
          'level' => 'required',
          'zone' => 'required',
          'telephone' => 'required|numeric',
          'handphone' => 'required|numeric',
          'email' => 'required|email|unique:users',
      ]
    );

    $password = Utilities::randomPassword();

    // umpuk data yang dihantar ke field di DB
    $post = new User;
    $post->ic_number = $request->ic_number;
    $post->name = ucwords(strtolower($request->name));
    $post->gred_code = $request->grade_id;
    $post->jaw_code = $request->position_id;
    $post->sector_code = $request->sector_id;
    $post->department_code = $request->department_id;
    $post->block = $request->block;
    $post->level = $request->level;
    $post->zone = $request->zone;
    $post->telephone = $request->telephone;
    $post->handphone = $request->handphone;
    $post->email = $request->email;
    $post->role_id = 6;
    $post->password = Hash::make($password);
    $post->save();
    $id = $post->id;
    
    // Maklumat yang hendak dipaparkan di dalam emel notifikasi
    $data = [
      'ic_number' => $request->ic_number,
      'password' => $password,
    ];

    Audit::create($id, null, 'Daftar Akaun Baru', null, null, null, null, null, null, null, null);
    Mail::to($request->email)->send(new NewUserMail($data));

    // Audit trail
    //Notify::flash('success', __('Akaun anda telah diwujudkan. Sila semak emel anda untuk ID Pengguna dan Katalaluan.'), 'BERJAYA');

    return redirect('/')->with(['success' => 'Akaun anda telah diwujudkan. Sila semak emel anda untuk ID Pengguna dan Katalaluan.']);
  }

  /** 
    * Dapatkan Maklumat Pengadu Dari DB eAudit production
  **/
  public function getUser($ic)
  {
    // Dapatkan maklumat pengadu mengikut ic yang dimasukkan
    
    $user = User_JAN::where(\DB::raw("REPLACE(`stf_nokp`, '-', '')"), $ic)
                    ->select('stf_nama', 'stf_nokp', 'stf_tel', 'stf_tel_bimbit', 'stf_email', 'stf_idsekt', 'stf_bahagian', 'stf_gred', 'stf_jawatan')
                    ->first();

    if ($user != null) { // Jika ada maklumat pengadu

      if($user->stf_idsekt == '1.0' || $user->stf_idsekt == '2.0' || $user->stf_idsekt == '3.1' || $user->stf_idsekt == '3.2' || $user->stf_idsekt == '3.3'){

        $nama = ucwords(strtolower($user->stf_nama));
        $ic_number = str_replace('-', '', $user->stf_nokp);
        $telephone = str_replace('-', '', $user->stf_tel);
        $handphone = str_replace('-', '', $user->stf_tel_bimbit);

        $bhgn = Department::where('active', 1)
                          ->where('sector_code', $user->stf_idsekt)
                          ->get();

        $user = array(
          "nama" => $nama,
          "ic" => $ic_number,
          "telefon" => $telephone,
          "tel_bimbit" => $handphone,
          "email" => $user->stf_email,
          "sektor" => $user->stf_idsekt,
          "bahagian" => $user->stf_bahagian,
          "gred" => $user->stf_gred,
          "jawatan" => $user->stf_jawatan,
          "list_bhgn" => $bhgn
        );
      
      }else{
        $user = 1;
      }

    }else{ // Jika tiada maklumat pengadu
      $user = 0;
    }

    //return response()->json(['user' => json_encode($user)]);
    return response()->json($user); // Hantar data dalam bentuk JSON               
  }

  /** 
    * Simpan Maklumat Aduan
  **/
  public function store(Request $request)
  {        
    // Validate data yang diperlukan
    $validatedData = $request->validate(
        [
            'name' => 'required',
            'sector' => 'required',
            'department' => 'required',
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
    $post = new Complaint;
    $post->name = $request->name;
    $post->ic_number = $request->ic_no;
    $post->sector_code = $request->sector;
    $post->department_code = $request->department;
    $post->block = $request->block;
    $post->level = $request->level;
    $post->zone = $request->zone;
    $post->location = $request->location;
    $post->telephone = $request->telephone;
    $post->handphone = $request->handphone;
    $post->email = $request->email;
    $post->category_id = $request->category;
    $post->subcategory_id = $request->subcategory;
    $post->remarks = $request->remarks;
    
    if($request->category == 10){
      $post->status_id = 2; // Auto Dalam Tindakan if Aplikasi
    } else {
      $post->status_id = 1;
    }
    
    $post->date_open = date('Y-m-d H:i:s');
    $post->save();
    $id = $post->id;
    
    /********** Proses untuk generate No Aduan **********/
    $number = str_pad($id, 4, "0", STR_PAD_LEFT); // tambah 0 pada last id
    $year = date('Y'); // dapatkan tahun semasa

    $app_no = $year.$number; // combine tahun dan number
    /******* !end Proses untuk generate No Aduan ********/

    // update balik no permohonan
    $post = Complaint::find($id);
    $post->application_no = $app_no;
    $post->save();

    if($request->file('attachment')) { // Jika pengadu muatnaik dokumen lampiran
      $fileName = $app_no.'_'.time().'.'.$request->file('attachment')->extension(); // rename file yang diupload ikut no aduan dan masa 
      $request->file('attachment')->move(public_path('uploads'), $fileName); // save lampiran di folder /public/uploads/
      //$name = $request->file('file')->getClientOriginalName();
 
      //$request->file('attachment')->storeAs('public/uploads', $fileName);
      
      // update balik nama file upload
      $post = Complaint::find($id);
      $post->attachment = $fileName;
      $post->save();
    }

    $lokasi = '';
    if($request->block != '')
      $lokasi = "Blok ".$request->block;
    if($request->level != '')
      $lokasi .= ", ".$request->level;
    if($request->zone != '')
      $lokasi .= ", Zon ".$request->zone;

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

    if($request->category == 10) {
        $assignedUsers = User::leftJoin('user_subcategories', 'users.id', '=', 'user_subcategories.user_id')
                             ->where('user_subcategories.subcategory_id', $request->subcategory)
                             ->whereIn('users.role_id', [7, 8])
                             ->where('users.active', 1)
                             ->select('users.email')
                             ->get();
                             
        foreach($assignedUsers as $u) {
            if (!empty($u->email)) {
                Log::info('[FrontController] Attempting NotifyMail', ['to' => $u->email, 'app_no' => $app_no]);
                try {
                    Mail::to($u->email)->send(new NotifyMail($data));
                    Log::info('[FrontController] NotifyMail SENT OK', ['to' => $u->email]);
                } catch (\Throwable $e) {
                    Log::error('[FrontController] NotifyMail FAILED', ['to' => $u->email, 'error' => $e->getMessage()]);
                }
                sleep(10);
            }
        }
    }

    Audit::create($id, $app_no, 'Log Aduan Baru', null, null, null, null, null, null, null, null);
    Log::info('[FrontController] Attempting ComplaintMail', ['to' => $request->email, 'app_no' => $app_no]);
    try {
        Mail::to($request->email)->send(new ComplaintMail($data));
        Log::info('[FrontController] ComplaintMail SENT OK', ['to' => $request->email]);
    } catch (\Throwable $e) {
        Log::error('[FrontController] ComplaintMail FAILED', ['to' => $request->email, 'error' => $e->getMessage()]);
    }
    sleep(5);
    Log::info('[FrontController] Attempting helpdesk NotifyMail', ['to' => 'helpdesk@audit.gov.my', 'app_no' => $app_no]);
    try {
        Mail::to('helpdesk@audit.gov.my')->send(new NotifyMail($data));
        Log::info('[FrontController] Helpdesk NotifyMail SENT OK');
    } catch (\Throwable $e) {
        Log::error('[FrontController] Helpdesk NotifyMail FAILED', ['error' => $e->getMessage()]);
    }

    // Audit trail
    Notify::flash('success', __('Aduan telah dihantar. Sila semak emel anda untuk No Aduan.'), 'BERJAYA');

    return redirect('/');

  }

  /** 
    * Semak Aduan
  **/
  public function check(Request $request)
  {
    $app_no = $request->application_no; // Dapatkan no aduan yang dimasukkan

    // Dapatkan maklumat aduan mengikut no aduan diatas
    $complaint = Complaint::leftJoin('categories', 'complaints.category_id', '=', 'categories.id')
                          ->leftJoin('status', 'complaints.status_id', '=', 'status.id')
                          ->where('application_no', $app_no)
                          ->select('complaints.id', 'complaints.application_no', 'complaints.name', 'complaints.telephone', 'complaints.email', 'complaints.remarks', 'complaints.status_id', 'complaints.attachment', 'complaints.date_open', 'complaints.sector_code', 'complaints.department_code', 'complaints.block', 'complaints.level', 'complaints.zone', 'complaints.location', 'categories.category_desc', 'status.status_desc')
                          ->first();

    if($complaint){

      $lokasi = '';
      if($complaint->block != '')
        $lokasi = "Blok ".$complaint->block;
      if($complaint->level != '')
        $lokasi .= ", ".$complaint->level;
      if($complaint->zone != '')
        $lokasi .= ", Zon ".$complaint->zone;
  
      $n = Utilities::getSector($complaint->sector_code).'<br>'.Utilities::getDepartment($complaint->department_code);
      $n .= '<br>'.$lokasi.'<br>'.$complaint->location;

      // Dapatkan maklumat pegawai bertanggungjawab jika ada
      $staff = Task::leftJoin('users', 'users.id', '=', 'tasks.user_id')
                   ->where('tasks.complaint_id', $complaint->id)
                   ->select('users.name', 'users.telephone')
                   ->first();

      return view('semakan', compact('complaint', 'staff', 'n'));

    }else{
      Notify::flash('error', __('No Aduan tidak wujud.'), 'GAGAL');

      return redirect('/');
    }
  }

  /** 
    * Semak Aduan
  **/
  public function check_log($id)
  {
    $id = EncID::get($id); // Decrypt ID yang dihantar

    // Dapatkan maklumat aduan mengikut no aduan diatas
    $complaint = Complaint::leftJoin('categories', 'complaints.category_id', '=', 'categories.id')
                          ->leftJoin('status', 'complaints.status_id', '=', 'status.id')
                          ->where('complaints.id', $id)
                          ->select('complaints.id', 'complaints.application_no', 'complaints.name', 'complaints.telephone', 'complaints.email', 'complaints.remarks', 'complaints.status_id', 'complaints.attachment', 'complaints.date_open', 'complaints.sector_code', 'complaints.department_code', 'complaints.block', 'complaints.level', 'complaints.zone', 'complaints.location', 'categories.category_desc', 'status.status_desc')
                          ->first();

    $lokasi = '';
    if($complaint->block != '')
      $lokasi = "Blok ".$complaint->block;
    if($complaint->level != '')
      $lokasi .= ", ".$complaint->level;
    if($complaint->zone != '')
      $lokasi .= ", Zon ".$complaint->zone;

    $n = Utilities::getSector($complaint->sector_code).'<br>'.Utilities::getDepartment($complaint->department_code);
    $n .= '<br>'.$lokasi.'<br>'.$complaint->location;

    // Dapatkan maklumat pegawai bertanggungjawab jika ada
    $staff = Task::leftJoin('users', 'users.id', '=', 'tasks.user_id')
                 ->where('tasks.complaint_id', $id)
                 ->select('users.name', 'users.telephone')
                 ->first();

    return view('semakan', compact('complaint', 'staff', 'n'));
  }

  /** 
    * Sahkan Aduan Selesai
  **/
  public function checkdone($id)
  {
    $complaint = Complaint::findOrFail($id);
    $complaint->status_id = 4;
    $complaint->date_close = date('Y-m-d H:i:s');
    $complaint->save();

    $appno = Utilities::getAppNo($id);
    Audit::create($id, $appno, 'Pengguna Tutup Aduan', null, null, null, null, null, null, null, null);

    Notify::flash('success', __('Aduan anda sudah dikemaskini'), 'BERJAYA');
  }

}
