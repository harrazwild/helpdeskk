<?php

/** 
  * Fail ini mengandungi segala yang berkaitan dengan Sub Menu Arkib Aduan Lama
  * By : MUHD SYARIZAN B. YAACOB
  * Date Created : April 2021
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
use App\Models\Archive;
// use App\Models\Task;
// use App\Models\TaskRemarks;
// use App\Models\User;
// use App\Models\Category;
// use App\Models\SubCategory;
// use App\Models\Detail;
// use App\Models\Sector;
// use App\Models\Status;
use DataTables;

class ArchiveController extends Controller
{
	/** 
    * Dapatkan Senarai Arkib
  **/
  public function index(Request $request)
  {
    $search = $request->input('search');
    $u_task = $request->input('u_task');
    $st = $request->input('status');
    $y = $request->input('tahun');
    
    $staffs = Archive::where(function($query){
                        $query->where('helpdesk2', '!=', '')
                              ->where('helpdesk2', '!=', 'Sila Pilih');
                      })
                      ->distinct()
                      ->get(['helpdesk2']);
                      
    return view('archive.index', compact('search', 'u_task', 'y', 'st', 'staffs'));
  }

  public function getArchive(Request $request)
  {
    $model = \App\Models\Archive::query();
    $search = $request->get('search');
    $u_task = $request->get('u_task');
    $status = $request->get('status');
    $tahun = $request->get('tahun');

    return DataTables::eloquent($model)
                       //->with(['recordsTotal' => $count_total, 'recordsFiltered' => $count_filter])
                       ->addIndexColumn()
                       ->filter(function ($query) use ($search, $u_task, $status, $tahun) {
                          if ($search) {
                            $query->where(function($q) use ($search){
                              $q->where('norujukan', 'like', "%" . $search . "%")
                                ->orWhere('fullname', 'like', "%" . $search . "%")
                                ->orWhere('KeteranganMasalah', 'like', "%" . $search . "%");
                            });
                          }

                          if ($u_task) {
                            $query->where('helpdesk2', 'like', "%" . $u_task . "%");
                          }
                        
                          if ($tahun) {
                            $query->where('Tarikh', 'like', "%" . $tahun . "%");
                          }
    
                          if ($status) {
                            $query->where('Status', 'like', "%" . $status . "%");
                          }
                       })
                       ->addColumn('action', function($row){
                        $btn = '<a href="'.route('show_archive', Crypt::encrypt($row->id)).'" style="text-decoration: none;"><i class="icon-eye"></i></a>';
                        return $btn;
                       })
                       ->editColumn('noAduan', function($row){
                        return '<strong>#'.$row->norujukan.'</strong>';
                       })
                       ->editColumn('lokasi', function($row){
                        return $row->sektor.'<br>'.$row->bahagian;
                       })
                       ->editColumn('detail_user', function($row){
                        return $row->fullname.'<br>'.$row->jawatan;
                       })
                       ->rawColumns(['noAduan', 'lokasi', 'detail_user', 'action'])
                       ->make(true);
  }
  
  /** 
    * Papar Aduan Arkib
  **/
  public function show($id)
  {
  	$id = EncID::get($id); // Decrypt ID yang dihantar

    // Dapatkan detail aduan
    $archive = Archive::where('id', $id)
                 			->first();               

    return view('archive.show', compact('archive'));
  }  
}
