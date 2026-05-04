<?php
/** 
  * Fail ini mengandungi segala yang berkaitan dengan Sub Menu Maklumat Aduan
  * By : MUHD SYARIZAN B. YAACOB
  * Date Created : Disember 2020
*/

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helper\EncID;
use App\Helper\Notify;
use App\Helper\Audit;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;
use App\Models\SubCategory;
use App\Models\Category;
use DataTables;

class SubCategoryController extends Controller
{
  /** 
    * Senarai Sub Kategori
  **/
  public function index(Request $request)
  {
  	if($request->search){
      $search = $request->search;
    }else{
      $search = '';
    }

    if($request->category){
      $cat = $request->category;
    }else{
      $cat = '';
    }

    // Dapatkan senarai sub kategori
    $subcategories = SubCategory::leftJoin('categories', 'categories.id', '=', 'subcategories.category_id')
						  	                ->where('subcategories.active', 1);

    if($search){ // Jika ada pilihan sektor
      $subcategories->where('subcategories.subcategory_desc', 'LIKE', '%'.$search.'%');
    }

    if($cat){ // Jika ada pilihan sektor
      $subcategories->where('subcategories.category_id', $cat);
    }

		$subcategories = $subcategories->select('subcategories.*', 'categories.category_desc')
				   	      	               ->get();

  	// Dapatkan senarai kategori
    $categories = Category::where('active', 1)
					   	            ->get();

  	return view('sub-category.index', compact('subcategories', 'categories', 'search', 'cat'));
  }

  /** 
    * Kemaskini Maklumat Sub Kategori
  **/
  public function store(Request $request)
  {
    // Validate data yang diperlukan
    $validatedData = $request->validate(
      [
        'category' => 'required',
        'subcategory_desc' => 'required',
      ]
    );

    // umpuk data yang dihantar ke field di DB
    $subcategory = new SubCategory;
    $subcategory->subcategory_desc = $request->subcategory_desc;
    $subcategory->category_id = $request->category;
    $subcategory->save();
    $id = $subcategory->id;

    // Audit trail
    Audit::create($id, null, 'Tambah Sub-Kategori', null, null, null, null, null, null, null, null, null);

    // Papar notifikasi berjaya
    if($subcategory){
      Notify::flash('success', __('Maklumat berjaya disimpan.'), 'BERJAYA');
    }else{
      Notify::flash('error', __('Maklumat tidak berjaya disimpan.'), 'GAGAL');  
    }

  	return redirect()->back();
  }

  /** 
    * Dapatkan Maklumat Sub Kategori
  **/
  public function getSubCategory($id)
  {
    // Dapatkan maklumat sub kategori mengikut id diatas
    $subcategory = SubCategory::where('id', $id)
						                  ->first();
				 
  	return response()->json($subcategory); // Hantar data dalam bentuk JSON
  }

  /** 
    * Dapatkan Maklumat Sub Kategori
  **/
  public function getSubCat($id)
  {
    // Dapatkan maklumat sub kategori mengikut id diatas
    $subcategory = SubCategory::where('category_id', $id)
                              ->where('active', 1)
                              ->get();
                 
    return response()->json($subcategory); // Hantar data dalam bentuk JSON
  }

  /** 
    * Kemaskini Maklumat Sub Kategori
  **/
  public function update(Request $request)
  {
  	// Validate data yang diperlukan
    $validatedData = $request->validate(
      [
        'category' => 'required',
        'subcategory_desc' => 'required'
      ]
    );

    // umpuk data yang dihantar ke field di DB
    $id = $request->id;
    $subcategory_desc = $request->subcategory_desc;
    $category_id = $request->category;

  	// Kemaskini data dalam DB mengikut ID diatas
    $subcategory = SubCategory::find($id);
    $subcategory->subcategory_desc = $subcategory_desc;
    $subcategory->category_id = $category_id;
    $subcategory->save();

    // Audit trail
    Audit::create($id, null, 'Kemaskini Sub-Kategori', null, null, null, null, null, null, null, null, null);

    // Papar notifikasi berjaya
    if($subcategory){
      Notify::flash('success', __('Maklumat berjaya disimpan.'), 'BERJAYA');
    }else{
      Notify::flash('error', __('Maklumat tidak berjaya disimpan.'), 'GAGAL');  
    }

    return redirect()->back();
  }

  /** 
    * Hapus Maklumat Sub Kategori
  **/
  public function delete(Request $request)
  {
    $id = $request->id; // Dapatkan id yang dihantar

  	// Kemaskini unit dalam DB mengikut ID diatas; 1 = aktif, 0 = tidak aktif
    $subcategory = SubCategory::find($id);
    $subcategory->active = 0;
    $subcategory->save();

    // Audit trail
    Audit::create($id, null, 'Hapus Sub-Kategori', null, null, null, null, null, null, null, null, null);

    if($subcategory){
      $arr = 1;
    }else{
      $arr = 0;
    }

    return json_encode($arr); 
  }

}