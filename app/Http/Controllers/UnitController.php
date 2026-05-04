<?php
/** 
  * Fail ini mengandungi segala yang berkaitan dengan Sub Menu Unit
  * By : MUHD SYARIZAN B. YAACOB
  * Date Created : Disember 2020
**/

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helper\EncID;
use App\Helper\Notify;
use App\Helper\Audit;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;
use App\Models\Unit;
use App\Models\Section;
use DataTables;

class UnitController extends Controller
{
  /** 
    * Senarai Unit
  **/
  public function index()
  {
  	// Dapatkan Senarai Seksyen
    $sections = Section::where('active', 1)
				   	           ->get();

  	// Dapatkan senarai unit
    $units = Unit::leftJoin('sections', 'sections.id', '=', 'units.section_id')
        				 ->where('units.active', 1)
        				 ->select('units.*', 'sections.section_desc')
        				 ->get();			  

  	return view('units.index', compact('sections', 'units'));
  }

  /** 
    * Paparan Tambah Unit
  **/
  public function create()
  {
  	// Dapatkan senarai seksyen
    $sections = Section::where('active', 1)
  				   	         ->get();

  	return view('units.create', compact('sections'));
  }

  /** 
    * Simpan Maklumat Unit Baru
  **/
  public function store(Request $request)
  {
    // Validate data yang diperlukan
    $validatedData = $request->validate(
      [
        'section' => 'required',
        'unit_desc' => 'required',
      ]
    );

    // umpuk data yang dihantar ke field di DB
    $unit = new Unit;
    $unit->section_id = $request->section;
    $unit->unit_desc = $request->unit_desc;
    $unit->save();
    $id = $unit->id;

    // Audit trail
    Audit::create($id, null, 'Tambah Unit', null, null, null, null, null, null, null, null);

    // Papar notifikasi berjaya
    if($unit){
      Notify::flash('success', __('Maklumat berjaya disimpan.'), 'BERJAYA');
    }else{
      Notify::flash('error', __('Maklumat tidak berjaya disimpan.'), 'GAGAL');  
    }

  	return redirect()->back();
  }

  /** 
    * Dapatkan Maklumat Unit
  **/
  public function get_Unit($id)
  {
    // Dapatkan maklumat unit mengikut id diatas
    $unit = Unit::where('id', $id)
			          ->first();

  	return response()->json($unit); // Hantar data dalam bentuk JSON
  }

  /** 
    * Kemaskini Maklumat Unit
  **/
  public function update(Request $request)
  {
  	// Validate data yang diperlukan
    $validatedData = $request->validate(
      [
        'section' => 'required',
        'unit_desc' => 'required'
      ]
    );

      // umpuk data yang dihantar ke field di DB
    $id = $request->id;
    $section_id = $request->section;
    $unit_desc = $request->unit_desc;

    // Kemaskini data dalam DB mengikut ID diatas
    $unit = Unit::find($id);
    $unit->section_id = $section_id;
    $unit->unit_desc = $unit_desc;
    $unit->save();

    // Audit trail
    Audit::create($id, null, 'Kemaskini Unit', null, null, null, null, null, null, null, null);

    // Papar notifikasi berjaya
    if($unit){
      Notify::flash('success', __('Maklumat berjaya disimpan.'), 'BERJAYA');
    }else{
      Notify::flash('error', __('Maklumat tidak berjaya disimpan.'), 'GAGAL');  
    }

    return redirect()->back();
  }

  /** 
    * Hapus Maklumat Unit
  **/
  public function delete(Request $request)
  {
    $id = $request->id; // Dapatkan id yang dihantar

  	// Kemaskini unit dalam DB mengikut ID diatas; 1 = aktif, 0 = tidak aktif
    $unit = Unit::find($id);
    $unit->active = 0;
    $unit->save();

    // Audit trail
    Audit::create($id, null, 'Hapus Unit', null, null, null, null, null, null, null, null);

    if($unit){
      $arr = 1;
    }else{
      $arr = 0;
    }

    return json_encode($arr); 
  }
}
