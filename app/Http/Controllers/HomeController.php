<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Helper\Audit;
use App\Helper\Utilities;
use App\Helper\EncID;
use App\Helper\Notify;
use App\Models\Complaintlist;
use App\Models\Complaint_Attachment;
use App\Models\TaskRemarks;
use App\Models\Category;
use App\Models\Sector;
use App\Models\Department;
use App\Models\Device;

use App\Models\User;
use Illuminate\Validation\Rules\Password;
use App\Models\Grade;
use App\Models\Position;
use App\Models\Faq;

use App\Mail\NotifyMail;
use App\Mail\MeetingMail;
use App\Mail\DoneMail;
use DataTables;
use File;

class HomeController extends Controller
{
  /**
   * Show the application dashboard.
   *
   * @return \Illuminate\Contracts\Support\Renderable
   */
  public function index(Request $request)
  {
    $user_id = Auth::user()->id; // Dapatkan ID pengguna yang log masuk  
    $ic = Auth::user()->ic_number;

    if($request->tahun){
      $y = $request->tahun;
    }else{
      $y = date('Y');
    }

    if($request->search){
      $search = str_replace('#', '', $request->search);
    }else{
      $search = '';
    }

    if($request->status){
      $st = $request->status;
    }else{
      $st = '';
    }
      
    // Dapatkan senarai aduan                                                  
    $complaints = Complaintlist::leftJoin('status', 'complaints.status_id', '=', 'status.id')
                               ->leftJoin('categories', 'complaints.category_id', '=', 'categories.id')
                               ->where('complaints.active', 1)
                               ->where('complaints.ic_number', $ic);

    if($search){ // Jika ada carian
      $complaints->where(function($query) use ($search){
        $query->where('complaints.application_no', 'LIKE', '%'.$search.'%')
               ->orWhere('complaints.remarks', 'LIKE', '%'.$search.'%');
      });
    }

    if($st){ // Jika ada pilihan status
      if($st == 4){
        $complaints->where(function($query){
          $query->where('complaints.status_id', 4)
                 ->orWhere('complaints.status_id', 5);
        });
      }else{
        $complaints->where('complaints.status_id', $st);
      }
    }

    if($y){
      $complaints->whereYear('complaints.date_open', $y);
    }

    $complaints = $complaints->select('complaints.id', 'complaints.application_no', 'complaints.remarks', 'complaints.status_id', 'complaints.category_id', 'complaints.date_open', 'status.status_desc', 'categories.category_desc')
                             ->orderBy('complaints.status_id', 'ASC')
                             ->orderBy('complaints.date_open', 'ASC')
                             ->get();               

    return view('user.index', compact('complaints', 'y', 'st', 'search'));
  }

  public function show_complaint($id)
  {
    $id = EncID::get($id);

    $complaint = Complaintlist::leftJoin('tasks', 'tasks.complaint_id', '=', 'complaints.id')
                              ->leftJoin('status', 'status.id', '=', 'complaints.status_id')
                              ->where('complaints.id', $id)
                              ->select('complaints.id', 'complaints.application_no', 'complaints.sector_code', 'complaints.department_code', 'complaints.block', 'complaints.level', 'complaints.zone', 'complaints.location', 'complaints.telephone', 'complaints.handphone', 'complaints.category_id', 'complaints.subcategory_id', 'complaints.detail', 'complaints.remarks', 'complaints.attachment', 'complaints.status_id', 'complaints.vendor_id', 'complaints.date_open', 'complaints.name', 'complaints.email', 'tasks.user_id', 'status.status_desc')
                              ->first();

    $attach = Complaint_Attachment::where('complaint_id', $id)
                                  ->get();

    $remarks = TaskRemarks::where('complaint_id', $id)
                          ->where('mark', 1)
                          ->get();

    return view('user.show_complaint', compact('complaint', 'attach', 'remarks'));
  }

  public function edit_complaint($id)
  {
    $id = EncID::get($id);

    $complaint = Complaintlist::find($id);

    $attach = Complaint_Attachment::where('complaint_id', $id)
                                  ->get();

    // Dapatkan senarai sektor                         
    $sectors = Sector::where('active', 1)
                     ->orderBy('order', 'ASC')
                     ->get();

    $departments = Department::where('active', 1)
                             ->where('sector_code', $complaint->sector_code)
                             ->get();

    // Dapatkan senarai kategori
    $categories = Category::where('active', 1)
                          ->get();

    return view('user.edit_complaint', compact('complaint', 'attach', 'sectors', 'departments', 'categories'));
  }

  public function upd_complaint(Request $request)
  {
    // Validate data yang diperlukan
    $validatedData = $request->validate(
      [
        'sector' => 'required',
        'department' => 'required',
        'block' => 'required',
        'level' => 'required',
        'zone' => 'required',
        'telephone' => 'required|numeric',
        'handphone' => 'required|numeric',
        'category' => 'required',
        'remarks' => 'required',
        'attachment' => 'file|mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx|max:20480'
      ]
    );

    // umpuk data yang dihantar ke field di DB
    $post = Complaintlist::find($request->id);
    $post->sector_code = $request->sector;
    $post->department_code = $request->department;
    $post->block = $request->block;
    $post->level = $request->level;
    $post->zone = $request->zone;
    $post->location = $request->location;
    $post->telephone = $request->telephone;
    $post->handphone = $request->handphone;
    $post->category_id = $request->category;
    $post->remarks = $request->remarks;
    $post->save();

    $app_no = \App\Helper\Utilities::getAppNo($request->id);

    // Audit trail
    Audit::create($request->id, $app_no, 'Pengadu Kemaskini Aduan', null, null, null, null, null, null, null, null);
    
    if($post){
      Notify::flash('success', __('Maklumat berjaya disimpan.'), 'BERJAYA');
    }else{
      Notify::flash('error', __('Maklumat tidak berjaya disimpan.'), 'GAGAL');  
    }

    return redirect('/home');
  }

  public function del_complaint(Request $request)
  {
    $id = $request->id;

    $post = Complaintlist::find($id);
    $post->active = 0;
    $post->save();

    $app_no = \App\Helper\Utilities::getAppNo($id);

    Audit::create($id, $app_no, 'Hapus Aduan', null, null, null, null, null, null, null, null);
    
    if($post){
      $arr = 1;
    }else{
      $arr = 0;
    }

    return json_encode($arr);
  }

  public function new_complaint()
  {
    // Dapatkan senarai sektor
    $sectors = Sector::where('active', 1)
                     ->orderBy('order', 'ASC')
                     ->get();

    $departments = Department::where('active', 1)
                             ->where('sector_code', Auth::user()->sector_code)
                             ->get();

    $devices = Device::where('active', 1)->get();

    // Dapatkan senarai kategori
    $categories = Category::where('active', 1)
                          ->get();

    return view('user.new_complaint', compact('sectors', 'departments', 'devices', 'categories'));
  }

  public function save_complaint(Request $request)
  {
    if($request->category == 12){ // Jika bantuan teknikal mesyuarat
      // Validate data yang diperlukan
      $validatedData = $request->validate([
        'location' => 'required',
        'sector' => 'required',
        'block' => 'required',
        'level' => 'required',
        'zone' => 'required',
        'telephone' => 'required|numeric',
        'handphone' => 'required|numeric',
        'category' => 'required',
        'start' => 'required',
        'end' => 'required',
        'ms_mula' => 'required',
        'ms_tamat' => 'required',
        'device' => 'required',
        'attachment.*' => 'file|mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx|max:20480'
      ]);

      $device = $request->device;
      $p = count($device);

      if($p == 1){

        $a = $device[0];

        if($a == '99'){
          $a_ict = $request->lain_ict;
        }else{
          $ict = Device::where('id', $a)->select('device')->first();
          $a_ict = $ict->device;
        }

      }elseif($p > 1){

        $ict = Device::where('id', $device[0])->select('device')->first();
        $a_ict = $ict->device;

        for ($i = 1; $i < $p; $i++) {
          if($device[$i] == '99'){
            $a_ict .= '|'.$request->lain_ict;
          }else{
            $ict = Device::where('id', $device[$i])->select('device')->first();
            $a_ict .= '|'.$ict->device;
          }
        }

      }

      // umpuk data yang dihantar ke field di DB
      $post = new Complaintlist;
      $post->name = Auth::user()->name;
      $post->ic_number = Auth::user()->ic_number;
      $post->email = Auth::user()->email;
      $post->grade_code = Auth::user()->gred_code;
      $post->position_code = Auth::user()->jaw_code;
      $post->sector_code = $request->sector;
      $post->department_code = $request->department;
      $post->block = $request->block;
      $post->level = $request->level;
      $post->zone = $request->zone;
      $post->location = $request->location;
      $post->telephone = $request->telephone;
      $post->handphone = $request->handphone;
      $post->category_id = $request->category;
      $post->remarks = $a_ict;
      $post->lain_ict = $request->lain_ict;
      $post->tkh_mula = date('Y-m-d', strtotime($request->start));
      $post->tkh_tamat = date('Y-m-d', strtotime($request->end));
      $post->ms_mula = date('H:i:s', strtotime($request->ms_mula));
      $post->ms_tamat = date('H:i:s', strtotime($request->ms_tamat));
      $post->status_id = 1;
      $post->date_open = date('Y-m-d H:i:s');
      $post->save();
      $id = $post->id;
      
      /********** Proses untuk generate No Aduan **********/
      $number = str_pad($id, 6, "0", STR_PAD_LEFT); // tambah 0 pada last id
      $year = date('Y'); // dapatkan tahun semasa

      $app_no = $year.$number; // combine tahun dan number
      /******* !end Proses untuk generate No Aduan ********/

      // update balik no permohonan
      $post = Complaintlist::find($id);
      $post->application_no = $app_no;
      $post->save();

      $lokasi = '';
      if($request->block != '')
        $lokasi = "Blok ".$request->block;
      if($request->level != '')
        $lokasi .= ", ".$request->level;
      if($request->zone != '')
        $lokasi .= ", Zon ".$request->zone;

      // Maklumat yang hendak dipaparkan di dalam emel notifikasi
      $data = [
        'app_no' => $app_no,
        'name' => Auth::user()->name,
        'remarks' => $a_ict,
        'sector' => \App\Helper\Utilities::getSector($request->sector),
        'department' => \App\Helper\Utilities::getDepartment($request->department),
        'lokasi' => $lokasi,
        'location' => $request->location,
        'tkh_mula' => date('Y-m-d', strtotime($request->start)),
        'tkh_tamat' => date('Y-m-d', strtotime($request->end)),
        'masa_mula' => date('H:i:s', strtotime($request->ms_mula)),
        'masa_tamat' => date('H:i:s', strtotime($request->ms_tamat)),
      ];

      // Audit trail
      Audit::create($id, $app_no, 'Pengadu Log Permohonan Baru', null, null, null, null, null, 1, null, null);
      // Emel team helpdesk
      Mail::to('helpdesk@audit.gov.my')->send(new MeetingMail($data));

      if($post){
        Notify::flash('success', __('Permohonan telah dihantar.'), 'BERJAYA');
      }else{
        Notify::flash('error', __('Permohonan tidak berjaya.'), 'GAGAL');  
      }

    }else{ // jika aduan kerosakan
      // Validate data yang diperlukan
      $validatedData = $request->validate([
        'sector' => 'required',
        'block' => 'required',
        'level' => 'required',
        'zone' => 'required',
        'telephone' => 'required|numeric',
        'handphone' => 'required|numeric',
        'category' => 'required',
        'remarks' => 'required',
        'attachment.*' => 'file|mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx|max:20480'
      ]);
    
      // umpuk data yang dihantar ke field di DB
      $post = new Complaintlist;
      //$post->user_id = $request->user_id;
      $post->name = Auth::user()->name;
      $post->ic_number = Auth::user()->ic_number;
      $post->email = Auth::user()->email;
      $post->grade_code = Auth::user()->gred_code;
      $post->position_code = Auth::user()->jaw_code;
      $post->sector_code = $request->sector;
      $post->department_code = $request->department;
      $post->block = $request->block;
      $post->level = $request->level;
      $post->zone = $request->zone;
      $post->location = $request->location;
      $post->telephone = $request->telephone;
      $post->handphone = $request->handphone;
      $post->category_id = $request->category;
      $post->remarks = $request->remarks;
      $post->status_id = 1;
      $post->date_open = date('Y-m-d H:i:s');
      $post->save();
      $id = $post->id;
      
      /********** Proses untuk generate No Aduan **********/
      $number = str_pad($id, 6, "0", STR_PAD_LEFT); // tambah 0 pada last id
      $year = date('Y'); // dapatkan tahun semasa

      $app_no = $year.$number; // combine tahun dan number
      /******* !end Proses untuk generate No Aduan ********/

      // update balik no permohonan
      $post = Complaintlist::find($id);
      $post->application_no = $app_no;
      $post->save();

      if($request->hasfile('attachment')){
        foreach($request->file('attachment') as $file){
            $fileName = $app_no.'_'.time().'.'.$file->extension();
            $file->move(public_path().'/uploads/'.$app_no.'/', $fileName);  
            
            $file = new Complaint_Attachment;
            $file->complaint_id = $id;
            $file->attachment = $fileName;
            $file->save();
        }
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
        'name' => \App\Helper\Utilities::getStaffName($request->user_id),
        'remarks' => $request->remarks,
        'sector' => \App\Helper\Utilities::getSector($request->sector),
        'department' => \App\Helper\Utilities::getDepartment($request->department),
        'lokasi' => $lokasi,
        'location' => $request->location
      ];

      // Audit trail
      Audit::create($id, $app_no, 'Pengadu Log Aduan Baru', null, null, null, null, null, 1, null, null);
      // Emel team helpdesk
      Mail::to('helpdesk@audit.gov.my')->send(new NotifyMail($data));

      if($post){
        Notify::flash('success', __('Aduan telah dihantar.'), 'BERJAYA');
      }else{
        Notify::flash('error', __('Aduan tidak berjaya.'), 'GAGAL');  
      }
    }

    return redirect('/home');
  }

  public function add_file(Request $request)
  {
    $id = $request->id;
    $app_no = \App\Helper\Utilities::getAppNo($id);

    // ADD VALIDATION HERE 
    $validatedData = $request->validate([
        'attachment' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx|max:20480'
    ]);

    if($request->file('attachment')) { // Jika pengadu muatnaik dokumen lampiran
      $fileName = $app_no.'_'.time().'.'.$request->file('attachment')->extension(); // rename file yang diupload ikut no aduan dan masa 
      
      // Create directory if it doesn't exist
      $uploadPath = public_path('uploads/'.$app_no);
      if (!file_exists($uploadPath)) {
        mkdir($uploadPath, 0777, true);
      }
      
      $request->file('attachment')->move($uploadPath, $fileName); // save lampiran di folder /public/uploads/application_no/

      $post = new Complaint_Attachment;
      $post->complaint_id = $id;
      $post->attachment = $fileName;
      $post->save();
    }

    if($post){
      Notify::flash('success', __('Lampiran berjaya dimuatnaik.'), 'BERJAYA');
    }else{
      Notify::flash('error', __('Lampiran tidak berjaya dimuatnaik.'), 'GAGAL');  
    }

    return redirect()->route('edit_complaint', Crypt::encrypt($id));
  }

  public function del_file($id)
  {
    $str_id = EncID::get($id);
    $new_id = explode('|', $str_id);
    $id = $new_id[0];
    $complaint_id = $new_id[1];

    $attach = Complaint_Attachment::where('id', $id)
                                  ->first();

    // Get the application number for the correct file path
    $app_no = \App\Helper\Utilities::getAppNo($complaint_id);
    $filename  = public_path('uploads/'.$app_no.'/'.$attach->attachment);

    if(File::exists($filename)) {    
      //Found existing file then delete
      File::delete($filename);  // or unlink($filename);
    }

    $attach = Complaint_Attachment::find($id)
                                  ->delete();

    if($attach){
      Notify::flash('success', __('Lampiran dihapuskan.'), 'BERJAYA');
    }else{
      Notify::flash('error', __('Lampiran tidak dihapuskan.'), 'GAGAL');  
    }
                   
    return redirect()->route('edit_complaint', Crypt::encrypt($complaint_id));
  }

  public function user_profile()
  {
    $id = Auth::user()->id; // Dapatkan session id pengguna
        
    // Dapatkan maklumat pengguna
    $user = User::leftJoin('roles', 'roles.id', '=', 'users.role_id')
                ->where('users.id', $id)
                ->select('users.*', 'roles.role_desc')
                ->first();

    // Dapatkan senarai jawatan
    $gred = Grade::all();

    // Dapatkan senarai seksyen
    $jawatan = Position::all();

    // Dapatkan senarai sektor                         
    $sectors = Sector::where('active', 1)
                     ->orderBy('order', 'ASC')
                     ->get();

    $departments = Department::where('active', 1)
                             ->where('sector_code', Auth::user()->sector_code)
                             ->get();

    return view('user.profile', compact('user', 'gred', 'jawatan', 'sectors', 'departments'));
  }

  public function upd_profile(Request $request, $id)
  {
    $id = EncID::get($id); // Decrypt ID yang dihantar
      
    // validate data yang diperlukan
    $validatedData = $request->validate(
      [
        'name' => 'required',
        'ic_number' => 'required',
        'gred' => 'required',
        'jawatan' => 'required',
        'sector' => 'required',
        'department' => 'required',
        'block' => 'required',
        'level' => 'required',
        'zone' => 'required',
        'email' => 'required',
        'telephone' => 'required',
        'handphone' => 'required',
      ]
    );

    // umpuk data yang dihantar ke field di DB
    $ic_number = $request->ic_number;
    $name = ucwords(strtolower($request->name));
    $gred = $request->gred;
    $jawatan = $request->jawatan;
    $sector = $request->sector;
    $department = $request->department;
    $block = $request->block;
    $level = $request->level;
    $zone = $request->zone;
    $telephone = $request->telephone;
    $handphone = $request->handphone;
    $email = $request->email;

    // Kemaskini data dalam DB mengikut ID diatas
    $user = User::where('id', $id)
                ->update([
                  'ic_number' => $ic_number,
                  'name' => $name,
                  'gred_code' => $gred,
                  'jaw_code' => $jawatan,
                  'sector_code' => $sector,
                  'department_code' => $department,
                  'block' => $block,
                  'level' => $level,
                  'zone' => $zone,
                  'telephone' => $telephone,
                  'handphone' => $handphone,
                  'email' => $email,
                ]);

      // Audit trail
    Audit::create($id, null, 'Kemaskini Profil', null, null, null, null, null, null, null, null);

    // Papar notifikasi berjaya
    if($user){
      Notify::flash('success', __('Maklumat berjaya dikemaskini.'), 'BERJAYA');
    }else{
      Notify::flash('error', __('Maklumat tidak berjaya dikemaskini.'), 'GAGAL');  
    }

    return redirect()->route('user_profile', Crypt::encrypt($id));
  }

  public function upd_password(Request $request, $id)
  {
    $id = EncID::get($id); // Decrypt ID yang dihantar
    
    // Get current user
    $user = User::find($id);
    
    // validate data yang diperlukan
    $validatedData = $request->validate(
      [
        'current_password' => 'required',
        'new_password' => ['required', Password::min(12)->letters()->mixedCase()->numbers()->symbols()],
        'repeat_password' => 'required|same:new_password'
      ]
    );

    // Check if current password matches
    if (!Hash::check($request->current_password, $user->password)) {
      return redirect()->back()
                     ->withErrors(['current_password' => 'Katalaluan semasa tidak tepat.'])
                     ->withInput();
    }

    // umpuk data yang dihantar ke field di DB
    $password = $request->new_password;

    // Kemaskini data dalam DB mengikut ID diatas
    $user = User::where('id', $id)
                ->update([
                  'password' => Hash::make($password)
                ]);

    // Audit trail
    Audit::create($id, null, 'Pengguna Kemaskini Katalaluan', null, null, null, null, null, null, null, null);

    // Papar notifikasi berjaya
    if($user){
      Notify::flash('success', __('Maklumat berjaya dikemaskini.'), 'BERJAYA');
    }else{
      Notify::flash('error', __('Maklumat tidak berjaya dikemaskini.'), 'GAGAL');  
    }

    return redirect()->route('user_profile', Crypt::encrypt($id));               
  }

  public function faq()
  {
    // Dapatkan soalan lazim
    $faqs = Faq::where('active', 1)
                ->where('show', 1)
                ->get();

    return view('faq.list', compact('faqs'));
  }

  public function verified(Request $request)
  {
    $id = $request->id;

    $c = Complaintlist::find($id);

    $complaint = Complaintlist::where('id', $id)
                              ->update([
                                'status_id' => 6
                              ]);

    // Audit trail
    Audit::create($id, $c->application_no, 'Aduan Disahkan Selesai', null, null, null, null, null, 6, null, null);

    if($complaint){
      $arr = 1;
    }else{
      $arr = 0;
    }

    return json_encode($arr);
  }

  public function unverified(Request $request)
  {
    $id = $request->id;

    $c = Complaintlist::find($id);

    $complaint = Complaintlist::where('id', $id)
                              ->update([
                                'status_id' => 7
                              ]);

    // Audit trail
    Audit::create($id, $c->application_no, 'Aduan Tidak Disahkan Selesai', null, null, null, null, null, 7, null, null);

    if($complaint){
      $arr = 1;
    }else{
      $arr = 0;
    }

    return json_encode($arr);
  }

  public function getSubCatUser($id)
  {
      $subcategory = \App\Models\SubCategory::where('category_id', $id)
                                ->where('active', 1)
                                ->get();
                   
      return response()->json($subcategory);
  }
}
