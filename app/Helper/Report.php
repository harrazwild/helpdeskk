<?php
/** 
  * Fail ini mengandungi fungsi laporan yang diperlukan oleh sistem
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
use App\Models\Detail;
use App\Models\Task;
use App\Models\V_KPI;
use App\Helper\Utilities;

use Session;

class Report
{
	/** 
    * Fungsi mendapatkan keterangan bahagian berdasarkan ID bahagian yang diberi
  **/
  static public function getDepartment($id) 
  {    
    $department = Department::where('id', $id)
                            ->select('department_desc')
                            ->first();

    if($department){
      return htmlspecialchars($department->department_desc);
    }else{
      return '-';
    }                        
  }

	/** 
    * Fungsi mendapatkan sub kategori berdasarkan ID kategori yang diberi
  **/
  static public function getSubCategory($id) 
  {    
    $subcat = SubCategory::where('category_id', $id)
                         ->where('active', 1)
                         ->select('id', 'subcategory_desc')
                         ->get();

    return $subcat;
  }

  /** 
    * Fungsi mendapatkan sub kategori berdasarkan ID kategori yang diberi
  **/
  static public function getDetail($id) 
  {    
    $details = Detail::where('subcategory_id', $id)
                     ->where('active', 1)
                     ->select('id', 'detail_desc')
                     ->get();

    return $details;
  }

   /** 
    * Fungsi mendapatkan nombor roman berdasarkan nombor yang diberi
  **/
  static public function romannumber($number) 
  {
    $map = array('m' => 1000, 'cm' => 900, 'd' => 500, 'cd' => 400, 'c' => 100, 'xc' => 90, 'l' => 50, 'xl' => 40, 'x' => 10, 'ix' => 9, 'v' => 5, 'iv' => 4, 'i' => 1);
    $returnValue = '';
    while ($number > 0) {
      foreach ($map as $roman => $int) {
        if($number >= $int) {
          $number -= $int;
          $returnValue .= $roman;
          break;
        }
      }
    }
    return $returnValue;
	}

	/** 
    * Fungsi mendapatkan alphabet berdasarkan nombor yang diberi
  **/
	static public function toAlpha($data)
	{
    $alphabet =   array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z');
    $alpha_flip = array_flip($alphabet);
    $data = $data-1;
    if($data <= 25){
      return $alphabet[$data];
    }elseif($data > 25){
      $dividend = ($data + 1);
      $alpha = '';
      $modulo;
      while ($dividend > 0){
        $modulo = ($dividend - 1) % 26;
        $alpha = $alphabet[$modulo] . $alpha;
        $dividend = floor((($dividend - $modulo) / 26));
      } 
      return $alpha;
    }
	}

	/** 
    * Fungsi mendapatkan jumlah aduan mengikut sub kategori
  **/
	static public function totalSubCat($cat, $subcat, $s, $e)
	{
		$x = Complaintlist::where('active', 1)
		                  ->where('category_id', $cat);

		if($subcat == ''){
			$x->whereNull('subcategory_id');
		}else{
			$x->where('subcategory_id', $subcat);
		}

		if($s != ''){
			$x->whereBetween('date_open', [$s.' 00:00:00', $e.' 23:59:59']);
		}                        

		$x = $x->count();

    return $x;
	}
	
	/** 
    * Fungsi mendapatkan jumlah aduan mengikut sub kategori
  **/
	static public function totalSubC($cat, $subcat, $s, $e)
	{
		if($subcat == ''){ // Jika tiada sub kategori
			
			$complaint = Complaintlist::where('active', 1)
												->where('category_id', $cat)
												->whereNull('subcategory_id')
												->whereBetween('date_open', [$s.' 00:00:00', $e.' 23:59:59'])
												->count();
			return $complaint;
												
		}else{ // Jika ada sub kategori
			$complaint = Complaintlist::where('active', 1)
												->where('category_id', $cat)	
												->where('subcategory_id', $subcat)
												->whereBetween('date_open', [$s.' 00:00:00', $e.' 23:59:59'])
												->select('detail')
					 							->get();

			$n = $complaint->count();

		  if($n){
		  	$t = 0;
		  	foreach($complaint as $row){
		  		if($row->detail != ''){
			  		$d = explode(',', $row->detail);
						$d = count($d);
						$t = $t+$d;
					}else{
						$t = $t+1;
					}
		  	}
				return $t;
			}else{
				return $n;
			}			 							
		
		}
	}

	/** 
      * Fungsi mendapatkan jumlah aduan mengikut perincian
    **/
	static public function totalDetail($cat, $subcat, $det, $s, $e)
	{
		$y = Complaintlist::where('active', 1)
		                  ->where('category_id', $cat)
		                  ->where('subcategory_id', $subcat);

		if($det == ''){
			$y->whereNull('detail');
		}else{
			$y->whereRaw('FIND_IN_SET('.$det.', detail)');
		}

		if($s != ''){
			$y->whereBetween('date_open', [$s.' 00:00:00', $e.' 23:59:59']);
		}                        

		$y = $y->count();

    return $y;
	}

	/** 
    * Fungsi mendapatkan jumlah task berdasarkan ID pelaksana yang diberi
  **/
  static public function task($user_id, $sDate, $eDate, $status_id) 
  {

    $task = Task::leftJoin('complaints', 'tasks.complaint_id', '=', 'complaints.id')
          			->where('tasks.active', 1)
          			->where('complaints.active', 1)
                ->whereBetween('complaints.date_open', [$sDate, $eDate])
          			->where('tasks.user_id', $user_id);

    if($status_id == 1){
    	$task->where(function($query){
        $query->where('complaints.status_id', 2)
              ->orWhere('complaints.status_id', 3)
              ->orWhere('complaints.status_id', 9);
     	});
    }elseif($status_id == 2){
    	$task->where(function($query){
        $query->where('complaints.status_id', 4)
              ->orWhere('complaints.status_id', 5);
     	});
    }elseif($status_id == 3){
    	$task->where(function($query){
        $query->where('complaints.status_id', 6)
              ->orWhere('complaints.status_id', 7);
     	});
    }elseif($status_id == 4){ 	
    	$task->where('complaints.status_id', 8);
    }      			
    $task = $task->count();

    return $task;
  }

  /** 
    * Fungsi mendapatkan jumlah task berdasarkan ID pelaksana yang diberi
  **/
  static public function total_task($user_id, $sDate, $eDate) 
  {
    $task = Task::leftJoin('complaints', 'tasks.complaint_id', '=', 'complaints.id')
          			->where('tasks.active', 1)
          			->where('complaints.active', 1)
                ->whereBetween('complaints.date_open', [$sDate, $eDate])
          			->where('tasks.user_id', $user_id)
          			->count();

    return $task;
  }

  /** 
    * Fungsi mendapatkan jumlah bil hari dari hari aduan dihantar
  **/
  static public function threedays($id, $s_date, $e_date) 
  {
    $complaints = V_KPI::where('id_pelaksana', $id)
                       ->whereBetween('date_open', [$s_date.' 00:00:00', $e_date.' 23:59:59'])
                       ->where('tempoh_selesai', '>', 2)
                       ->where('tempoh_selesai', '<', 5)
                       ->count();

    return $complaints;
  }

  /** 
    * Fungsi mendapatkan jumlah bil hari dari hari aduan dihantar
  **/
  static public function fivedays($id, $s_date, $e_date) 
  {
    $complaints = V_KPI::where('id_pelaksana', $id)
                       ->whereBetween('date_open', [$s_date.' 00:00:00', $e_date.' 23:59:59'])
                       ->where('tempoh_selesai', '>=', 5)
                       ->count();

    return $complaints;
  }

}