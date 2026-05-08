<?php
/** 
  * Fail ini mengandungi segala fungsi yang diperlukan oleh sistem
  * By : MUHD SYARIZAN B. YAACOB
  * Date Created : Disember 2020
**/

namespace App\Helper;
use Illuminate\Support\Facades\Auth;
use App\Models\Complaintlist;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Sector;
use App\Models\Department;
//use App\Models\Jawatan;
use App\Models\Position;
use App\Models\Grade;
use App\Models\Detail;
use App\Models\Status;
use App\Models\User;
use App\Models\Role;
use App\Models\Task;
use App\Models\TaskRemarks;
use App\Models\Vendor;
use App\Models\Holiday;

use Session;

class Utilities
{
  static public function randomPassword() 
  {
    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
  }



  /** 
    * Fungsi mendapatkan No Aduan berdasarkan ID aduan yang diberi
  **/
  static public function getAppNo($id) 
  {    
    $comp = Complaintlist::where('id', $id)
                         ->select('application_no')
                         ->first();

    return $comp->application_no;
  }

  /** 
    * Fungsi mendapatkan No Aduan berdasarkan ID task yang diberi
  **/
  static public function getTaskAppNo($id) 
  {    
    $comp = TaskRemarks::leftJoin('complaints', 'complaints.id', '=', 'task_remarks.complaint_id')
                       ->where('task_remarks.id', $id)
                       ->select('complaints.application_no')
                       ->first();

    return $comp->application_no;
  }

  /** 
    * Fungsi mendapatkan keterangan kategori berdasarkan ID kategori yang diberi
  **/
  static public function getCategory($id) 
  {    
    $category = Category::where('id', $id)
              					->select('category_desc')
              					->first();

    return $category->category_desc;
  }

  /** 
    * Fungsi mendapatkan keterangan sektor berdasarkan ID sektor yang diberi
  **/
  static public function getSector($id) 
  {    
    $sector = Sector::where('sector_code', $id)
                    ->select('sector_desc')
                    ->first();

    if($sector){
      return $sector->sector_desc;
    }else{
      return '-';
    }
  }

  /** 
    * Fungsi mendapatkan keterangan bahagian berdasarkan ID bahagian yang diberi
  **/
  static public function getDepartment($id) 
  {    
    $department = Department::where('department_code', $id)
                            ->select('department_desc')
                            ->first();

    if($department){
      return $department->department_desc;
    }else{
      return '-';
    }                        
  }

  /** 
    * Fungsi mendapatkan keterangan sub kategori berdasarkan ID sub kategori yang diberi
  **/
  static public function getSubCategory($id) 
  {    
    $subcategory = SubCategory::where('id', $id)
                						  ->select('subcategory_desc')
                						  ->first();

    return $subcategory->subcategory_desc;
  }

  /** 
    * Fungsi mendapatkan keterangan perincian berdasarkan ID perincian yang diberi
  **/
  static public function getDetail($id) 
  {    
    $d = explode(',', $id);

    foreach($d as $r)
    {
      $det = Detail::where('id', $r)
                   ->select('detail_desc')
                   ->first();

      if($det){             
        $detail[] = $det->detail_desc;
      }
    }

    $details = implode(', ', $detail);

    return $details;
  }
  
  /** 
    * Fungsi mendapatkan keterangan status berdasarkan ID status yang diberi
  **/
  static public function getStatus($id) 
  {    
    $det = Status::where('id', $id)
                 ->select('status_desc')
                 ->first();

    return $det->status_desc;
  }

  /** 
    * Fungsi mendapatkan nama pegawai berdasarkan ID pengguna yang diberi
  **/
  static public function getStaffName($id) 
  {    
    $user = User::where('id', $id)
        				->select('name')
        				->first();

    return $user->name;
  }

  /** 
    * Fungsi mendapatkan nama pegawai berdasarkan ID pengguna yang diberi
  **/
  static public function getStaffPosition($id) 
  {    
    $user = User::leftJoin('positions', 'positions.id', '=', 'users.position_id')
                ->where('users.id', $id)
                ->select('positions.position_desc')
                ->first();

    return $user->position_desc;
  }

  static public function getGrade($id) 
  {    
    $gred = Grade::where('grade_code', $id)
                   ->select('grade_desc')
                   ->first();

    return $gred->gre_desc;
  }

  static public function getPosition($id) 
  {    
    $jaw = Position::where('position_code', $id)
                   ->select('position_desc')
                   ->first();

    return $jaw->jaw_desc;
  }

  /** 
    * Fungsi mendapatkan keterangan peranan berdasarkan ID peranan yang diberi
  **/
  static public function getRole($id) 
  {    
    $role = Role::where('id', $id)
        				->select('role_desc')
        				->first();

    return $role->role_desc;
  }

  /** 
    * Fungsi mendapatkan keterangan peranan berdasarkan ID peranan yang diberi
  **/
  static public function getVendor($id) 
  {    
    $v = Vendor::where('id', $id)
               ->select('vendor_name')
               ->first();

    return $v->vendor_name;
  }

  /** 
    * Fungsi mendapatkan no. telefon berdasarkan ID pengguna yang diberi
  **/
  static public function phonenumber($id) 
  {    
    $phone = User::where('id', $id)
        				 ->select('telephone')
        				 ->first();

  	$phone = str_replace(" ", "", str_replace("-", "", $phone->telephone));

    return $phone;
  }

  /** 
    * Fungsi mendapatkan jumlah task berdasarkan ID pelaksana yang diberi
  **/
  static public function countTask($user_id, $status) 
  {
    $task = Task::leftJoin('complaints', 'tasks.complaint_id', '=', 'complaints.id')
          			->where('tasks.active', 1)
          			->where('complaints.active', 1)
          			->where('complaints.status_id', $status)
          			->where('tasks.user_id', $user_id)
          			->count();

    return $task;
  }

  /** 
    * Fungsi mendapatkan jumlah task berdasarkan ID pelaksana yang diberi
  **/
  static public function totalTask($user_id) 
  {
    $task = Task::leftJoin('complaints', 'tasks.complaint_id', '=', 'complaints.id')
          			->where('tasks.active', 1)
          			->where('complaints.active', 1)
          			->where('tasks.user_id', $user_id)
          			->count();

    return $task;
  }

  /** 
    * Fungsi mendapatkan jumlah notifikasi berdasarkan ID yang log masuk
  **/
  static public function totalMeeting() 
  {
    $noti = Complaintlist::where('category_id', 12)
                         ->where('complaints.active', 1)
                         ->where('complaints.status_id', 1)
                         ->count();    

    return $noti;
  }

  /** 
    * Fungsi mendapatkan jumlah notifikasi berdasarkan ID yang log masuk
  **/
  static public function meetingTime($id) 
  {
    $t = Complaintlist::where('id', $id)->first();    

    $d1 = strtotime($t->tkh_mula);
    $d2 = strtotime($t->tkh_tamat);

    if($d2 > $d1){
      $time = date('d-m-Y', $d1).' - '.date('d-m-Y', $d2).'<br>'.date('h:i a', strtotime($t->ms_mula)).' - '.date('h:i a', strtotime($t->ms_tamat));
    }else{
      $time = date('d-m-Y', $d1).'<br>'.date('h:i a', strtotime($t->ms_mula)).' - '.date('h:i a', strtotime($t->ms_tamat));
    }

    return $time;
  }

  /** 
    * Fungsi mendapatkan jumlah notifikasi berdasarkan ID yang log masuk
  **/
  static public function totalNew() 
  {
    $section_id = Auth::user()->section_id; // Dapatkan seksyen pengguna yang log masuk

    $noti = Complaintlist::leftJoin('tasks', 'tasks.complaint_id', '=', 'complaints.id')
                         ->leftJoin('categories', 'categories.id', '=', 'complaints.category_id')
                         ->where('complaints.active', 1)
                         ->where('complaints.status_id', 1)
                         ->where('complaints.category_id', '!=', 12)
                         ->count();    


    return $noti;
  }

  /** 
    * Fungsi mendapatkan senarai notifikasi berdasarkan ID yang log masuk
  **/
  static public function New() 
  {
    $section_id = Auth::user()->section_id; // Dapatkan seksyen pengguna yang log masuk

    $comp = Complaintlist::leftJoin('tasks', 'tasks.complaint_id', '=', 'complaints.id')
                         ->leftJoin('categories', 'categories.id', '=', 'complaints.category_id')
                         ->where('complaints.active', 1)
                         ->where('complaints.status_id', 1)
                         ->select('complaints.id', 'complaints.application_no', 'complaints.remarks')
                         ->get();    

    return $comp;
  }

  /** 
    * Fungsi mendapatkan jumlah notifikasi berdasarkan ID yang log masuk
  **/
  static public function totalOnTask() 
  {
    $role = Auth::user()->role_id; // Dapatkan peranan pengguna yang log masuk
    $user_id = Auth::user()->id; // Dapatkan id pengguna yang log masuk
    $section_id = Auth::user()->section_id; // Dapatkan seksyen pengguna yang log masuk

    if($role == 3){ // Jika peranan pelaksana
      $noti = Complaintlist::leftJoin('tasks', 'tasks.complaint_id', '=', 'complaints.id')
                           ->where('complaints.active', 1)
                           ->where('tasks.user_id', $user_id)
                           //->where('complaints.category_id', '!=', 12)
                           ->where(function($query){
                              $query->where('complaints.status_id', 2)
                                    ->orWhere('complaints.status_id', 9);
                           })
                           ->count();
    }elseif($role == 4){ // jika peranan pegawai
      $noti = Complaintlist::where('complaints.active', 1)
                           ->where('complaints.status_id', 3)
                           ->where('complaints.officer_id', $user_id)
                           //->where('complaints.category_id', '!=', 12)
                           ->count();
    }elseif($role == 2){ // jika peranan penyelaras aduan
      $noti = Complaintlist::leftJoin('tasks', 'tasks.complaint_id', '=', 'complaints.id')
                           ->leftJoin('categories', 'categories.id', '=', 'complaints.category_id')
                           ->where('complaints.active', 1)
                           //->where('complaints.category_id', '!=', 12)
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
    }elseif($role == 7 || $role == 8){
      $subcats = \Illuminate\Support\Facades\DB::table('user_subcategories')->where('user_id', $user_id)->pluck('subcategory_id')->toArray();
      $noti = Complaintlist::where('active', 1)->whereIn('subcategory_id', $subcats);
      
      if($role == 7) {
           $noti = $noti->whereIn('status_id', [2, 11]);
      } else {
           $noti = $noti->whereIn('status_id', [2, 7, 9]);
      }
      $noti = $noti->count();
    }else{
      $noti = 0;                                     
    }

    return $noti;
  }

  /** 
    * Fungsi mendapatkan senarai notifikasi berdasarkan ID yang log masuk
  **/
  static public function Task() 
  {
    $role = Auth::user()->role_id; // Dapatkan peranan pengguna yang log masuk
    $user_id = Auth::user()->id; // Dapatkan id pengguna yang log masuk
    $section_id = Auth::user()->section_id; // Dapatkan seksyen pengguna yang log masuk

    if($role == 3){ // Jika peranan pelaksana
      $comp = Complaintlist::leftJoin('tasks', 'tasks.complaint_id', '=', 'complaints.id')
                           ->where('complaints.active', 1)
                           ->where('tasks.user_id', $user_id)
                           ->where('complaints.category_id', '!=', 12)
                           ->where(function($query){
                              $query->where('complaints.status_id', 2)
                                    ->orWhere('complaints.status_id', 9);
                           })
                           ->select('complaints.id', 'complaints.application_no', 'complaints.remarks')
                           ->get();
    }elseif($role == 4){ // jika peranan pegawai
      $comp = Complaintlist::where('complaints.active', 1)
                           ->where('complaints.status_id', 3)
                           ->where('complaints.officer_id', $user_id)
                           ->where('complaints.category_id', '!=', 12)
                           ->select('complaints.id', 'complaints.application_no', 'complaints.remarks')
                           ->get();
    }elseif($role == 2){ // jika peranan penyelaras aduan
      $comp = Complaintlist::leftJoin('tasks', 'tasks.complaint_id', '=', 'complaints.id')
                           ->leftJoin('categories', 'categories.id', '=', 'complaints.category_id')
                           ->where('complaints.active', 1)
                           ->where('complaints.category_id', '!=', 12)
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
                           ->select('complaints.id', 'complaints.application_no', 'complaints.remarks')
                           ->get();
    }elseif($role == 7 || $role == 8){
      $subcats = \Illuminate\Support\Facades\DB::table('user_subcategories')->where('user_id', $user_id)->pluck('subcategory_id')->toArray();
      $comp = Complaintlist::where('active', 1)->whereIn('subcategory_id', $subcats);
      
      if($role == 7) {
           $comp = $comp->whereIn('status_id', [2, 11]);
      } else {
           $comp = $comp->whereIn('status_id', [2, 7, 9]);
      }
      $comp = $comp->select('complaints.id', 'complaints.application_no', 'complaints.remarks')->get();
    }else{
      $comp = [];                          
    }

    return $comp;
  }

  static public function Mesyuarat() 
  {
    $role = Auth::user()->role_id; // Dapatkan peranan pengguna yang log masuk
    $user_id = Auth::user()->id; // Dapatkan id pengguna yang log masuk
    $section_id = Auth::user()->section_id; // Dapatkan seksyen pengguna yang log masuk

    if($role == 3){ // Jika peranan pelaksana
      $comp = Complaintlist::leftJoin('tasks', 'tasks.complaint_id', '=', 'complaints.id')
                           ->where('complaints.active', 1)
                           ->where('tasks.user_id', $user_id)
                           ->where('complaints.category_id', 12)
                           ->where(function($query){
                              $query->where('complaints.status_id', 2)
                                    ->orWhere('complaints.status_id', 9);
                           })
                           ->select('complaints.id', 'complaints.application_no', 'complaints.location')
                           ->orderBy('complaints.tkh_mula', 'DESC')
                           ->get();
    }elseif($role == 2){ // jika peranan penyelaras aduan
      $comp = Complaintlist::leftJoin('tasks', 'tasks.complaint_id', '=', 'complaints.id')
                           ->leftJoin('categories', 'categories.id', '=', 'complaints.category_id')
                           ->where('complaints.active', 1)
                           ->where('complaints.category_id', 12)
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
                           ->select('complaints.id', 'complaints.application_no', 'complaints.location')
                           ->orderBy('complaints.tkh_mula', 'DESC')
                           ->get();
    }else{
      $comp = [];                          
    }

    return $comp;
  }

  /** 
    * Fungsi mendapatkan jumlah bil hari dari hari aduan dihantar
  **/
  static public function task_day($id) 
  {
    $comp = Complaintlist::where('id', $id)
                         ->select('date_open', 'status_id')
                         ->first();

    date_default_timezone_set('Asia/Kuala_Lumpur'); // Tetap timezone ke Malaysia
    $from = strtotime(date('Y-m-d', strtotime($comp->date_open))); // tukar format tarikh
    $today = strtotime(date('Y-m-d'), time()); // dapatkan tarikh hari ini
    
    $days = Utilities::getWorkingDays($from, $today);
    
    if($comp->status_id < 4 || $comp->status_id == 9){
      if($days > 2 && $days < 5){ // jika jumlah lebih dari 3 hari dan kurang dari 5 hari
        $color = 'style="color: #eac459;"'; // tetap warna jingga
      }elseif($days > 4){ // jika jumlah hari lebih dari 4 hari
        $color = 'style="color: #ff6c60;"'; // tetap warna merah
      }else{ // jika selain diatas, set tiada tetapan warna
        $color = '';
      }
    }else{
      $color = '';
    }
    
    return $color;
  }

  //The function returns the no. of business days between two dates and it skips the holidays
  static public function getWorkingDays($startDate, $endDate){
    //The total number of days between the two dates. We compute the no. of seconds and divide it to 60*60*24
    //We add one to inlude both dates in the interval.
    $days = ($endDate - $startDate) / 86400 + 1;

    $no_full_weeks = floor($days / 7);
    $no_remaining_days = fmod($days, 7);

    //It will return 1 if it's Monday,.. ,7 for Sunday
    $the_first_day_of_week = date("N", $startDate);
    $the_last_day_of_week = date("N", $endDate);

    //---->The two can be equal in leap years when february has 29 days, the equal sign is added here
    //In the first case the whole interval is within a week, in the second case the interval falls in two weeks.
    if ($the_first_day_of_week <= $the_last_day_of_week) {
        if ($the_first_day_of_week <= 6 && 6 <= $the_last_day_of_week) $no_remaining_days--;
        if ($the_first_day_of_week <= 7 && 7 <= $the_last_day_of_week) $no_remaining_days--;
    }
    else {
        // (edit by Tokes to fix an edge case where the start day was a Sunday
        // and the end day was NOT a Saturday)

        // the day of the week for start is later than the day of the week for end
        if ($the_first_day_of_week == 7) {
            // if the start date is a Sunday, then we definitely subtract 1 day
            $no_remaining_days--;

            if ($the_last_day_of_week == 6) {
                // if the end date is a Saturday, then we subtract another day
                $no_remaining_days--;
            }
        }
        else {
            // the start date was a Saturday (or earlier), and the end date was (Mon..Fri)
            // so we skip an entire weekend and subtract 2 days
            $no_remaining_days -= 2;
        }
    }

    //The no. of business days is: (number of weeks between the two dates) * (5 working days) + the remainder
//---->february in none leap years gave a remainder of 0 but still calculated weekends between first and last day, this is one way to fix it
   $workingDays = $no_full_weeks * 5;
    if ($no_remaining_days > 0 )
    {
      $workingDays += $no_remaining_days;
    }

    $holidays = Holiday::where('active', 1)
                       ->where('year', date('Y'))
                       ->get();

    //We subtract the holidays
    foreach($holidays as $holiday){
        $time_stamp=strtotime($holiday->date);
        //If the holiday doesn't fall in weekend
        if ($startDate <= $time_stamp && $time_stamp <= $endDate && date("N",$time_stamp) != 6 && date("N",$time_stamp) != 7)
            $workingDays--;
    }

    return floor($workingDays);
  }

  /** 
    * Fungsi mendapatkan senarai Pelaksana berdasarkan ID mesyuarat
  **/
  static public function getPelaksana($id) 
  {
    $comp = Task::leftJoin('users', 'users.id', '=', 'tasks.user_id')
                ->where('tasks.complaint_id', $id)
                ->select('users.name')
                ->get();    

    $n = count($comp);
    
    if($n){
      if($n > 1){
        $name = $comp[0]->name;
        $name .= '<br>'.$comp[1]->name;
      }else{
        $name = $comp[0]->name;
      }
    }else{
      $name = '-';
    }
    

    return $name;
  }
}
