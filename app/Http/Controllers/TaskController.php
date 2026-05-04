<?php
/** 
  * Fail ini mengandungi segala yang berkaitan dengan Sub Menu Senarai Pelaksana
  * By : MUHD SYARIZAN B. YAACOB
  * Date Created : Disember 2020
*/

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helper\EncID;
use App\Helper\Notify;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Task;

class TaskController extends Controller
{
  /** 
    * Hapus Maklumat Unit
  **/
  public function index()
  {
  	// Dapatkan senarai Pelaksana
    $users = User::leftJoin('positions', 'users.position_id', '=', 'positions.id')
        				 ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
        				 ->where('users.active', 1)
                 ->where('users.section_id', Auth::user()->section_id)
                 ->where(function($query){
                          $query->where('users.role_id', 2)
                                ->orWhere('users.role_id', 3);
                 })
        				 ->orderBy('roles.id', 'ASC')
                 ->orderBy('users.name', 'ASC')
        				 ->select('users.id', 'users.name', 'positions.position_desc', 'roles.role_desc')
        				 ->get();			  

  	return view('tasks.index', compact('users'));
  }
}
