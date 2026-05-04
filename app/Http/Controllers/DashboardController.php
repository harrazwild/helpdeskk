<?php
/** 
  * Fail ini mengandungi segala yang berkaitan dengan Sub Menu Maklumat Aduan
  * By : MUHD SYARIZAN B. YAACOB
  * Date Created : Disember 2020
**/

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helper\EncID;
use App\Helper\Notify;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;
use App\Models\Complaintlist;

class DashboardController extends Controller
{
    /** 
      * Paparan Dashboard
    **/
    public function index()
    {
    	return view('dashboard');
    }

    public function getDataWidget(Request $request){
        $section = Auth::user()->section_id; // Dapatkan seksyen ID pengguna yang log masuk 

        if(isset($request->year)){
            $year = $request->year;
        }else{
            $year = date('Y');
        }
        
        // Statistik jumlah aduan keseluruhan                   
        $totalAll = Complaintlist::where('complaints.active', 1)
                                 ->whereYear('complaints.date_open', $year)
                                 ->where('complaints.category_id', '!=', 12)
                                 ->select(DB::raw('COUNT(complaints.id) as total'),
                                      DB::raw('SUM( CASE WHEN complaints.status_id = 1 THEN 1 ELSE 0 END ) as new'),
                                      DB::raw('SUM( CASE WHEN (complaints.status_id = 2 OR complaints.status_id = 3 OR complaints.status_id = 9) THEN 1 ELSE 0 END ) as dt'),
                                      DB::raw('SUM( CASE WHEN (complaints.status_id = 4 OR complaints.status_id = 5) THEN 1 ELSE 0 END ) as done'),
                                      DB::raw('SUM( CASE WHEN (complaints.status_id = 6 OR complaints.status_id = 7) THEN 1 ELSE 0 END ) as verify'),
                                      DB::raw('SUM( CASE WHEN complaints.status_id = 8 THEN 1 ELSE 0 END ) as close')
                                      )
                                 ->first();
        
        // Umpuk nilai 0 jika tiada nilai hasil dari query diatas
        $a_new = !empty($totalAll->new) ? $totalAll->new : "0";                    
        $a_dt = !empty($totalAll->dt) ? $totalAll->dt : "0";                     
        $a_done = !empty($totalAll->done) ? $totalAll->done : "0";                      
        $a_verify = !empty($totalAll->verify) ? $totalAll->verify : "0";                      
        $a_close = !empty($totalAll->close) ? $totalAll->close : "0";                      
        $a_total = !empty($totalAll->total) ? $totalAll->total : "0";                      

        $totalAll = $obj = (object) ["new" => $a_new, "dt" => $a_dt, "done" => $a_done, "verify" => $a_verify, "close" => $a_close, "total" => $a_total]; // Umpukan nilai array object baru hasil dari atas

        // Statistik jumlah aduan mengikut bulan
        $mth = Complaintlist::where('complaints.active', 1)
                            ->whereYear('complaints.date_open', $year)
                            ->where('complaints.category_id', '!=', 12)
                            ->select(DB::raw('SUM( CASE WHEN MONTH ( complaints.date_open ) = 1 THEN 1 ELSE 0 END ) as jan'),
                              DB::raw('SUM( CASE WHEN MONTH ( complaints.date_open ) = 2 THEN 1 ELSE 0 END ) as feb'),
                              DB::raw('SUM( CASE WHEN MONTH ( complaints.date_open ) = 3 THEN 1 ELSE 0 END ) as mac'),
                              DB::raw('SUM( CASE WHEN MONTH ( complaints.date_open ) = 4 THEN 1 ELSE 0 END ) as apr'),
                              DB::raw('SUM( CASE WHEN MONTH ( complaints.date_open ) = 5 THEN 1 ELSE 0 END ) as mei'),
                              DB::raw('SUM( CASE WHEN MONTH ( complaints.date_open ) = 6 THEN 1 ELSE 0 END ) as jun'),
                              DB::raw('SUM( CASE WHEN MONTH ( complaints.date_open ) = 7 THEN 1 ELSE 0 END ) as jul'),
                              DB::raw('SUM( CASE WHEN MONTH ( complaints.date_open ) = 8 THEN 1 ELSE 0 END ) as ogos'),
                              DB::raw('SUM( CASE WHEN MONTH ( complaints.date_open ) = 9 THEN 1 ELSE 0 END ) as sept'),
                              DB::raw('SUM( CASE WHEN MONTH ( complaints.date_open ) = 10 THEN 1 ELSE 0 END ) as okt'),
                              DB::raw('SUM( CASE WHEN MONTH ( complaints.date_open ) = 11 THEN 1 ELSE 0 END ) as nov'),
                              DB::raw('SUM( CASE WHEN MONTH ( complaints.date_open ) = 12 THEN 1 ELSE 0 END ) as dis')
                            )
                            ->first();

        // Statistik jumlah aduan mengikut bulan
        $done = Complaintlist::where('complaints.active', 1)
                             ->where('complaints.status_id', 8)
                             ->whereYear('complaints.date_close', $year)
                             ->where('complaints.category_id', '!=', 12)
                             ->select(DB::raw('SUM( CASE WHEN MONTH ( complaints.date_close ) = 1 THEN 1 ELSE 0 END ) as jan'),
                                DB::raw('SUM( CASE WHEN MONTH ( complaints.date_close ) = 2 THEN 1 ELSE 0 END ) as feb'),
                                DB::raw('SUM( CASE WHEN MONTH ( complaints.date_close ) = 3 THEN 1 ELSE 0 END ) as mac'),
                                DB::raw('SUM( CASE WHEN MONTH ( complaints.date_close ) = 4 THEN 1 ELSE 0 END ) as apr'),
                                DB::raw('SUM( CASE WHEN MONTH ( complaints.date_close ) = 5 THEN 1 ELSE 0 END ) as mei'),
                                DB::raw('SUM( CASE WHEN MONTH ( complaints.date_close ) = 6 THEN 1 ELSE 0 END ) as jun'),
                                DB::raw('SUM( CASE WHEN MONTH ( complaints.date_close ) = 7 THEN 1 ELSE 0 END ) as jul'),
                                DB::raw('SUM( CASE WHEN MONTH ( complaints.date_close ) = 8 THEN 1 ELSE 0 END ) as ogos'),
                                DB::raw('SUM( CASE WHEN MONTH ( complaints.date_close ) = 9 THEN 1 ELSE 0 END ) as sept'),
                                DB::raw('SUM( CASE WHEN MONTH ( complaints.date_close ) = 10 THEN 1 ELSE 0 END ) as okt'),
                                DB::raw('SUM( CASE WHEN MONTH ( complaints.date_close ) = 11 THEN 1 ELSE 0 END ) as nov'),
                                DB::raw('SUM( CASE WHEN MONTH ( complaints.date_close ) = 12 THEN 1 ELSE 0 END ) as dis')
                             )
                             ->first();                    
                            
        $sect = Complaintlist::where('complaints.active', 1)
                             ->whereYear('complaints.date_open', $year)
                             ->where('complaints.category_id', '!=', 12)
                             ->select(DB::raw('SUM( CASE WHEN complaints.sector_code = "1.0" THEN 1 ELSE 0 END ) as pkan'),
                              DB::raw('SUM( CASE WHEN complaints.sector_code = "2.0" THEN 1 ELSE 0 END ) as sp'),
                              DB::raw('SUM( CASE WHEN complaints.sector_code = "3.1" THEN 1 ELSE 0 END ) as sak'),
                              DB::raw('SUM( CASE WHEN complaints.sector_code = "3.2" THEN 1 ELSE 0 END ) as sap'),
                              DB::raw('SUM( CASE WHEN complaints.sector_code = "3.3" THEN 1 ELSE 0 END ) as satu')
                             )
                             ->first();                    

                             // Statistik jumlah aduan mengikut bulan
        $meet = Complaintlist::where('complaints.active', 1)
                            ->whereYear('complaints.tkh_mula', $year)
                            ->where('complaints.category_id', 12)
                            ->select(DB::raw('SUM( CASE WHEN MONTH ( complaints.tkh_mula ) = 1 THEN 1 ELSE 0 END ) as jan'),
                              DB::raw('SUM( CASE WHEN MONTH ( complaints.tkh_mula ) = 2 THEN 1 ELSE 0 END ) as feb'),
                              DB::raw('SUM( CASE WHEN MONTH ( complaints.tkh_mula ) = 3 THEN 1 ELSE 0 END ) as mac'),
                              DB::raw('SUM( CASE WHEN MONTH ( complaints.tkh_mula ) = 4 THEN 1 ELSE 0 END ) as apr'),
                              DB::raw('SUM( CASE WHEN MONTH ( complaints.tkh_mula ) = 5 THEN 1 ELSE 0 END ) as mei'),
                              DB::raw('SUM( CASE WHEN MONTH ( complaints.tkh_mula ) = 6 THEN 1 ELSE 0 END ) as jun'),
                              DB::raw('SUM( CASE WHEN MONTH ( complaints.tkh_mula ) = 7 THEN 1 ELSE 0 END ) as jul'),
                              DB::raw('SUM( CASE WHEN MONTH ( complaints.tkh_mula ) = 8 THEN 1 ELSE 0 END ) as ogos'),
                              DB::raw('SUM( CASE WHEN MONTH ( complaints.tkh_mula ) = 9 THEN 1 ELSE 0 END ) as sept'),
                              DB::raw('SUM( CASE WHEN MONTH ( complaints.tkh_mula ) = 10 THEN 1 ELSE 0 END ) as okt'),
                              DB::raw('SUM( CASE WHEN MONTH ( complaints.tkh_mula ) = 11 THEN 1 ELSE 0 END ) as nov'),
                              DB::raw('SUM( CASE WHEN MONTH ( complaints.tkh_mula ) = 12 THEN 1 ELSE 0 END ) as dis')
                            )
                            ->first();

        // Umpuk nilai 0 jika tiada nilai hasil dari query diatas
        $s_pkan = !empty($sect->pkan) ? $sect->pkan : "0";                    
        $s_sp = !empty($sect->sp) ? $sect->sp : "0";                     
        $s_sak = !empty($sect->sak) ? $sect->sak : "0";                      
        $s_sap = !empty($sect->sap) ? $sect->sap : "0";                      
        $s_satu = !empty($sect->satu) ? $sect->satu : "0";                      

        $sect = $obj = (object) ["pkan" => $s_pkan, "sp" => $s_sp, "sak" => $s_sak, "sap" => $s_sap, "satu" => $s_satu]; // Umpukan nilai array object baru hasil dari atas

        // $data = [
        //     'totalAll' => $totalAll,
        //     'mth' => $mth,
        //     'sect' => $sect
        // ];
        
        $data = array($totalAll, $mth, $done, $sect, $meet);

        //$data = json_encode($data);
        return response()->json($data);
        //return view('dashboard', compact('totalAll', 'mth', 'sect'));
    }

}
