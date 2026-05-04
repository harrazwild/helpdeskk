<?php
/** 
  * Fail ini mengandungi segala yang berkaitan dengan Sub Menu Maklumat Aduan
  * By : MUHD SYARIZAN B. YAACOB
  * Date Created : Disember 2020
**/

namespace App\Http\Controllers;

use App\Helper\Audit;
use Illuminate\Http\Request;
use App\Helper\Utilities;
use App\Helper\EncID;
use App\Helper\Notify;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;
use App\Models\AuditTrail;
use DataTables;

class AuditController extends Controller
{
    /** 
    * Dapatkan Senarai Bahagian
  **/
  public function index(Request $request)
  {
    return view('audit.index');
  }

  public function getAudit(Request $request)
  {
    $search = $request->input('search.value');
    //$columns = $request->get('columns');
    $pageSize = ($request->length) ? $request->length : 10;

    $audit = AuditTrail::leftJoin('users as a', 'a.id', '=', 'audit_trail.user_id')
                       ->leftJoin('users as b', 'b.id', '=', 'audit_trail.staff')
                       ->leftJoin('users as c', 'c.id', '=', 'audit_trail.officer')
                       ->leftJoin('categories', 'categories.id', '=', 'audit_trail.category')
                       ->leftJoin('subcategories', 'subcategories.id', '=', 'audit_trail.subcategory')
                       ->where('object_id', '!=', null);

    $itemCounter = $audit->get();
    $count_total = $itemCounter->count();

    $count_filter = 0;
    if($search != ''){
      $audit->where(function($query) use ($search){
                      $query->where('audit_trail.application_no', 'LIKE', '%'.$search.'%')
                            ->orWhere('a.name', 'LIKE', '%'.$search.'%');
                    });
      $count_filter = $audit->count();
    }

    $audit->select('audit_trail.*', 'a.name as penyelaras', 'b.name as pegawai_teknikal', 'c.name as pegawai', 'categories.category_desc', 'subcategories.subcategory_desc');

    $start = ($request->start) ? $request->start : 0;
    $audit->skip($start)->take($pageSize);

    if($count_filter == 0){
      $count_filter = $count_total;
    }

    return DataTables()->of($audit)
                       ->with(['recordsTotal' => $count_total, 'recordsFiltered' => $count_filter])
                       ->addIndexColumn()
                       ->editColumn('noAduan', function($row){
                        return '<strong>#'.$row->application_no.'</strong>';
                       })
                       ->editColumn('tarikh', function($row){
                        return date('d-m-Y', strtotime($row->created_at));
                       })
                       ->editColumn('perincian', function($row){
                        $d = 'Pegawai Teknikal : '.$row->pegawai_teknikal.'<br>Pegawai : '.$row->pegawai.'<br>Kategori : '.$row->category_desc.'<br>Sub Kategori : '.$row->subcategory_desc.'<br>Ulasan : '.$row->remark;
                        return $d;
                       })
                       ->editColumn('st', function($row){
                        if($row->status == 1){
                          $status = 'Aduan Baru';
                        }elseif($row->status == 2){
                          $status = 'Dalam Tindakan';
                        }elseif($row->status == 3){
                          $status = 'Tindakan Pegawai';
                        }elseif($row->status == 4){
                          $status = 'Selesai Di Peringkat Pegawai';
                        }elseif($row->status == 5){
                          $status = 'Selesai Di Peringkat Pegawai Teknikal';
                        }elseif($row->status == 6){
                          $status = 'Disahkan Selesai';
                        }elseif($row->status == 7){
                          $status = 'Tidak Disahkan Selesai';
                        }elseif($row->status == 8){
                          $status = 'Tutup Aduan';                        
                        }else{
                          $status = '';
                        }
                        return $status;
                       })
                       ->rawColumns(['noAduan', 'tarikh', 'st', 'perincian'])
                       ->make(true);
  }

}
