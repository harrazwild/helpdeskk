<?php
/** 
  * Fail ini mengandungi segala yang berkaitan dengan Sub Menu Maklumat Aduan
  * By : MUHD SYARIZAN B. YAACOB
  * Date Created : Disember 2020
**/

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    /** 
    * Dapatkan Senarai Bahagian
  **/
  public function get_Departments($sector_id)
  {
    // Dapatkan senarai bahagian mengikut ID diatas
    $departments = Department::where('bah_status', 1)
                             ->where('bah_user', '!=', 'NA')
                             ->where('bah_seccode', $sector_id)
                             ->orderby("bah_order","ASC")
                			       ->get();

    return response()->json($departments); // Hantar data dalam bentuk JSON
  }

}
