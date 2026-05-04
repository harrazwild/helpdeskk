<?php
/** 
  * Fail ini mengandungi segala yang berkaitan dengan Sub Menu Maklumat Aduan
  * By : MUHD SYARIZAN B. YAACOB
  * Date Created : Disember 2020
**/

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helper\Audit;
use App\Helper\EncID;
use App\Helper\Notify;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use App\Models\Section;
use App\Models\Unit;
use DataTables;

class CategoryController extends Controller
{
  /** 
    * Dapatkan Senarai Kategori
  **/
  public function index(Request $request)
  {
  	if($request->search){
      $search = $request->search;
    }else{
      $search = '';
    }

    if($request->section){
      $sec = $request->section;
    }else{
      $sec = '';
    }

    // Dapatkan senarai kategori
    $categories = Category::leftJoin('sections', 'sections.id', '=', 'categories.section_id')
						              ->where('categories.active', 1);

    if($search){ // Jika ada pilihan sektor
      $categories->where('category_desc', 'LIKE', '%'.$search.'%');
    }

    if($sec){ // Jika ada pilihan sektor
      $categories->where('section_id', $sec);
    }

    $categories = $categories->select('categories.*', 'sections.section_desc')
				   	                 ->get();

    // Dapatkan senarai seksyen
    $sections = Section::where('active', 1)
					             ->get();

  	return view('category.index', compact('categories', 'sections', 'search', 'sec'));
  }

  /** 
    * Simpan Maklumat Kategori Baru
  **/
  public function store(Request $request)
  {
    // Validate data yang diperlukan
    $validatedData = $request->validate(
      [
        'category_desc' => 'required',
        'section' => 'required',
      ]
    );

    // umpuk data yang dihantar ke field di DB
    $category = new Category;
    $category->category_desc = $request->category_desc;
    $category->section_id = $request->section;
    $category->save();
    $id = $category->id;

    // Audit trail
    Audit::create($id, null, 'Tambah Kategori', null, null, null, null, null, null, null, null);

    // Papar notifikasi berjaya
    if($category){
      Notify::flash('success', __('Maklumat berjaya disimpan.'), 'BERJAYA');
    }else{
      Notify::flash('error', __('Maklumat tidak berjaya disimpan.'), 'GAGAL');  
    }
      
  	return redirect()->back();
  }

  /** 
    * Dapatkan Maklumat Kategori
  **/
  public function getCategory($id)
  {
    // Dapatkan maklumat kategori mengikut id diatas
    $category = Category::where('id', $id)
					              ->first();
	
    // Dapatkan senarai seksyen
    $sections = Section::where('active', 1)
  					           ->get();

  	// umpuk semua hasil query ke bentuk multi-array
    $arr= array();
  	$arr['arr1'] = $category;
  	$arr['arr2'] = $sections;
  				 
  	return response()->json($arr); // hantar hasil dalam bentuk JSON
  }

  /** 
    * Kemaskini Maklumat Kategori
  **/
  public function update(Request $request)
  {
  	// Validate data yang diperlukan
    $validatedData = $request->validate(
      [
        'category_desc' => 'required',
        'section' => 'required',
      ]
    );

    // umpuk data yang dihantar ke field di DB
    $id = $request->id;
    $category_desc = $request->category_desc;
    $section_id = $request->section;

  	// Kemaskini data dalam DB mengikut ID diatas
    $category = Category::find($id);
    $category->category_desc = $category_desc;
    $category->section_id = $section_id;
    $category->save();

    // Audit trail
    Audit::create($id, null, 'Kemaskini Kategori', null, null, null, null, null, null, null, null);

    // Papar notifikasi berjaya
    if($category){
      Notify::flash('success', __('Maklumat berjaya disimpan.'), 'BERJAYA');
    }else{
      Notify::flash('error', __('Maklumat tidak berjaya disimpan.'), 'GAGAL');  
    }

    return redirect()->back();
  }

  /** 
    * Hapus Maklumat Kategori
  **/
  public function delete(Request $request)
  {
    $id = $request->id; // Dapatkan id yang dihantar

  	// Kemaskini unit dalam DB mengikut ID diatas; 1 = aktif, 0 = tidak aktif
    $category = Category::find($id);
    $category->active = 0;
    $category->save();

    // Audit trail
    Audit::create($id, null, 'Hapus Kategori', null, null, null, null, null, null, null, null);

    if($category){
      $arr = 1;
    }else{
      $arr = 0;
    }

    return json_encode($arr); 
  }

}
