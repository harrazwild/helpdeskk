<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Helper\Audit;
use App\Helper\Utilities;
use App\Helper\EncID;
use App\Helper\Notify;
use App\Models\Complaintlist;
use App\Models\Complaint_Attachment;
use App\Models\Task;
use App\Models\TaskRemarks;
use App\Models\User;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Detail;
use App\Models\Sector;
use App\Models\Status;
use App\Models\Vendor;
use App\Models\AuditTrail;
use App\Models\V_StatAduan;
use App\Models\V_Complaints;
use App\Mail\NotifyMail;
use App\Mail\DoneMail;
use App\Mail\VerifyMail;
use DataTables;
use PDF;

class MeetingController extends Controller
{
  /** 
    * Dapatkan Senarai Aduan
  **/
  public function index(Request $request)
  {
    $user_id = Auth::user()->id; // Dapatkan ID pengguna yang log masuk  
    $section = Auth::user()->section_id; // Dapatkan seksyen ID pengguna yang log masuk 
    $unit = Auth::user()->unit_id; // Dapatkan unit ID pengguna yang log masuk
    $role = Auth::user()->role_id; // Dapatkan peranan pengguna yang log masuk

    if($request->tahun){
      $y = $request->tahun;
    }else{
      $y = date('Y');
    }

    if($request->bulan){
      $m = $request->bulan;
    }else{
      $m = '';
    }

    if($request->search){
      $search = $request->search;
    }else{
      $search = '';
    }

    if($request->sector){
      $sec = $request->sector;
    }else{
      $sec = '';
    }

    if($request->status){
      $st = $request->status;
    }else{
      $st = '';
    }
      
    // Dapatkan senarai aduan                                                  
    $complaints = Complaintlist::leftJoin('grade', 'grade.grade_code', '=', 'complaints.grade_code')
                               ->leftJoin('positions', 'positions.position_code', '=', 'complaints.position_code')
                               ->leftJoin('sectors', 'sectors.sector_code', '=', 'complaints.sector_code')
                               ->leftJoin('departments', 'departments.department_code', '=', 'complaints.department_code')
                               ->where('complaints.category_id', 12)
                               ->where('complaints.active', 1);

    if($search){ // Jika ada carian
      
      if(strpos($search, ", ")){
        $query = str_replace(", ", " ", $search);
        $keys = explode(" ", $query);
      }elseif(strpos($search, ",")){
        $query = str_replace(",", " ", $search);
        $keys = explode(" ", $query);	
      }else{
        $keys = explode(" ", $search);
      }

      foreach($keys as $k){
        $complaints->where(function($query) use ($k){
          $query->where('complaints.application_no', 'LIKE', '%'.$k.'%')
                ->orWhere('complaints.name', 'LIKE', '%'.$k.'%')
                ->orWhere('complaints.email', 'LIKE', '%'.$k.'%')
                ->orWhere('complaints.remarks', 'LIKE', '%'.$k.'%');
        });
      }

    }

    if($sec){ // Jika ada pilihan sektor
      $complaints->where('complaints.sector_code', $sec);
    }

    if($st){ // Jika ada pilihan status
      $complaints->where('complaints.status_id', $st);
    }

    if($y){
      $complaints->whereYear('complaints.tkh_mula', $y);
    }

    if($m){
      $complaints->whereMonth('complaints.tkh_mula', $m);
    }

    $complaints = $complaints->select('complaints.*', 'grade.grade_desc', 'positions.position_desc', 'sectors.sector_desc', 'departments.department_desc')
                             ->orderBy('complaints.status_id', 'ASC')
                             ->orderBy('complaints.tkh_mula', 'ASC')
                             ->get();
                                                                                   
    //dd($complaints);                      
    // Dapatkan senarai sektor                         
    $sectors = Sector::where('active', 1)
                      ->get();

    // Dapatkan senarai status                 
    $status = Status::where('active', 1)
                    ->get();

    return view('meeting.index', compact('complaints', 'sectors', 'status', 'y', 'm', 'sec', 'st', 'search'));
  }

  public function edit($id)
  {
    $id = EncID::get($id); // Decrypt ID yang dihantar

    // Dapatkan maklumat aduan mengikut ID aduan diatas
    $complaint = Complaintlist::leftJoin('status as st', 'st.id', '=', 'complaints.status_id')
                              ->leftJoin('categories', 'categories.id', '=', 'complaints.category_id')
                              ->leftJoin('grade', 'grade.grade_code', '=', 'complaints.grade_code')
                              ->leftJoin('positions', 'positions.position_code', '=', 'complaints.position_code')
                              ->leftJoin('sectors', 'sectors.sector_code', '=', 'complaints.sector_code')
                              ->leftJoin('departments', 'departments.department_code', '=', 'complaints.department_code')
                              ->where('complaints.id', $id)
                              ->select('complaints.*', 'grade.grade_desc', 'positions.position_desc', 'sectors.sector_desc', 'departments.department_desc', 'st.status_desc', 'categories.section_id')
                              ->first();
    
    $it = explode('|', $complaint->remarks);
    $n = count($it);
    
    // Dapatkan nama pelaksana
    $tasks = Task::where('complaint_id', $id)
                 ->get();               

    if(count($tasks) > 0){
      foreach($tasks as $d){
        $taskID[] = $d->id;
        $userID[] = $d->user_id;
      }
    }else{
      $taskID[] = '';
      $userID[] = '';
    }
                 
    // Dapatkan senarai ulasan
    $remarks = TaskRemarks::leftJoin('users as u', 'u.id', '=', 'task_remarks.user_id')
                          ->where('task_remarks.complaint_id', $id)
                          ->where('task_remarks.active', 1)
                          ->orderBy('created_at', 'ASC')
                          ->select('task_remarks.*', 'u.name')
                          ->get();

    // Dapatkan senarai kategori
    $categories = Category::where('active', 1)
                          ->get();

    // Dapatkan senarai sub kategori
    $subcategories = SubCategory::where('category_id', $complaint->category_id)
                                ->get();            

    // Dapatkan senarai pelaksana
    $staffs = User::where('users.section_id', $complaint->section_id)
                  ->where('users.active', 1)
                  ->where(function($query){
                          $query->where('users.role_id', 2)
                                ->orWhere('users.role_id', 3);
                  })
                  ->select('users.id', 'users.name')
                  ->get();

    return view('meeting.edit', compact('complaint', 'remarks', 'it', 'n', 'categories', 'subcategories', 'staffs', 'taskID', 'userID'));
  }

  public function show($id)
  {
    $id = EncID::get($id); // Decrypt ID yang dihantar

    // Dapatkan maklumat aduan mengikut ID aduan diatas
    $complaint = Complaintlist::leftJoin('status as st', 'st.id', '=', 'complaints.status_id')
                              ->leftJoin('categories', 'categories.id', '=', 'complaints.category_id')
                              ->leftJoin('grade', 'grade.grade_code', '=', 'complaints.grade_code')
                              ->leftJoin('positions', 'positions.position_code', '=', 'complaints.position_code')
                              ->leftJoin('sectors', 'sectors.sector_code', '=', 'complaints.sector_code')
                              ->leftJoin('departments', 'departments.department_code', '=', 'complaints.department_code')
                              ->where('complaints.id', $id)
                              ->select('complaints.*', 'grade.grade_desc', 'positions.position_desc', 'sectors.sector_desc', 'departments.department_desc', 'st.status_desc', 'categories.section_id')
                              ->first();
    
    $it = explode('|', $complaint->remarks);
    $n = count($it);
    
    // Dapatkan nama pelaksana
    $tasks = Task::where('complaint_id', $id)
                 ->get();               

    foreach($tasks as $d){
      $taskID[] = $d->id;
      $userID[] = $d->user_id;
    }

    // Dapatkan senarai ulasan
    $remarks = TaskRemarks::leftJoin('users as u', 'u.id', '=', 'task_remarks.user_id')
                          ->where('task_remarks.complaint_id', $id)
                          ->where('task_remarks.active', 1)
                          ->orderBy('created_at', 'ASC')
                          ->select('task_remarks.*', 'u.name')
                          ->get();

    // Dapatkan senarai kategori
    $categories = Category::where('active', 1)
                          ->get();

    // Dapatkan senarai sub kategori
    $subcategories = SubCategory::where('category_id', $complaint->category_id)
                                ->get();            

    // Dapatkan senarai pelaksana
    $staffs = User::where('users.section_id', $complaint->section_id)
                  ->where('users.active', 1)
                  ->where(function($query){
                          $query->where('users.role_id', 2)
                                ->orWhere('users.role_id', 3);
                  })
                  ->select('users.id', 'users.name')
                  ->get();

    return view('meeting.show', compact('complaint', 'remarks', 'it', 'n', 'categories', 'subcategories', 'userID'));
  }

  public function verify(Request $request)
  {
    $id = $request->id;

    $c = Complaintlist::find($id);

    $complaint = Complaintlist::where('id', $id)
                              ->update([
                                'status_id' => 8,
                                'date_job_done' => date('Y-m-d H:i:s')
                              ]);

    // Audit trail
    Audit::create($id, $c->application_no, 'Aduan Disahkan Selesai', null, null, null, null, null, 8, null, null);

    if($complaint){
      $arr = 1;
    }else{
      $arr = 0;
    }

    return json_encode($arr);
  }

  public function update_meeting(Request $request)
  {                                 
    if($request->status_id < 3){
      $validatedData = $request->validate(
        [
          'category' => 'required',
          'subcategory' => 'required',
          'staff1' => 'required',
        ]
      );
    }

    // umpuk data yang dihantar ke field di DB
    $id = $request->complaint_id;
    $category = $request->category;
    $subcategory = $request->subcategory;
    $old_task1 = $request->old_taskID1;
    $old_task2 = $request->old_taskID2;
    $staff1 = $request->staff1;
    $staff2 = $request->staff2;
    $remark = $request->remarks;

    $compl = Complaintlist::where('id', $id)
                          ->first();
  
    $c_task1 = Task::find($old_task1); // check pegawai pelaksana 1
    
    if($c_task1){ // update jika dh wujud
      if($c_task1->user_id != $staff1){
        $t = Task::find($old_task1);
        $t->user_id = $staff1;
        $t->save();
      }
    }else{ // create baru jika belum wujud
      $t = new Task;
      $t->user_id = $staff1;
      $t->complaint_id = $id;
      $t->save();
    }

    $c_task2 = Task::find($old_task2); // check pegawai pelaksana 2
    
    if($c_task2){ // update jika dh wujud
      if($c_task2->user_id != $staff2){
        $t = Task::find($old_task2);
        $t->user_id = $staff2;
        $t->save();
      }
    }else{ // create baru jika belum wujud
      $t = new Task;
      $t->user_id = $staff2;
      $t->complaint_id = $id;
      $t->save();
    }

    $post = Complaintlist::find($id);

    if($compl->category_id != $category){
      $post->category_id = $category;
    }

    if($compl->subcategory_id != $subcategory){
      $post->subcategory_id = $subcategory;
    }

    $post->status_id = 2;
    $post->save();

    if($remark != ''){
      // umpuk data yang dihantar ke field di DB
      $r = new TaskRemarks;
      $r->complaint_id = $id;
      $r->user_id = Auth::user()->id;
      $r->remarks = $remark;
      $r->save();
    }
      
    // Audit trail
    $appno = Utilities::getAppNo($id);
    Audit::create($id, $appno, 'Kemaskini Aduan', $staff1, null, $category, $subcategory, null, 2, $remark, null);

    // Papar notifikasi berjaya
    if($post){
      Notify::flash('success', __('Aduan Berjaya Dikemaskini.'), 'BERJAYA');
    }else{
      Notify::flash('error', __('Aduan Tidak Berjaya Dikemaskini.'), 'GAGAL');
    }

    return redirect()->back(); 
  }
}
