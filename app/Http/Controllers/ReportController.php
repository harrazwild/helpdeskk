<?php
/** 
  * Fail ini mengandungi segala yang berkaitan dengan Sub Menu Maklumat Aduan
  * By : MUHD SYARIZAN B. YAACOB
  * Date Created : Disember 2020
**/

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Helper\Report;
use App\Models\Complaintlist;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Detail;
use App\Models\Status;
use App\Models\User;
use App\Models\TaskRemarks;
use App\Models\V_KPI;
use App\Exports\StaffDetailExport;
use App\Exports\StaffStatExport;
use App\Exports\StaffKpiExport;
use App\Exports\CategoryExport;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class ReportController extends Controller
{
  public function category_report(Request $request)
  {        
    $cat = $request->category;
    $y = date('Y');

    if($request->daterange){
      $date = $request->daterange;
      $date = explode(" - ", $date);
      $s_date = date('Y-m-d', strtotime($date[0]));
      $e_date = date('Y-m-d', strtotime($date[1]));
    }else{
      $s_date = $y.'-01-01';
      $e_date = $y.'-12-31';
    }

    $sDate = date('d-m-Y', strtotime($s_date));
    $eDate = date('d-m-Y', strtotime($e_date));

    $category = Category::where('active', 1)
                        ->where('section_id', Auth::user()->section_id)
                        ->get();

    $categories = Category::where('active', 1);
                          
    if($cat){
      $categories->where('id', $cat);
    }else{
      $categories->where('section_id', Auth::user()->section_id);
    }                      

    $categories = $categories->get();

    return view('reports.category2', compact('category', 'cat', 'categories', 's_date', 'e_date', 'sDate', 'eDate'));
  }

  public function staff_detail_report(Request $request)
  {
    $y = date('Y');
    $sc = $request->section;
    $s = $request->staff;
    $st = $request->status;
    $d = $request->kpi;

    if($request->daterange){
      $date = $request->daterange;
      $date = explode(" - ", $date);
      $s_date = date('Y-m-d', strtotime($date[0]));
      $e_date = date('Y-m-d', strtotime($date[1]));
    }else{
      $s_date = $y.'-01-01';
      $e_date = $y.'-12-31';
    }

    $sDate = date('d-m-Y', strtotime($s_date));
    $eDate = date('d-m-Y', strtotime($e_date));

    $staff = User::where('active', 1)
                 ->where(function($query){
                    $query->where('role_id', '!=', 4)
                          ->where('role_id', '!=', 6);
                 })
                 ->get();

    $status = Status::where('active', 1)
                    ->get();             
            
    $complaints = V_KPI::where('id_pelaksana', $s)
                       ->whereBetween('date_open', [$s_date.' 00:00:00', $e_date.' 23:59:59']);

    if($st){
      $complaints->where('status_id', $st);
    }

    if($d == 2 || $d == 3){
      if($d == 2){
        $complaints->where('tempoh_ditutup', '>', 2)
                   ->where('tempoh_ditutup', '<', 5);
      }elseif($d == 3){
        $complaints->where('tempoh_ditutup', '>=', 5);
      }
    }

    $complaints = $complaints->get();
    //dd($complaints);

    return view('reports.staff_detail', compact('complaints', 'sc', 'staff', 's', 'status', 'st', 'd', 'sDate', 'eDate'));
  }

  public function staff_stat_report(Request $request)
  {
    $y = date('Y');
    $s = $request->staff;
    $sc = $request->section;

    if($request->daterange){
      $date = $request->daterange;
      $date = explode(" - ", $date);
      $s_date = date('Y-m-d', strtotime($date[0]));
      $e_date = date('Y-m-d', strtotime($date[1]));
    }else{
      $s_date = $y.'-01-01';
      $e_date = $y.'-12-31';
    }

    $sDate = date('d-m-Y', strtotime($s_date));
    $eDate = date('d-m-Y', strtotime($e_date));

    $staff = User::where('active', 1)
                 ->where(function($query){
                    $query->where('role_id', '!=', 4)
                          ->where('role_id', '!=', 6);
                 })
                 ->get();

    $p = User::leftJoin('positions', 'users.jaw_code', '=', 'positions.position_code')
             ->leftJoin('grade', 'users.gred_code', '=', 'grade.grade_code')
             ->where('users.active', 1)
             ->where(function($query){
                $query->where('users.role_id', '!=', 4)
                      ->where('users.role_id', '!=', 6);
             })
             ->orderBy('grade.grade_order', 'ASC')
             ->orderBy('users.name', 'ASC');

    if($s){
      $p = $p->where('users.id', $s);
    }

    if($sc){
      $p = $p->where('users.section_id', $sc);
    } 

      $p = $p->select('users.id', 'users.name', 'positions.position_desc')
             ->get();

    return view('reports.staff_stat', compact('staff', 's', 'sc', 'p', 'sDate', 'eDate', 's_date', 'e_date'));
  }

  public function staff_kpi_report(Request $request)
  {
    $y = date('Y');
    $s = $request->staff;
    $sc = $request->section;

    if($request->daterange){
      $date = $request->daterange;
      $date = explode(" - ", $date);
      $s_date = date('Y-m-d', strtotime($date[0]));
      $e_date = date('Y-m-d', strtotime($date[1]));
    }else{
      $s_date = $y.'-01-01';
      $e_date = $y.'-12-31';
    }

    $sDate = date('d-m-Y', strtotime($s_date));
    $eDate = date('d-m-Y', strtotime($e_date));

    $staff = User::where('active', 1)
                 ->where(function($query){
                    $query->where('role_id', '!=', 4)
                          ->where('role_id', '!=', 6);
                 })
                 ->get();

    $p = User::leftJoin('positions', 'users.jaw_code', '=', 'positions.position_code')
             ->leftJoin('grade', 'users.gred_code', '=', 'grade.grade_code')
             ->where('users.active', 1)
             ->where(function($query){
                $query->where('users.role_id', '!=', 4)
                      ->where('users.role_id', '!=', 6);
             })
             ->orderBy('grade.grade_order', 'ASC')
             ->orderBy('users.name', 'ASC');

    if($s){
      $p = $p->where('users.id', $s);
    }

    if($sc){
      $p = $p->where('users.section_id', $sc);
    } 

      $p = $p->select('users.id', 'users.name', 'positions.position_desc')
             ->get();

    return view('reports.staff_kpi', compact('staff', 's', 'sc', 'p', 'sDate', 'eDate', 's_date', 'e_date'));
  }

  public function categoryPDF($sDate, $eDate, $cat) 
  {
    $sDate = date('Y-m-d', strtotime($sDate));
    $eDate = date('Y-m-d', strtotime($eDate));

    $categories = Category::where('active', 1);
                          
    if($cat){
      $categories->where('id', $cat);
    }else{
      $categories->where('section_id', Auth::user()->section_id);
    }                      

    $categories = $categories->get();
    
    $today = date('d-m-Y H:i:s');

    $data = [
      'categories'     => $categories,
      'eDate' => $eDate,
      'sDate'  => $sDate,
      'today'  => $today,
    ];

    //return view('reports.category_pdf', $data);
    $pdf = PDF::loadView('reports.category_pdf', $data);

    // download PDF file with download method
    return $pdf->download('Laporan_Mengikut_Kategori_Aduan_'.$today.'.pdf');
  }

  public function staffStatPDF($sDate, $eDate, $staff, $section) 
  {
    $sDate = date('Y-m-d', strtotime($sDate));
    $eDate = date('Y-m-d', strtotime($eDate));

    $p = User::leftJoin('positions', 'users.jaw_code', '=', 'positions.position_code')
             ->leftJoin('grade', 'users.gred_code', '=', 'grade.grade_code')
             ->where('users.active', 1)
             ->where(function($query){
                $query->where('users.role_id', '!=', 4)
                      ->where('users.role_id', '!=', 6);
             })
             ->orderBy('grade.grade_order', 'ASC')
             ->orderBy('users.name', 'ASC');

    if($staff != 0){
      $p = $p->where('users.id', $staff);
    }         
      
    if($section != 0){
      $p = $p->where('users.section_id', $section);
    }

    $p = $p->select('users.id', 'users.name', 'positions.position_desc')
           ->get();

    $today = date('d-m-Y H:i:s');

    $data = [
      'p'     => $p,
      'eDate' => $eDate,
      'sDate'  => $sDate,
      'today'  => $today,
    ];

    //return view('reports.staff_stat_pdf', $data);
    $pdf = PDF::loadView('reports.staff_stat_pdf', $data);
    $pdf->setPaper('L', 'landscape');

    // download PDF file with download method
    return $pdf->download('Laporan_Statistik_Tindakan_Pengawai_Teknikal_'.$today.'.pdf');
  }

  public function staffDetailPDF($sDate, $eDate, $staff, $status, $kpi) 
  {
    $sDate = date('Y-m-d', strtotime($sDate));
    $eDate = date('Y-m-d', strtotime($eDate));
             
    $complaints = V_KPI::where('id_pelaksana', $staff)
                       ->whereBetween('date_open', [$sDate.' 00:00:00', $eDate.' 23:59:59']);

    if($status){
      $complaints->where('status_id', $status);
    }

    if($kpi == 2 || $kpi == 3){
      if($kpi == 2){
        $complaints->where('tempoh_ditutup', '>', 2)
                   ->where('tempoh_ditutup', '<', 5);
      }elseif($kpi == 3){
        $complaints->where('tempoh_ditutup', '>=', 5);
      }
    }

    $complaints = $complaints->get();

    $today = date('d-m-Y H:i:s');

    $data = [
      'complaints'     => $complaints,
      'eDate' => $eDate,
      'sDate'  => $sDate,
      'today'  => $today,
      's' => $staff
    ];

    //return view('reports.staff_detail_pdf', $data);
    $pdf = PDF::loadView('reports.staff_detail_pdf', $data);
    $pdf->setPaper('L', 'landscape');

    // download PDF file with download method
    return $pdf->download('Laporan_Perincian_Pegawai_Teknikal_'.$today.'.pdf');
  }
  
  public function staffKpiPDF($sDate, $eDate, $staff)
  {
    $sDate = date('Y-m-d', strtotime($sDate));
    $eDate = date('Y-m-d', strtotime($eDate));

    $p = User::leftJoin('positions', 'users.jaw_code', '=', 'positions.position_code')
             ->leftJoin('grade', 'users.gred_code', '=', 'grade.grade_code')
             ->where('users.active', 1)
             ->where(function($query){
                $query->where('users.role_id', '!=', 4)
                      ->where('users.role_id', '!=', 6);
             })
             ->orderBy('grade.grade_order', 'ASC')
             ->orderBy('users.name', 'ASC');

    if($staff){
      $p = $p->where('users.id', $staff);
    }

    $p = $p->select('users.id', 'users.name', 'positions.position_desc')
             ->get();

    $today = date('d-m-Y H:i:s');

    $data = [
      'p'     => $p,
      'eDate' => $eDate,
      'sDate'  => $sDate,
      'today'  => $today
    ];

    //return view('reports.staff_detail_pdf', $data);
    $pdf = PDF::loadView('reports.staff_kpi_pdf', $data);
    $pdf->setPaper('L', 'landscape');

    // download PDF file with download method
    return $pdf->download('Laporan_Perincian_KPI_Pegawai_Teknikal_'.$today.'.pdf');
  }

  public function staffDetailExcel($sDate, $eDate, $staff, $status, $kpi) 
  {
    $sDate = date('Y-m-d', strtotime($sDate));
    $eDate = date('Y-m-d', strtotime($eDate));
             
    return Excel::download(new StaffDetailExport($sDate, $eDate, $staff, $status, $kpi), 'Laporan_Perincian_Pegawai_Teknikal.xlsx');
  }

  public function staffStatExcel($sDate, $eDate, $staff, $section) 
  {
    $sDate = date('Y-m-d', strtotime($sDate));
    $eDate = date('Y-m-d', strtotime($eDate));
             
    return Excel::download(new StaffStatExport($sDate, $eDate, $staff, $section), 'Laporan_Statistik_Tindakan_Pegawai_Teknikal.xlsx');
  }

  public function staffKpiExcel($sDate, $eDate, $staff) 
  {
    $sDate = date('Y-m-d', strtotime($sDate));
    $eDate = date('Y-m-d', strtotime($eDate));
             
    return Excel::download(new StaffKpiExport($sDate, $eDate, $staff), 'Laporan_Statistik_KPI_Tindakan_Pegawai_Teknikal.xlsx');
  }

  public function categoryExcel($sDate, $eDate, $cat) 
  {
    $sDate = date('Y-m-d', strtotime($sDate));
    $eDate = date('Y-m-d', strtotime($eDate));
             
    return Excel::download(new CategoryExport($sDate, $eDate, $cat), 'Laporan_Mengikut_Kategori_Aduan.xlsx');
  }

  public function get_Staff($id)
  {
    // Dapatkan senarai pelaksana mengikut ID kategori diatas
    $staff = User::where('active', 1)
                 ->where('section_id', $id)
                 ->where(function($query){
                    $query->where('role_id', '!=', 4)
                          ->where('role_id', '!=', 6);
                 })
                 ->select('id', 'name')
                 ->get();

    return response()->json($staff); // Hantar data dalam bentuk JSON
  }

  /** 
    * Dapatkan Ulasan
  **/
  public function getUlasan($id)
  {
    $remarks = TaskRemarks::leftJoin('users as u', 'u.id', '=', 'task_remarks.user_id')
                          ->where('task_remarks.complaint_id', $id)
                          ->where('task_remarks.active', 1)
                          ->orderBy('created_at', 'ASC')
                          ->select('task_remarks.*', 'u.name')
                          ->get();

    // Dapatkan ulasan mengikut ID diatas

    $data = '<ul class="chats cool-chat">';
    
    $bil = 1;

    foreach($remarks as $row){

      if($bil % 2 == 0){
        $set = "out"; 
      }
      else{
        $set = "in";
      }

      $data .= '<li class="'.$set.'">
                  <img src="img/avatar.png" alt="" class="avatar">
                  <div class="message">
                      <span class="arrow"></span>
                      <a class="name" href="#">'.$row->name.'</a>
                      <span class="datetime">'.date("d-m-Y H:i s", strtotime($row->created_at)).'</span>
                      <span class="body" style="white-space: pre-wrap;">'.$row->remarks.'</span>
                  </div>
              </li>';

      $bil = $bil + 1;
    }          
              

    $data .= '</ul>';

    return $data;  
  }

  public function getThreeDays(Request $request)
  {
    $data = explode('_', $request->data);
    $id = $data[0];
    $sDate = $data[1];
    $eDate = $data[2];

    $r = V_KPI::where('id_pelaksana', $id)
              ->whereBetween('date_open', [$sDate.' 00:00:00', $eDate.' 23:59:59'])
              ->where('tempoh_selesai', '>', 2)
              ->where('tempoh_selesai', '<', 5)
              ->orderBy('status_id', 'ASC')
              ->get();

   return Response($r);
  }

  public function getFiveDays(Request $request)
  {
    $data = explode('_', $request->data);
    $id = $data[0];
    $sDate = $data[1];
    $eDate = $data[2];

    $r = V_KPI::where('id_pelaksana', $id)
              ->whereBetween('date_open', [$sDate.' 00:00:00', $eDate.' 23:59:59'])
              ->where('tempoh_selesai', '>=', 5)
              ->orderBy('status_id', 'ASC')
              ->get();

   return Response($r);
  }

}