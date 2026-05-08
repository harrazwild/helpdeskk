<?php

/** 
  * Fail ini mengandungi segala yang berkaitan dengan Sub Menu Maklumat Aduan
  * By : MUHD SYARIZAN B. YAACOB
  * Date Created : Disember 2020
**/

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

class ComplaintlistController extends Controller
{
  public function ajaxComplaint()
  {
    
  }

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

    if($request->u_task){
      $u_task = $request->u_task;
    }else{
      $u_task = '';
    }

    if($request->status){
      $st = $request->status;
    }else{
      $st = '';
    }
      
    // Statistik jumlah aduan bagi diri sendiri
    
    if($role == 3){ // Jika peranan pelaksana
      $task1 = Complaintlist::leftJoin('tasks', 'tasks.complaint_id', '=', 'complaints.id')
                           ->where('complaints.active', 1)
                           ->where('complaints.category_id', '!=', 12)
                           ->where('complaints.status_id', 2)
                           // ->where(function($query){
                           //    $query->where('complaints.status_id', 2)
                           //          ->orWhere('complaints.status_id', 4);
                           // })
                           ->where('tasks.user_id', $user_id)
                           ->count();
    }elseif($role == 4){ // jika peranan pegawai
      $task1 = Complaintlist::where('complaints.active', 1)
                           ->where('complaints.category_id', '!=', 12)
                           ->where('complaints.status_id', 3)
                           ->where('complaints.officer_id', $user_id)
                           ->count();
    }elseif($role == 2){ // jika peranan penyelaras aduan
      $task1 = Complaintlist::leftJoin('tasks', 'tasks.complaint_id', '=', 'complaints.id')
                           ->leftJoin('categories', 'categories.id', '=', 'complaints.category_id')
                           ->where('complaints.active', 1)
                           ->where('complaints.category_id', '!=', 12)
                           // ->where('categories.section_id', $section)
                           ->where(function($query) use ($user_id){
                              $query->where(function($q) use ($user_id){
                                $q->where('tasks.user_id', $user_id)
                                  ->Where('complaints.status_id', 2);
                              });
                              $query->orWhere(function($x){
                                $x->where('complaints.status_id', 6)
                                  ->orWhere('complaints.status_id', 7);
                                });
                            })
                           ->count();
    }elseif($role == 7 || $role == 8){ // Jika peranan Pegawai Aplikasi atau Vendor
      $assignedSubcategories = DB::table('user_subcategories')
                                  ->where('user_id', $user_id)
                                  ->pluck('subcategory_id');
      $task1 = Complaintlist::where('active', 1)
                            ->where('category_id', 10) // Kategori Aplikasi
                            ->whereIn('subcategory_id', $assignedSubcategories)
                            ->whereIn('status_id', [2, 11]) // Dalam Tindakan atau Semakan
                            ->count();
    }else{
      $task1 = '';                                     
    }

    // Statistik jumlah aduan mengikut seksyen
    $total = V_StatAduan::select(DB::raw('SUM(new) as new'),
                                 DB::raw('SUM(dt) as dt'),
                                 DB::raw('SUM(done) as done'),
                                 DB::raw('SUM(verify) as verify'),
                                 DB::raw('SUM(close) as close')
    );
    
    if($y){
      $total->where('year', $y);
    }
    
    $total = $total->first();

    // Dapatkan senarai aduan                                                  
    $complaints = V_Complaints::select('*');

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
          $query->where('application_no', 'LIKE', '%'.$k.'%')
                ->orWhere('name', 'LIKE', '%'.$k.'%')
                ->orWhere('email', 'LIKE', '%'.$k.'%')
                ->orWhere('remarks', 'LIKE', '%'.$k.'%');
        });
      }

    }

    if($sec){ // Jika ada pilihan sektor
      $complaints->where('sector_code', $sec);
    }

    if($u_task){ // Jika ada pilihan sektor
      $complaints->where('user_id', $u_task);
    }

    if($st){ // Jika ada pilihan status
      if($st == 4){
        $complaints->where(function($query){
          $query->where('status_id', 4)
                ->orWhere('status_id', 5);
        });
      }else{
        $complaints->where('status_id', $st);
      }
    }

    if($y){
      $complaints->whereYear('date_open', $y);
    }

    if($m){
      $complaints->whereMonth('date_open', $m);
    }

    if($role == 7 || $role == 8) {
      $assignedSubcategories = DB::table('user_subcategories')
                                  ->where('user_id', $user_id)
                                  ->pluck('subcategory_id');
      $complaints->whereIn('subcategory_id', $assignedSubcategories);
    }

    $complaints = $complaints->where('category_id', '!=', 12)
                             ->orderBy('status_id', 'ASC')
                             ->orderBy('date_open', 'ASC')
                             ->get();
                                                                                   
    //dd($complaints);                      
    // Dapatkan senarai sektor                         
    $sectors = Sector::where('active', 1)
                      ->get();

    // Dapatkan senarai status                 
    $status = Status::where('active', 1)
                    ->get();

    $staffs = User::where('role_id', '!=', 6)
                  ->where('role_id', '!=', 4)
                  ->get();                 

    return view('complaints.index', compact('complaints', 'sectors', 'staffs', 'status', 'total', 'task1', 'y', 'm', 'sec', 'u_task', 'st', 'search'));
  }

  public function show_coordinator($id)
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
                         
    $attach = Complaint_Attachment::where('complaint_id', $id)
                                  ->get();

    if($complaint->status_id == 4 || $complaint->status_id == 5){

      $now = time();

      $date = AuditTrail::where('object_id', $id)
                        ->where(function($query){
                            $query->where('status', 4)
                                  ->orWhere('status', 5);
                        })
                        ->select('created_at')
                        ->first();
      $day = abs($now - strtotime($date->created_at));

      $day = round($day / (60 * 60 * 24));

    }else{
      $day = 0;
    }

    $d = explode(',', $complaint->detail);

    // Dapatkan nama pelaksana
    $tasks = Task::where('complaint_id', $id)
                 ->first();               

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

    // Dapatkan senarai status
    $status = Status::where('active', 1)
                    ->get();

    // Dapatkan senarai pegawai
    $officers = User::where('active', 1)
                    ->where('role_id', 4)
                    ->get();

    // Dapatkan senarai sub kategori
    $subcategories = SubCategory::where('category_id', $complaint->category_id)
                                ->get();            

    // Dapatkan senarai perincian
    $details = Detail::where('subcategory_id', $complaint->subcategory_id)
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

    return view('complaints.show_coordinator', compact('complaint', 'day', 'attach', 'd', 'remarks', 'officers', 'status', 'categories', 'subcategories', 'details', 'staffs', 'tasks'));

  }

  public function show_technical($id)
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
                              
    $attach = Complaint_Attachment::where('complaint_id', $id)
                                  ->get();

    $d = explode(',', $complaint->detail);

    // Dapatkan nama pelaksana
    $tasks = Task::where('complaint_id', $id)
                 ->first();               

    // Dapatkan senarai ulasan
    $remarks = TaskRemarks::leftJoin('users as u', 'u.id', '=', 'task_remarks.user_id')
                          ->where('task_remarks.complaint_id', $id)
                          ->where('task_remarks.active', 1)
                          ->orderBy('created_at', 'ASC')
                          ->select('task_remarks.*', 'u.name')
                          ->get();

    // Dapatkan senarai status
    $status = Status::where('active', 1)
                    ->get();

    // Dapatkan senarai pegawai
    $officers = User::where('active', 1)
                    ->where('role_id', 4)
                    ->where('section_id', Auth::user()->section_id)
                    ->get();

    $vendors = Vendor::where('active', 1)
                     ->get();                

    // Dapatkan senarai sub kategori
    $subcategories = SubCategory::where('category_id', $complaint->category_id)
                                ->get();            

    // Dapatkan senarai perincian
    $details = Detail::where('subcategory_id', $complaint->subcategory_id)
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

    return view('complaints.show_technical', compact('complaint', 'attach', 'd', 'remarks', 'officers', 'vendors', 'status', 'subcategories', 'details', 'staffs', 'tasks'));

  }

  public function show_verify($id)
  {
    $id = EncID::get($id); // Decrypt ID yang dihantar

    // Dapatkan maklumat aduan mengikut ID aduan diatas
    $complaint = Complaintlist::leftJoin('status as st', 'st.id', '=', 'complaints.status_id')
                              ->leftJoin('grade', 'grade.grade_code', '=', 'complaints.grade_code')
                              ->leftJoin('positions', 'positions.position_code', '=', 'complaints.position_code')
                              ->leftJoin('sectors', 'sectors.sector_code', '=', 'complaints.sector_code')
                              ->leftJoin('departments', 'departments.department_code', '=', 'complaints.department_code')
                              ->where('complaints.id', $id)
                              ->select('complaints.*', 'grade.grade_desc', 'positions.position_desc', 'sectors.sector_desc', 'departments.department_desc', 'st.status_desc')
                              ->first();

                              //dd($complaint);
    $attach = Complaint_Attachment::where('complaint_id', $id)
                                  ->get();

    $d = explode(',', $complaint->detail);

    // Dapatkan nama pelaksana
    $tasks = Task::where('complaint_id', $id)
                 ->first();               

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

    // Dapatkan senarai status
    $status = Status::where('active', 1)
                    ->get();

    // Dapatkan senarai pegawai
    $officers = User::where('active', 1)
                    ->where('role_id', 4)
                    ->where('section_id', Auth::user()->section_id)
                    ->get();

    // Dapatkan senarai sub kategori
    $subcategories = SubCategory::where('category_id', $complaint->category_id)
                                ->get();            

    // Dapatkan senarai perincian
    $details = Detail::where('subcategory_id', $complaint->subcategory_id)
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

    return view('complaints.show_verify', compact('complaint', 'attach', 'd', 'remarks', 'officers', 'status', 'categories', 'subcategories', 'details', 'staffs', 'tasks'));

  }

  public function show_officer($id)
  {
    $id = EncID::get($id); // Decrypt ID yang dihantar

    // Dapatkan maklumat aduan mengikut ID aduan diatas
    $complaint = Complaintlist::leftJoin('status as st', 'st.id', '=', 'complaints.status_id')
                              ->leftJoin('grade', 'grade.grade_code', '=', 'complaints.grade_code')
                              ->leftJoin('positions', 'positions.position_code', '=', 'complaints.position_code')
                              ->leftJoin('sectors', 'sectors.sector_code', '=', 'complaints.sector_code')
                              ->leftJoin('departments', 'departments.department_code', '=', 'complaints.department_code')
                              ->where('complaints.id', $id)
                              ->select('complaints.*', 'grade.grade_desc', 'positions.position_desc', 'sectors.sector_desc', 'departments.department_desc', 'st.status_desc')
                              ->first();
                              
    $attach = Complaint_Attachment::where('complaint_id', $id)
                                  ->get();

    $d = explode(',', $complaint->detail);

    // Dapatkan nama pelaksana
    $tasks = Task::where('complaint_id', $id)
                 ->first();               

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

    // Dapatkan senarai status
    $status = Status::where('active', 1)
                    ->get();

    // Dapatkan senarai pegawai
    $officers = User::where('active', 1)
                    ->where('role_id', 4)
                    ->where('section_id', Auth::user()->section_id)
                    ->get();

    // Dapatkan senarai sub kategori
    $subcategories = SubCategory::where('category_id', $complaint->category_id)
                                ->get();            

    // Dapatkan senarai perincian
    $details = Detail::where('subcategory_id', $complaint->subcategory_id)
                     ->get();

    // Dapatkan senarai pelaksana
    $staffs = User::where('users.active', 1)
                  ->where(function($query){
                          $query->where('users.role_id', 2)
                                ->orWhere('users.role_id', 3);
                  })
                  ->select('users.id', 'users.name')
                  ->get();

    return view('complaints.show_officer', compact('complaint', 'attach', 'd', 'remarks', 'officers', 'status', 'categories', 'subcategories', 'details', 'staffs', 'tasks'));

  }

  /** 
    * Paparan Maklumat Aduan
  **/
  public function show_disabled($id)
  {
    $id = EncID::get($id); // Decrypt ID yang dihantar

    // Dapatkan maklumat aduan mengikut ID aduan diatas
    $complaint = Complaintlist::leftJoin('status as st', 'st.id', '=', 'complaints.status_id')
                              ->leftJoin('grade', 'grade.grade_code', '=', 'complaints.grade_code')
                              ->leftJoin('positions', 'positions.position_code', '=', 'complaints.position_code')
                              ->leftJoin('sectors', 'sectors.sector_code', '=', 'complaints.sector_code')
                              ->leftJoin('departments', 'departments.department_code', '=', 'complaints.department_code')
                              ->where('complaints.id', $id)
                              ->select('complaints.*', 'grade.grade_desc', 'positions.position_desc', 'sectors.sector_desc', 'departments.department_desc', 'st.status_desc')
                              ->first();
                              
    $attach = Complaint_Attachment::where('complaint_id', $id)
                                  ->get();

    $d = explode(',', $complaint->detail);

    // Dapatkan nama pelaksana
    $tasks = Task::where('complaint_id', $id)
                 ->first();               

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

    // Dapatkan senarai status
    $status = Status::where('active', 1)
                    ->get();

    // Dapatkan senarai pegawai
    $officers = User::where('active', 1)
                    ->where('role_id', 4)
                    ->get();

    // Dapatkan senarai sub kategori
    $subcategories = SubCategory::where('category_id', $complaint->category_id)
                                ->get();            

    // Dapatkan senarai perincian
    $details = Detail::where('subcategory_id', $complaint->subcategory_id)
                     ->get();

    // Dapatkan senarai pelaksana
    $staffs = User::where('users.section_id', Auth::user()->section_id)
                  ->where('users.active', 1)
                  ->where(function($query){
                          $query->where('users.role_id', 2)
                                ->orWhere('users.role_id', 3);
                  })
                  ->select('users.id', 'users.name')
                  ->get();

    return view('complaints.show_disabled', compact('complaint', 'attach', 'd', 'remarks', 'officers', 'status', 'categories', 'subcategories', 'details', 'staffs', 'tasks'));
  }

  /** 
    * Papar Maklumat Aduan Selesai
  **/
  public function show_done($id)
  {
    $id = EncID::get($id); // Decrypt ID yang dihantar

    // Dapatkan maklumat aduan mengikut ID aduan diatas
    $complaint = Complaintlist::leftJoin('status as st', 'st.id', '=', 'complaints.status_id')
                              ->leftJoin('grade', 'grade.grade_code', '=', 'complaints.grade_code')
                              ->leftJoin('positions', 'positions.position_code', '=', 'complaints.position_code')
                              ->leftJoin('sectors', 'sectors.sector_code', '=', 'complaints.sector_code')
                              ->leftJoin('departments', 'departments.department_code', '=', 'complaints.department_code')
                              ->where('complaints.id', $id)
                              ->select('complaints.*', 'grade.grade_desc', 'positions.position_desc', 'sectors.sector_desc', 'departments.department_desc', 'st.status_desc')
                              ->first();
                              
    $attach = Complaint_Attachment::where('complaint_id', $id)
                                  ->get();

    $d = explode(',', $complaint->detail);

    // Dapatkan nama pelaksana
    $tasks = Task::where('complaint_id', $id)
                 ->first();               

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

    // Dapatkan senarai status
    $status = Status::where('active', 1)
                    ->get();

    // Dapatkan senarai pegawai
    $officers = User::where('active', 1)
                    ->where('role_id', 4)
                    ->get();

    // Dapatkan senarai sub kategori
    $subcategories = SubCategory::where('category_id', $complaint->category_id)
                                ->get();            

    // Dapatkan senarai perincian
    $details = Detail::where('subcategory_id', $complaint->subcategory_id)
                     ->get();

    // Dapatkan senarai pelaksana
    $staffs = User::where('users.section_id', Auth::user()->section_id)
                  ->where('users.active', 1)
                  ->where(function($query){
                          $query->where('users.role_id', 2)
                                ->orWhere('users.role_id', 3);
                  })
                  ->select('users.id', 'users.name')
                  ->get();

    return view('complaints.show_done', compact('complaint', 'attach', 'd', 'remarks', 'officers', 'status', 'categories', 'subcategories', 'details', 'staffs', 'tasks'));
  }

  public function update_complaint_coordinator(Request $request)
  {                                 
    if($request->status_id < 3){
      $validatedData = $request->validate(
        [
          'category' => 'required',
          'subcategory' => 'required',
          'staff' => 'required',
        ]
      );
    }

    // umpuk data yang dihantar ke field di DB
    $id = $request->complaint_id;
    $category = $request->category;
    $subcategory = $request->subcategory;
    $staff = $request->staff;
    $remark = $request->remarks;
    $status_id = $request->status_id;

    if($request->detail != ''){
      $details = implode(',', $request->detail);
    }else{
      $details = null;
    }

    if($status_id < 3){ // jika status aduan belum selesai

      $compl = Complaintlist::where('id', $id)
                            ->first();
    
      // Dapatkan maklumat pelaksana pegawai teknikal sebelum kemaskini
      $existingTask = Task::where('complaint_id', $id)->first();
      $oldStaff = $existingTask ? $existingTask->user_id : null;

      $task = Task::updateOrCreate(
                                    ['complaint_id' => $id],
                                    ['user_id' => $staff]
                                  );

      // Hantar emel notifikasi jika pelaksana baru ditukar oleh Penyelaras Aduan
      if ($oldStaff != $staff) {
          $assignedStaff = User::find($staff);
          if ($assignedStaff && $assignedStaff->email) {
              $lokasi = '';
              if($compl->block != '')
                $lokasi = "Blok ".$compl->block;
              if($compl->level != '')
                $lokasi .= ", ".$compl->level;
              if($compl->zone != '')
                $lokasi .= ", Zon ".$compl->zone;

              $data = [
                'id' => $id,
                'app_no' => $compl->application_no,
                'name' => $compl->name,
                'remarks' => $compl->remarks,
                'sector' => Utilities::getSector($compl->sector_code),
                'department' => Utilities::getDepartment($compl->department_code),
                'lokasi' => $lokasi,
                'location' => $compl->location
              ];
              
              // Hantar emel menggunakan template NotifyMail sedia ada
              Mail::to($assignedStaff->email)->send(new NotifyMail($data));
          }
      }

      $post = Complaintlist::find($id);

      if($compl->category_id != $category){
        $post->category_id = $category;
      }

      if($compl->subcategory_id != $subcategory){
        $post->subcategory_id = $subcategory;
      }

      if($compl->detail != $details){
        $post->detail = $details; 
      }

      if($task){
        $post->status_id = 2;
      }

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
      Audit::create($id, $appno, 'Kemaskini Aduan', $staff, null, $category, $subcategory, $details, 2, $remark, null);

    }else{
      
      $post = Complaintlist::find($id);

      if($request->status){
        $post->status_id = $request->status;
      }

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
      Audit::create($id, $appno, 'Kemaskini Aduan', $staff, null, $category, $subcategory, $details, 8, $remark, null);

    }

    // Papar notifikasi berjaya
    if($post){
      Notify::flash('success', __('Aduan Berjaya Dikemaskini.'), 'BERJAYA');
    }else{
      Notify::flash('error', __('Aduan Tidak Berjaya Dikemaskini.'), 'GAGAL');
    }
    
    $s = Task::where('complaint_id', $id)
             ->first();

    if($request->status && $request->status == 8){
      return redirect()->route('show_done', Crypt::encrypt($id)); 
    }else{
      
      if($s->user_id == Auth::user()->id ){
        return redirect()->route('show_technical', Crypt::encrypt($id));
      }else{
        return redirect()->back();  
      }

    }
  }

  public function update_complaint_technical(Request $request)
  {                                 
    //dd($request);

    // Validate data yang diperlukan
    $validatedData = $request->validate(
      [
        'subcategory' => 'required',
      ]
    );

    // umpuk data yang dihantar ke field di DB
    $id = $request->complaint_id;
    $subcategory = $request->subcategory;
    $status = $request->status;
    $officer = $request->officer;
    $vendor = $request->vendor;
    $remark = $request->remarks;
    
    if($request->mark){
      $mark = $request->mark;
    }else{
      $mark = 0;
    }
    
    if($request->detail != ''){
      $details = implode(',', $request->detail);
    }else{
      $details = null;
    }

    $compl = Complaintlist::where('id', $id)->first();
    
    $s = Task::where('complaint_id', $id)
             ->first();

    if(isset($request->staff) && $s && $request->staff != $s->user_id){
      $task = Task::find($s->id);
      $task->user_id = $request->staff;
      $task->save();
      $status = 2;
    }

    $post = Complaintlist::find($id);

    if($compl->subcategory_id != $subcategory){
      $post->subcategory_id = $subcategory;
    }

    if($compl->detail != $details){
      $post->detail = $details; 
    }

    if($compl->detail != $details){
      $post->detail = $details; 
    }

    if($compl->status_id != $status && $status != null){
      
      if($status == 3){
        $post->officer_id = $officer;
        $post->status_id = $status;
      }elseif($status == 9){
        $post->vendor_id = $vendor;
        $post->status_id = $status;
      }else{
        $post->status_id = $status;
        $post->date_job_done = date('Y-m-d H:i:s');
      }
    }

    if($compl->vendor_id != $vendor){
      $status = 9;
      $post->vendor_id = $vendor;
      $post->status_id = $status; 
    }

    $post->save();

    if($remark != ''){
      // umpuk data yang dihantar ke field di DB
      $r = new TaskRemarks;
      $r->complaint_id = $id;
      $r->user_id = Auth::user()->id;
      $r->remarks = $remark;
      $r->mark = $mark;
      $r->save();
    }
    
    // Audit trail
    $appno = Utilities::getAppNo($id);
    Audit::create($id, $appno, 'Kemaskini Aduan', $request->staff, $officer, null, $subcategory, $details, $status, $remark, $vendor);

    if($status == 5 || $status == 11){ // jika aduan selesai atau dihantar untuk semakan
      $lokasi = '';
      if($request->block != '')
        $lokasi = "Blok ".$request->block;
      if($request->level != '')
        $lokasi .= ", ".$request->level;
      if($request->zone != '')
        $lokasi .= ", Zon ".$request->zone;

      $data = [
        'app_no' => $compl->application_no,
        'name' => $compl->name,
        'remarks' => $compl->remarks,
        'sector' => \App\Helper\Utilities::getSector($compl->sector_code),
        'department' => \App\Helper\Utilities::getDepartment($compl->department_code),
        'lokasi' => $lokasi,
        'location' => $compl->location
      ];
      
      if($status == 5) {
          Mail::to($compl->email)->send(new VerifyMail($data));
      } else if($status == 11) {
          // Emel Pegawai Aplikasi
          $assignedUsers = User::leftJoin('user_subcategories', 'users.id', '=', 'user_subcategories.user_id')
                               ->where('user_subcategories.subcategory_id', $subcategory)
                               ->where('users.role_id', 7) // Pegawai Aplikasi
                               ->where('users.active', 1)
                               ->select('users.email')
                               ->get();
                               
          foreach($assignedUsers as $u) {
              Mail::to($u->email)->send(new NotifyMail($data));
              sleep(1);
          }
      }
    }

    // Papar notifikasi berjaya
    if($post){
      Notify::flash('success', __('Aduan Berjaya Dikemaskini.'), 'BERJAYA');
    }else{
      Notify::flash('error', __('Aduan Tidak Berjaya Dikemaskini.'), 'GAGAL');
    }

    return redirect('complaintlist');
  }

  public function update_complaint_verify(Request $request)
  {                                 
    // umpuk data yang dihantar ke field di DB
    $id = $request->complaint_id;
    $status = $request->status;
    $remark = $request->remarks;

    $compl = Complaintlist::where('id', $id)
                          ->first();
    
    $post = Complaintlist::find($id);

    if($compl->status_id != $status && $status != null){
      if($status == 8){
        $post->status_id = $status;
        $post->date_close = date('Y-m-d H:i:s');
      }else{
        $post->status_id = $status;
      }
    }

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
    Audit::create($id, $appno, 'Kemaskini Aduan', null, null, null, null, null, $status, $remark, null);

    // Papar notifikasi berjaya
    if($post){
      Notify::flash('success', __('Aduan Berjaya Dikemaskini.'), 'BERJAYA');
    }else{
      Notify::flash('error', __('Aduan Tidak Berjaya Dikemaskini.'), 'GAGAL');
    }

    return redirect('complaintlist');
  }

  /** 
    * KPP Kemaskini Aduan
  **/
  public function kpp_update_complaint(Request $request)
  {                                 
    // Validate data yang diperlukan
    $validatedData = $request->validate(
      [
        'staff' => 'required'
      ]
    );

    // umpuk data yang dihantar ke field di DB
    $id = $request->complaint_id;
    $staff = $request->staff;
    $remark = $request->remarks;

    $compl = Complaintlist::where('id', $id)
                          ->first();

    Task::updateOrCreate(
                          ['complaint_id' => $id],
                          ['user_id' => $staff]
                        );

    if($compl->status_id == 1){
      $post = Complaintlist::find($id);
      $post->status_id = 2;
      $post->save();
    }

    if($remark != ''){
      // umpuk data yang dihantar ke field di DB
      $post = new TaskRemarks;
      $post->complaint_id = $id;
      $post->user_id = Auth::user()->id;
      $post->remarks = $remark;
      $post->save();
    }

    // Audit trail
    $appno = Utilities::getAppNo($id);
    Audit::create($id, $appno, 'KPP/Pegawai Kemaskini Aduan', $staff, null, null, null, null, 2, $remark, null);

    // Papar notifikasi berjaya
    if($post){
      Notify::flash('success', __('Aduan Berjaya Dikemaskini.'), 'BERJAYA');
    }else{
      Notify::flash('error', __('Aduan Tidak Berjaya Dikemaskini.'), 'GAGAL');
    }

    return redirect()->back();
  }

  /** 
    * Pegawai Kemaskini Aduan
  **/
  public function officer_update_complaint(Request $request)
  {                                 
    // umpuk data yang dihantar ke field di DB
    $id = $request->complaint_id;
    $staff = $request->staff;
    $status = $request->status;
    $remark = $request->remarks;

    if($remark != ''){
      // umpuk data yang dihantar ke field di DB
      $post = new TaskRemarks;
      $post->complaint_id = $id;
      $post->user_id = Auth::user()->id;
      $post->remarks = $remark;
      $post->save();
    }

    if(isset($staff)){
      Task::updateOrCreate(
                            ['complaint_id' => $id],
                            ['user_id' => $staff]
                          );
    }

    $compl = Complaintlist::where('id', $id)->first();
    
    $post = Complaintlist::find($id);

    if($compl->status_id != $status && $status != null){
      $post->status_id = $status;
      $post->date_job_done = date('Y-m-d H:i:s');

      // Audit trail
      $appno = Utilities::getAppNo($id);
      Audit::create($id, $appno, 'Pegawai Kemaskini Aduan', $staff, null, null, null, null, $status, $remark, null);
    }else{
      $status = $request->status_id;

      // Audit trail
      $appno = Utilities::getAppNo($id);
      Audit::create($id, $appno, 'Pegawai Tukar Pegawai Teknikal', $staff, null, null, null, null, $status, $remark, null);
    }

    $post->save();

    if($status == 4){ // jika aduan selesai
      $lokasi = '';
      if($request->block != '')
        $lokasi = "Blok ".$request->block;
      if($request->level != '')
        $lokasi .= ", ".$request->level;
      if($request->zone != '')
        $lokasi .= ", Zon ".$request->zone;

      $data = [
        'app_no' => $compl->application_no,
        'name' => $compl->name,
        'remarks' => $compl->remarks,
        'sector' => \App\Helper\Utilities::getSector($compl->sector_code),
        'department' => \App\Helper\Utilities::getDepartment($compl->department_code),
        'lokasi' => $lokasi,
        'location' => $compl->location
      ];
      Mail::to($compl->email)->send(new VerifyMail($data));
    }

    // Papar notifikasi berjaya
    if($post){
      Notify::flash('success', __('Aduan Berjaya Dikemaskini.'), 'BERJAYA');
    }else{
      Notify::flash('error', __('Aduan Tidak Berjaya Dikemaskini.'), 'GAGAL');
    }

    return redirect()->back();
  }

  /** 
    * Dapatkan Maklumat Pegawai
  **/
  public function getOfficers()
  {
    // Dapatkan maklumat sub kategori mengikut id diatas
    $staffs = User::where('section_id', Auth::user()->section_id)
                   ->where('role_id', 4)
                   ->get();
                 
    return response()->json($staffs); // Hantar data dalam bentuk JSON
  }

  /** 
    * Tugaskan Pelaksana Aduan
  **/
  public function assigntask(Request $request)
  {
    $complaint_id = $request->id; // Dapatkan ID aduan yang dihantar
    $user_id = $request->staff; // Dapatkan ID pelaksana yang dihantar

    // Update task atau create task jika belum wujud 
    $task = Task::updateOrCreate(
      ['complaint_id' => $complaint_id],
      ['user_id' => $user_id]
    );

    // Jika update atau create berjaya, setkan status aduan
    if($task){

      $complaint = Complaintlist::where('id', $complaint_id)
                                ->update(['status_id' => 2]);

      // Audit trail
      $appno = Utilities::getAppNo($complaint_id);
      Audit::create($complaint_id, $appno, 'Ambil Tindakan', $user_id, null, null, null, null, 2, null, null);
    
    }

    if($task){
      $arr = 1;
    }else{
      $arr = 0;
    }

    return json_encode($arr);
  }

  /** 
    * Hapus Aduan
  **/
  public function delete(Request $request)
  {
    $id = $request->id; // Dapatkan ID aduan yang dihantar
    
    if(is_array($id)) {
        foreach($id as $i) {
            Complaintlist::where('id', $i)->update(['active' => 0]);
            $appno = Utilities::getAppNo($i);
            Audit::create($i, $appno, 'Hapus Aduan', null, null, null, null, null, null, null, null);
        }
        $arr = 1;
        return json_encode($arr);
    } else {
        // Hapus aduan, set aktif kepada 0
        $complaint = Complaintlist::where('id', $id)
                                  ->update(['active' => 0]);

        // Audit trail
        $appno = Utilities::getAppNo($id);
        Audit::create($id, $appno, 'Hapus Aduan', null, null, null, null, null, null, null, null);

        if($complaint){
          $arr = 1;
        }else{
          $arr = 0;
        }

        return json_encode($arr);  
    }
  }

  /** 
    * Hapus Ulasan
  **/
  public function delRemark(Request $request)
  {
    $id = $request->id; // Dapatkan ID aduan yang dihantar

    // Hapus ulasan, set aktif kepada 0
    $remarks = TaskRemarks::find($id);
    $remarks->active = 0;
    $remarks->save();

    // Audit trail
    $appno = Utilities::getTaskAppNo($id);
    Audit::create($id, $appno, 'Hapus Ulasan', null, null, null, null, null, null, null, null);

    if($remarks){
      $arr = 1;
    }else{
      $arr = 0;
    }

    return json_encode($arr);  
  }

  /** 
    * Dapatkan Ulasan
  **/
  public function getRemark($id)
  {
    // Dapatkan ulasan mengikut ID diatas
    $remarks = TaskRemarks::where('id', $id)
                          ->first();

    return response()->json($remarks);  
  }

  /** 
    * Kemaskini Ulasan
  **/
  public function updRemark(Request $request)
  {
    $id = $request->remarks_id; // Dapatkan ID aduan yang dihantar
    $remarks = $request->remarks; // Dapatkan ulasan yang dihantar

    if($request->mark){
      $mark = $request->mark;
    }else{
      $mark = 0;
    }

    // Kemaskini komen
    $remarks = TaskRemarks::where('id', $id)
                          ->update(['remarks' => $remarks, 'mark' => $mark]);

    // Audit trail
    $appno = Utilities::getTaskAppNo($id);
    Audit::create($id, $appno, 'Kemaskini Ulasan', null, null, null, null, null, null, null, null);

    // Papar notifikasi berjaya
    if($remarks){
      Notify::flash('success', __('Aduan Berjaya Dikemaskini.'), 'BERJAYA');
    }else{
      Notify::flash('error', __('Aduan Tidak Berjaya Dikemaskini.'), 'GAGAL');
    }
    
    return redirect()->back();  
  }

  /** 
    * Hantar Email
  **/
  public function send_emel(Request $request)
  {
    $id = $request->id;

    $complaint = Complaintlist::leftJoin('tasks', 'complaints.id', '=', 'tasks.complaint_id')
                              ->leftJoin('users', 'tasks.user_id', '=', 'users.id')
                              ->where('complaints.id', $id)
                              ->select('users.name as pelaksana', 'users.email', 'complaints.application_no', 'complaints.name as pengadu', 'complaints.remarks', 'complaints.sector_code', 'complaints.department_code', 'complaints.block', 'complaints.level', 'complaints.zone', 'complaints.location')
                              ->first();

    $lokasi = '';
    if($complaint->block != '')
      $lokasi = "Blok ".$complaint->block;
    if($complaint->level != '')
      $lokasi .= ", ".$complaint->level;
    if($complaint->zone != '')
      $lokasi .= ", Zon ".$complaint->zone;

    // Maklumat yang hendak dipaparkan di dalam emel notifikasi
    $data = [
      'app_no' => $complaint->application_no,
      'name' => $complaint->pengadu,
      'remarks' => $complaint->remarks,
      'sector' => \App\Helper\Utilities::getSector($complaint->sector_code),
      'department' => \App\Helper\Utilities::getDepartment($complaint->department_code),
      'lokasi' => $lokasi,
      'location' => $complaint->location
    ];

    Mail::to($complaint->email)->send(new NotifyMail($data));

    if($complaint){
      $arr = 1;
    }else{
      $arr = 0;
    }

    return json_encode($arr);
  }

  /** 
    * Dapatkan Senarai Aduan
  **/
  public function ontask(Request $request)
  {
    $user_id = Auth::user()->id; // Dapatkan ID pengguna yang log masuk  
    $section = Auth::user()->section_id; // Dapatkan seksyen ID pengguna yang log masuk 
    $unit = Auth::user()->unit_id; // Dapatkan unit ID pengguna yang log masuk
    $role = Auth::user()->role_id; // Dapatkan peranan pengguna yang log masuk

    if($request->tahun){
      $y = $request->tahun;
    }else{
      $y = '';
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

    // Dapatkan senarai aduan                                                  
    $complaints = Complaintlist::leftJoin('status', 'complaints.status_id', '=', 'status.id')
                               ->leftJoin('categories', 'complaints.category_id', '=', 'categories.id')
                               ->leftJoin('tasks', 'tasks.complaint_id', '=', 'complaints.id')
                               ->leftJoin('sectors', 'sectors.sector_code', '=', 'complaints.sector_code')
                               ->leftJoin('departments', 'departments.department_code', '=', 'complaints.department_code')
                               ->leftJoin('users as b', 'b.id', '=', 'tasks.user_id')
                               ->where('complaints.active', 1)
                               ->where('complaints.category_id', '!=', 12);

    if($role == 4){
      $complaints->where('complaints.officer_id', $user_id)
                 ->where('complaints.status_id', 3);
    }elseif($role == 2){
      // AND ((`tasks`.`user_id` = 645 AND `complaints`.`status_id` = 2) OR (`complaints`.`status_id` = 6 OR `complaints`.`status_id` = 7)) 
      $complaints->where(function($query) use ($user_id){
                          $query->where(function($q) use ($user_id){
                                  $q->where('tasks.user_id', $user_id)
                                    ->Where('complaints.status_id', 2);
                                });
                          $query->orWhere(function($x){
                                  $x->where('complaints.status_id', 6)
                                    ->orWhere('complaints.status_id', 7);
                                });
                       });
    }elseif($role == 7 || $role == 8){
      $assignedSubcategories = DB::table('user_subcategories')
                                  ->where('user_id', $user_id)
                                  ->pluck('subcategory_id');
      $complaints->where('complaints.category_id', 10)
                 ->whereIn('complaints.subcategory_id', $assignedSubcategories)
                 ->whereIn('complaints.status_id', [2, 11]);
    }else{
      $complaints->where('tasks.user_id', $user_id)
                 ->where(function($query){
                          $query->where('complaints.status_id', 2)
                                ->orWhere('complaints.status_id', 9);
                       });
    }                          
                               
    if($search){ // Jika ada carian
      $complaints->where(function($query) use ($search){
        $query->where('complaints.application_no', 'LIKE', '%'.$search.'%')
               ->orWhere('complaints.name', 'LIKE', '%'.$search.'%')
               ->orWhere('complaints.email', 'LIKE', '%'.$search.'%');
      });
    }

    if($sec){ // Jika ada pilihan sektor
      $complaints->where('complaints.sector_code', $sec);
    }

    if($y){
      $complaints->whereYear('complaints.date_open', $y);
    }

    $complaints = $complaints->select('complaints.id', 'complaints.application_no', 'complaints.remarks', 'complaints.status_id', 'complaints.date_open', 'complaints.block', 'complaints.level', 'complaints.position_code', 'complaints.zone', 'departments.department_desc', 'sectors.sector_desc', 'status.status_desc', 'complaints.name AS pengadu', 'b.name AS pelaksana')
                             ->orderBy('complaints.status_id', 'ASC')
                             ->orderBy('complaints.date_open', 'ASC')
                             ->get();
    
    // Dapatkan senarai meeting                                                  
    $meeting = Complaintlist::leftJoin('tasks', 'tasks.complaint_id', '=', 'complaints.id')
                            ->leftJoin('users as b', 'b.id', '=', 'tasks.user_id')
                            ->leftJoin('sectors', 'sectors.sector_code', '=', 'complaints.sector_code')
                            ->leftJoin('departments', 'departments.department_code', '=', 'complaints.department_code')
                            ->where('complaints.active', 1)
                            ->where('complaints.category_id', 12);

    $meeting->where(function($query) use ($user_id){
      $query->where(function($q) use ($user_id){
        $q->where('tasks.user_id', $user_id)
          ->where('complaints.status_id', 2);
      });
    });
                                     
    if($search){ // Jika ada carian
      $meeting->where(function($query) use ($search){
        $query->where('complaints.application_no', 'LIKE', '%'.$search.'%')
               ->orWhere('complaints.name', 'LIKE', '%'.$search.'%')
               ->orWhere('complaints.email', 'LIKE', '%'.$search.'%');
      });
    }

    if($sec){ // Jika ada pilihan sektor
      $meeting->where('complaints.sector_code', $sec);
    }

    if($y){
      $meeting->whereYear('complaints.date_open', $y);
    }

    $meeting = $meeting->select('complaints.id', 'complaints.application_no', 'complaints.location', 'complaints.status_id', 'complaints.block', 'complaints.level', 'complaints.sector_code', 'complaints.zone', 'sectors.sector_desc', 'departments.department_desc', 'complaints.name AS pengadu', 'b.name AS pelaksana')
                       ->orderBy('complaints.status_id', 'ASC')
                       ->orderBy('complaints.date_open', 'ASC')
                       ->get();                         

    //dd($meeting);                      
    // Dapatkan senarai sektor                         
    $sectors = Sector::where('active', 1)
                      ->get();

    // Dapatkan senarai status                 
    $status = Status::where('active', 1)
                    ->get();

    return view('complaints.index_ontask', compact('complaints', 'meeting', 'sectors', 'status', 'y', 'sec', 'search'));
  }

  /** 
    * Dapatkan Ulasan
  **/
  public function getTimeline($id)
  {
    $log = AuditTrail::leftJoin('users as b', 'b.id', '=', 'audit_trail.staff')
                     ->leftJoin('users as c', 'c.id', '=', 'audit_trail.officer')
                     ->leftJoin('vendors', 'vendors.id', '=', 'audit_trail.vendor')
                     ->where('audit_trail.object_id', $id)
                     ->whereNotNull('audit_trail.application_no')
                     ->whereNotNull('audit_trail.status')
                     ->orderBy('audit_trail.created_at', 'ASC')
                     ->select('audit_trail.description', 'audit_trail.status', 'audit_trail.created_at', 'audit_trail.name as tindakan', 'b.name as pelaksana', 'c.name as pegawai', 'vendors.vendor_name')
                     ->get();

    // Dapatkan ulasan mengikut ID diatas
    $data = '<ul id="timeline" class="timeline">';
    
    foreach($log as $row){

      if($row->status == 1){
        $color = 'dark';
      }elseif($row->status == 2 || $row->status == 3 || $row->status == 9){
        $color = 'red';
      }elseif($row->status == 4 || $row->status == 5){
        $color = 'blue';
      }elseif($row->status == 6 || $row->status == 7){
        $color = 'purple';     
      }elseif($row->status == 8){
        $color = 'green';
      }

      $data .= '<li class="timeline-item">
                  <div class="timeline-wrapper border-'.$color.'">
                      <span class="timeline-date">'.date("d-m-Y", strtotime($row->created_at)).'<br>'.date("h:i a", strtotime($row->created_at)).'</span>
                      <div class="timeline-content panel">
                          
                          <span class="arrow"></span>
                          <p style="font-size: 11pt;"><strong>'.$row->tindakan.'</strong></p>';
                          
                          if($row->status == 1 && $row->description == 'Log Aduan Baru (BTM)'){
                            $remark = "Pegawai BTM log aduan ke sistem";
                          }elseif($row->status == 1 && $row->description == 'Pengadu Log Aduan Baru'){
                            $remark = "Pengadu log aduan ke sistem";  
                          }elseif($row->status == 2 && $row->description == 'Kemaskini Aduan'){
                            $remark = "Penyelaras Sistem menyerahkan tugas kepada :<br><i>".$row->pelaksana."</i>";
                          }elseif($row->status == 2 && $row->description == 'Ambil Tindakan'){
                            $remark = "Pegawai Teknikal mengambil alih tugas";
                          }elseif($row->status == 2 && $row->description == 'Pegawai Kemaskini Aduan'){
                            $remark = "Aduan diserah kepada Pegawai Teknikal :<br><i>".$row->pelaksana."</i>";
                          }elseif($row->status == 3){
                            $remark = "Pegawai Teknikal menghantar aduan ke peringkat pegawai :<br><i>".$row->pegawai."</i>";
                          }elseif($row->status == 4){
                            $remark = "Aduan selesai di peringkat pegawai";
                          }elseif($row->status == 5){
                            $remark = "Aduan selesai di peringkat pegawai teknikal";  
                          }elseif($row->status == 6){
                            $remark = "Pengadu mengesahkan aduan selesai";
                          }elseif($row->status == 7){
                            $remark = "Pengadu tidak mengesahkan aduan selesai";
                          }elseif($row->status == 8){
                            $remark = "Penyelaras Sistem menutup aduan";
                          }elseif($row->status == 9){
                            $remark = "Aduan diserahkan kepada Pembekal :<br><i>".$row->vendor_name."</i>";
                          }elseif($row->description == 'Pegawai Tukar Pegawai Teknikal'){
                            $remark = "Aduan diserahkan kepada Pegawai Teknikal :<br><i>".$row->pelaksana."</i>";       
                          }



      $data .=            '<div>'.$remark.'</div>
                      </div>
                  </div>
                </li>';
    }

    return $data;  
  }

  public function auditPDF(Request $request)
  {
    $id = $request->logID; 
    $today = date('d-m-Y H:i:s');

    $log = AuditTrail::leftJoin('users as b', 'b.id', '=', 'audit_trail.staff')
                     ->leftJoin('users as c', 'c.id', '=', 'audit_trail.officer')
                     ->leftJoin('vendors', 'vendors.id', '=', 'audit_trail.vendor')
                     ->where('audit_trail.object_id', $id)
                     ->whereNotNull('audit_trail.application_no')
                     ->whereNotNull('audit_trail.status')
                     ->orderBy('audit_trail.created_at', 'ASC')
                     ->select('audit_trail.application_no', 'audit_trail.description', 'audit_trail.status', 'audit_trail.created_at', 'audit_trail.name as tindakan', 'b.name as pelaksana', 'c.name as pegawai', 'vendors.vendor_name')
                     ->get();                         

    $data = [
      'logs' => $log,
      'today' => $today,
      'app_no' => $log[0]->application_no,
    ];

    //return view('complaints.log_pdf', $data);
    $pdf = PDF::loadView('complaints.audit_pdf', $data);

    // download PDF file with download method
    return $pdf->download('Jejak_Audit_'.$log[0]->application_no.'_'.$today.'.pdf');
  }

  public function logPDF($id) 
  {
    $id = EncID::get($id); // Decrypt ID yang dihantar
    $today = date('d-m-Y H:i:s');

    $complaint = Complaintlist::leftJoin('status as st', 'st.id', '=', 'complaints.status_id')
                              ->leftJoin('categories', 'categories.id', '=', 'complaints.category_id')
                              ->where('complaints.id', $id)
                              ->select('complaints.*', 'st.status_desc', 'categories.section_id')
                              ->first();

    // Dapatkan senarai ulasan
    $remarks = TaskRemarks::leftJoin('users as u', 'u.id', '=', 'task_remarks.user_id')
                          ->where('task_remarks.complaint_id', $id)
                          ->where('task_remarks.active', 1)
                          ->orderBy('created_at', 'ASC')
                          ->select('task_remarks.*', 'u.name')
                          ->get();                          

    $data = [
      'complaint' => $complaint,
      'today'     => $today, 
      'remarks'   => $remarks, 
    ];

    //return view('complaints.log_pdf', $data);
    $pdf = PDF::loadView('complaints.log_pdf', $data);

    // download PDF file with download method
    return $pdf->download('Maklumat_Aduan_'.$today.'.pdf');
  }

}
