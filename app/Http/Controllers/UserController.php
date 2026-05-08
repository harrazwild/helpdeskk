<?php
/** 
  * Fail ini mengandungi segala yang berkaitan dengan Sub Menu Pengguna Sistem
  * By : MUHD SYARIZAN B. YAACOB
  * Date Created : Disember 2020
**/

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helper\EncID;
use App\Helper\Notify;
use App\Helper\Audit;
use App\Helper\Utilities;
use App\Mail\ResetPasswordMail;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Unit;
use App\Models\Section;
use App\Models\Sector;
use App\Models\Department;
use App\Models\Grade;
use App\Models\Role;
use App\Models\Position;
use App\Models\SubCategory;
use App\Mail\NewUserMail;
use DataTables;

class UserController extends Controller
{
  /** 
    * Senarai Pengguna Sistem
  **/
  public function index(Request $request)
  {
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

    if($request->department){
      $department = $request->department;
    }else{
      $department = '';
    }

    if($request->role){
      $role = $request->role;
    }else{
      $role = '';
    }

    // Dapatkan senarai seksyen
    $sectors = Sector::where('active', 1)
				   	          ->get();

    // Dapatkan senarai seksyen
    $departments = Department::where('active', 1);

    if($sec){
      $departments->where('sector_code', $sec);
    }
      
    $departments = $departments->get();

    // Dapatkan senarai peranan                   
  	$roles = Role::where('active', 1)
  				       ->get();
         
      // Dapatkan senarai pengguna sistem             
  	$users = User::leftJoin('roles', 'users.role_id', '=', 'roles.id')
                 ->leftJoin('positions', 'users.jaw_code', '=', 'positions.position_code')
                 ->leftJoin('sectors', 'users.sector_code', '=', 'sectors.sector_code')
                 ->leftJoin('departments', 'users.department_code', '=', 'departments.department_code');
                   
    if($search){ // Jika ada carian
      $users->where(function($query) use ($search){
        $query->where('users.name', 'LIKE', '%'.$search.'%')
              ->orWhere('users.ic_number', 'LIKE', '%'.$search.'%');
               //->orWhere('complaints.email', 'LIKE', '%'.$search.'%');
      });
    }

    if($sec){ // Jika ada pilihan sektor
      $users->where('users.sector_code', $sec);
    }             

    if($department){ // Jika ada pilihan sektor
      $users->where('users.department_code', $department);
    }

    if($role){ // Jika ada pilihan sektor
      $users->where('users.role_id', $role);
    }
  	
    $users = $users->orderBy('users.name', 'ASC')
                   ->select('users.id', 'users.name', 'positions.position_desc', 'sectors.sector_desc', 'departments.department_desc', 'users.role_id', 'users.active', 'roles.role_desc')
    			         ->get();			  

  	return view('auth.index', compact('search', 'sec', 'department', 'role', 'sectors', 'departments', 'roles', 'users'));
  }

  /** 
    * Paparan Pengguna Sistem
  **/
  public function show($id)
  {
  	$id = EncID::get($id); // Decrypt ID yang dihantar
  	
    // Dapatkan maklumat pengguna mengikut ID diatas
  	$user = User::leftJoin('roles', 'users.role_id', '=', 'roles.id')
                ->leftJoin('positions', 'users.jaw_code', '=', 'positions.position_code')
                ->leftJoin('grade', 'users.gred_code', '=', 'grade.grade_code')
                ->leftJoin('sectors', 'users.sector_code', '=', 'sectors.sector_code')
                ->leftJoin('departments', 'users.department_code', '=', 'departments.department_code')
                ->leftJoin('sections', 'users.section_id', '=', 'sections.id')
        				->where('users.id', $id)
        				->select('users.name', 'users.ic_number', 'sectors.sector_desc', 'departments.department_desc', 'users.block', 'users.level', 'users.zone', 'users.telephone', 'users.handphone', 'users.email', 'grade.grade_desc', 'positions.position_desc', 'sections.section_desc', 'roles.role_desc')
        				->first();

  	return view('auth.show', compact('user'));
  }

  /** 
    * Paparan Tambah Pengguna Sistem
  **/
  public function create()
  {
  	// Dapatkan senarai peranan
    $roles = Role::where('active', 1)
  			         ->get();

    // Dapatkan senarai seksyen
    $sectors = Sector::where('active', 1)
                      ->get();

    // Dapatkan senarai seksyen
    $departments = Department::where('active', 1)       
                              ->get();

    $grades = Grade::all();

    // Dapatkan senarai jawatan
  	$positions = Position::all();

    // Dapatkan senarai seksyen                     
  	$sections = Section::where('active', 1)
  				   	         ->get();

    $subcategories = SubCategory::where('active', 1)->get();

  	return view('auth.create', compact('roles', 'sectors', 'departments', 'grades', 'positions', 'sections', 'subcategories'));
  }

  /** 
    * Simpan Data Pengguna Baru Sistem Ke DB
  **/
  public function store(Request $request)
  {
    // validate data yang diperlukan
    $validatedData = $request->validate(
      [
        'role_id' => 'required',
        'ic_number' => 'required|unique:users',
        'name' => 'required',
        // 'grade_id' => 'required',
        // 'position_id' => 'required',
        // 'sector_id' => 'required',
        // 'department_id' => 'required',
        // 'block' => 'required',
        // 'level' => 'required',
        // 'zone' => 'required',
        // 'telephone' => 'required',
        // 'handphone' => 'required',
        'email' => 'required|unique:users'
      ]
    );

    if($request->department_id == 9){
      $section = $request->section_id;
    }else{
      $section = null;
    }

    $ic_number = $request->ic_number;
    $email = $request->email;

    $password = Utilities::randomPassword();

    // umpuk data yang dihantar ke field di DB
    $user = new User;
    $user->role_id = $request->role_id;
    $user->ic_number = $ic_number;
    $user->name = $request->name;
    $user->gred_code = $request->grade_id;
    $user->jaw_code = $request->position_id;
    $user->sector_code = $request->sector_id;
    $user->department_code = $request->department_id;
    $user->block = $request->block;
    $user->level = $request->level;
    $user->zone = $request->zone;
    $user->section_id = $section;
    $user->telephone = $request->telephone;
    $user->handphone = $request->handphone;
    $user->email = $email;        
    $user->password = Hash::make($password);
    $user->save();
    $id = $user->id;

    if($request->has('subcategories') && is_array($request->subcategories)) {
        foreach($request->subcategories as $sub_id) {
            DB::table('user_subcategories')->insert([
                'user_id' => $id,
                'subcategory_id' => $sub_id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }

    // Papar notifikasi berjaya
    if($user){
      
      $data = [
        'ic_number' => $ic_number,
        'password' => $password,
      ];
      Mail::to($email)->send(new NewUserMail($data));

      // audit trail
      Audit::create($id, null, 'Tambah Pengguna', null, null, null, null, null, null, null, null);

      Notify::flash('success', __('Maklumat berjaya disimpan.'), 'BERJAYA');
    }else{
      Notify::flash('error', __('Maklumat tidak berjaya disimpan.'), 'GAGAL');  
    }

  	return redirect('/user');
  }

  /** 
    * Paparan Kemaskini Pengguna Sistem
  **/
  public function edit($id)
  {
  	$id = EncID::get($id); // Decrypt ID yang dihantar
  	
    // Dapatkan maklumat pengguna mengikut ID diatas
    $user = User::where('id', $id)
                ->first();

    // Dapatkan senarai peranan
    $roles = Role::where('active', 1)
                ->get();

    // Dapatkan senarai seksyen
    $sectors = Sector::where('active', 1)
                      ->get();

    // Dapatkan senarai seksyen
    $departments = Department::where('active', 1)
                              ->where('sector_code', $user->sector_code)        
                              ->get();

    $grades = Grade::all();

    // Dapatkan senarai jawatan
    $positions = Position::all();

    // Dapatkan senarai seksyen                     
    $sections = Section::where('active', 1)
                       ->get();

    $subcategories = SubCategory::where('active', 1)->get();
    $user_subcategories = DB::table('user_subcategories')->where('user_id', $id)->pluck('subcategory_id')->toArray();

  	return view('auth.edit', compact('user', 'roles', 'sectors', 'departments', 'grades', 'positions', 'sections', 'subcategories', 'user_subcategories'));			 			
  }

  /** 
    * Simpan Data Kemaskini Pengguna Sistem Ke DB
  **/
  public function update(Request $request, $id)
  {
  	$id = EncID::get($id); // Decrypt ID yang dihantar
  	
    // validate data yang diperlukan
  	$validatedData = $request->validate(
      [
        'role_id' => 'required',
        'ic_number' => 'required',
        'name' => 'required',
        'grade_id' => 'required',
        'position_id' => 'required',
        'sector_id' => 'required',
        'department_id' => 'required',
        'block' => 'required',
        'level' => 'required',
        'zone' => 'required',
        'telephone' => 'required',
        'handphone' => 'required',
        'email' => 'required'
      ]
    );

    if($request->department_id == '2.1.3'){
      $section = $request->section_id;
    }else{
      $section = null;
    }

  	// Kemaskini data dalam DB mengikut ID diatas
    $user = User::where('id', $id)
              	->update([
              		'role_id' => $request->role_id,
                    'ic_number' => $request->ic_number,
                    'name' => $request->name,
                    'gred_code' => $request->grade_id,
                    'jaw_code' => $request->position_id,
                    'sector_code' => $request->sector_id,
                    'department_code' => $request->department_id,
                    'block' => $request->block,
                    'level' => $request->level,
                    'zone' => $request->zone,
                    'section_id' => $section,
                    'telephone' => $request->telephone,
                    'handphone' => $request->handphone,
                    'email' => $request->email,
              	]);

    if($request->has('subcategories') && is_array($request->subcategories)) {
        DB::table('user_subcategories')->where('user_id', $id)->delete();
        foreach($request->subcategories as $sub_id) {
            DB::table('user_subcategories')->insert([
                'user_id' => $id,
                'subcategory_id' => $sub_id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    } else {
        DB::table('user_subcategories')->where('user_id', $id)->delete();
    }

    // Audit trail
  	Audit::create($id, null, 'Kemaskini Pengguna', null, null, null, null, null, null, null, null);

    // Papar notifikasi berjaya
    if($user){
      Notify::flash('success', __('Maklumat berjaya disimpan.'), 'BERJAYA');
    }else{
      Notify::flash('error', __('Maklumat tidak berjaya disimpan.'), 'GAGAL');  
    }

  	return redirect('/user');		 			
  }

  /** 
    * Dapatkan Senarai Unit Mengikut ID Seksyen
  **/
  public function getUnit($id)
  {
    // Dapatkan senarai unit mengikut ID seksyen yang dihantar
    $unit = Unit::where('id', $id)
			          ->first();

  	return response()->json($unit); // Hantar data dalam bentuk JSON
  }

  /** 
    * Tetapan Semula Katalaluan Pengguna Sistem
  **/
  public function reset(Request $request)
  {
    $id = $request->id; // Dapatkan id yang dihantar

    $password = Utilities::randomPassword();

    $u = User::where('id', $id)
             ->select('ic_number', 'email')
             ->first();

  	// Kemaskini katalaluan dalam DB mengikut ID diatas; default katalaluan = 'password'
    $user = User::where('id', $id)
                ->update(['password' => Hash::make($password)]);

    // Maklumat yang hendak dipaparkan di dalam emel notifikasi
    $data = [
      'ic_number' => $u->ic_number,
      'password' => $password,
    ];

    // Audit trail
    Audit::create($id, null, 'Set Semula Katalaluan Pengguna', null, null, null, null, null, null, null, null);
    Mail::to($u->email)->send(new ResetPasswordMail($data));

    if($user){
      $arr = 1;
    }else{
      $arr = 0;
    }

    return json_encode($arr); 
  }

  /** 
    * Hapus Pengguna Sistem
  **/
  public function delete(Request $request)
  {
    $id = $request->id; // Dapatkan id yang dihantar

      // Kemaskini pengguna aktif dalam DB mengikut ID diatas; 1 = aktif, 0 = tidak aktif
  	$user = User::where('id', $id)
              	->update(['active' => 0]);

    // Audit trail
    Audit::create($id, null, 'Nyah Aktif Pengguna', null, null, null, null, null, null, null, null);

    if($user){
      $arr = 1;
    }else{
      $arr = 0;
    }

    return json_encode($arr); 
  }

  /** 
    * Aktifkan Pengguna Sistem
  **/
  public function activate(Request $request)
  {
    $id = $request->id; // Dapatkan id yang dihantar

    // Kemaskini pengguna aktif dalam DB mengikut ID diatas; 1 = aktif, 0 = tidak aktif
    $user = User::where('id', $id)
                ->update(['active' => 1]);

    // Audit trail
    Audit::create($id, null, 'Aktifkan Pengguna', null, null, null, null, null, null, null, null);

    if($user){
      $arr = 1;
    }else{
      $arr = 0;
    }
    
    return json_encode($arr); 
  }

  /** 
    * Paparan Profil Pengguna Sistem
  **/
  public function profile()
  {
    $id = Auth::user()->id; // Decrypt ID yang dihantar
    
    // Dapatkan maklumat pengguna mengikut ID diatas
    $user = User::where('id', $id)
                ->first();

    // Dapatkan senarai seksyen
    $sectors = Sector::where('active', 1)
                      ->get();

    // Dapatkan senarai seksyen
    $departments = Department::where('active', 1)
                              ->where('sector_code', $user->sector_code)        
                              ->get();

    $grades = Grade::all();

    // Dapatkan senarai jawatan
    $positions = Position::all();

    // Dapatkan senarai seksyen                     
    $sections = Section::where('active', 1)
                       ->get();

    return view('auth.profile', compact('user', 'sectors', 'departments', 'grades', 'positions', 'sections'));
  }

  /** 
    * Kemaskini Profil Pengguna Sistem
  **/
  public function update_profile(Request $request, $id)
  {
    $id = EncID::get($id); // Decrypt ID yang dihantar

    // validate data yang diperlukan
    $validatedData = $request->validate(
      [
        'ic_number' => 'required',
        'name' => 'required',
        'grade_id' => 'required',
        'position_id' => 'required',
        'sector_id' => 'required',
        'department_id' => 'required',
        'block' => 'required',
        'level' => 'required',
        'zone' => 'required',
        'telephone' => 'required',
        'handphone' => 'required',
        'email' => 'required'
      ]
    );

    if($request->department_id == '2.1.3'){
      $section = $request->section_id;
    }else{
      $section = null;
    }

    // Kemaskini data dalam DB mengikut ID diatas
    $user = User::where('id', $id)
                ->update([
                    'ic_number' => $request->ic_number,
                    'name' => $request->name,
                    'gred_code' => $request->grade_id,
                    'jaw_code' => $request->position_id,
                    'sector_code' => $request->sector_id,
                    'department_code' => $request->department_id,
                    'block' => $request->block,
                    'level' => $request->level,
                    'zone' => $request->zone,
                    'section_id' => $section,
                    'telephone' => $request->telephone,
                    'handphone' => $request->handphone,
                    'email' => $request->email,
                ]);

    // Audit trail
    Audit::create($id, null, 'Pengguna Kemaskini Profil', null, null, null, null, null, null, null, null);

    // Papar notifikasi berjaya
    if($user){
      Notify::flash('success', __('Maklumat berjaya disimpan.'), 'BERJAYA');
    }else{
      Notify::flash('error', __('Maklumat tidak berjaya disimpan.'), 'GAGAL');  
    }

    return back();                   
  }

  /** 
    * Kemaskini Katalaluan Pengguna Sistem
  **/
  public function update_password(Request $request, $id)
  {
    $id = EncID::get($id); // Decrypt ID yang dihantar
    
    // Get current user
    $user = User::find($id);
    
    // validate data yang diperlukan
    $validatedData = $request->validate(
      [
        'current_password' => 'required',
        'new_password' => ['required', Password::min(12)->letters()->mixedCase()->numbers()->symbols()],
        'repeat_password' => 'required|same:new_password'
      ]
    );

    // Check if current password matches
    if (!Hash::check($request->current_password, $user->password)) {
      return redirect()->back()
                     ->withErrors(['current_password' => 'Katalaluan semasa tidak tepat.'])
                     ->withInput();
    }

    // umpuk data yang dihantar ke field di DB
    $password = $request->new_password;

    // Kemaskini data dalam DB mengikut ID diatas
    $user = User::where('id', $id)
                ->update([
                    'password' => Hash::make($password)
                ]);

    // Audit trail
    Audit::create($id, null, 'Pengguna Kemaskini Katalaluan', null, null, null, null, null, null, null, null);

    // Papar notifikasi berjaya
    if($user){
      Notify::flash('success', __('Maklumat berjaya disimpan.'), 'BERJAYA');
    }else{
      Notify::flash('error', __('Maklumat tidak berjaya disimpan.'), 'GAGAL');  
    }

    return redirect('/dashboard');               
  }
}
