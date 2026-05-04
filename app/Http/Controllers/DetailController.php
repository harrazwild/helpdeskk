<?php
/** 
  * Fail ini mengandungi segala yang berkaitan dengan Sub Menu Maklumat Aduan
  * By : MUHD SYARIZAN B. YAACOB
  * Date Created : Disember 2020
*/

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
use App\Models\SubCategory;
use App\Models\Category;
use App\Models\Detail;
use DataTables;

class DetailController extends Controller
{
  /** 
    * Senarai Perincian
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

    // if($request->subcategory){
    //   $subcat = $request->subcategory;
    // }else{
    //   $subcat = '';
    // }
    
    // Dapatkan senarai perincian
    $details = Detail::leftJoin('subcategories', 'details.subcategory_id', '=', 'subcategories.id')
                     ->leftJoin('categories', 'details.category_id', '=', 'categories.id')
                     ->where('details.active', 1);

    if($search){ // Jika ada pilihan sektor
      $details->where('details.detail_desc', 'LIKE', '%'.$search.'%');
    }

    if($cat){ // Jika ada pilihan sektor
      $details->where('details.category_id', $cat);
    }

    // if($subcat){ // Jika ada pilihan sektor
    //   $details->where('details.subcategory_id', $subcat);
    // }

    $details = $details->select('details.*', 'categories.category_desc', 'subcategories.subcategory_desc')
                       ->get();

  // Dapatkan senarai kategori
    $categories = Category::where('active', 1)
                          ->get();

    // Dapatkan senarai sub kategori
    $subcategories = SubCategory::where('active', 1)
                                ->get();

    return view('details.index', compact('details', 'subcategories', 'categories', 'search', 'cat'));
  }

  /** 
    * Simpan Maklumat Perincian
  **/
  public function store(Request $request)
  {
    // Validate data yang diperlukan
    $validatedData = $request->validate(
        [
            'category' => 'required',
            'subcategory' => 'required',
            'detail_desc' => 'required',
        ]

    );

    // umpuk data yang dihantar ke field di DB
    $detail = new Detail;
    $detail->detail_desc = $request->detail_desc;
    $detail->category_id = $request->category;
    $detail->subcategory_id = $request->subcategory;
    $detail->save();
    $id = $detail->id;

    // Audit trail
    Audit::create($id, null, 'Tambah Perincian', null, null, null, null, null, null, null, null);

    // Papar notifikasi berjaya
    if($detail){
      Notify::flash('success', __('Aduan Berjaya Dikemaskini.'), 'BERJAYA');
    }else{
      Notify::flash('error', __('Aduan Tidak Berjaya Dikemaskini.'), 'GAGAL');
    }

    return redirect()->back();
  }

  /** 
    * Dapatkan Maklumat Perincian
  **/
  public function getDetails($id)
  {
    $details = Detail::where('subcategory_id', $id)
                     ->get();

    return response()->json($details);         
  }

  /** 
    * Dapatkan Maklumat Perincian
  **/
  public function get_Detail($id)
  {
    $details = Detail::where('id', $id)
                     ->first();

    return response()->json($details);         
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
    * Kemaskini Maklumat Perincian
  **/
  public function update(Request $request)
  {
    // Validate data yang diperlukan
    $validatedData = $request->validate(
        [
            'category' => 'required',
            'subcategory' => 'required',
            'detail_desc' => 'required',
        ]

    );

    // umpuk data yang dihantar ke field di DB
    $id = $request->id;
    $detail_desc = $request->detail_desc;
    $category_id = $request->category;
    $subcategory_id = $request->subcategory;

    // Kemaskini data dalam DB mengikut ID diatas
    $detail = Detail::find($id);
    $detail->detail_desc = $detail_desc;
    $detail->category_id = $category_id;
    $detail->subcategory_id = $subcategory_id;
    $detail->save();

    // Audit trail
    Audit::create($id, null, 'Kemaskini Perincian', null, null, null, null, null, null, null, null);

    // Papar notifikasi berjaya
    if($detail){
      Notify::flash('success', __('Aduan Berjaya Dikemaskini.'), 'BERJAYA');
    }else{
      Notify::flash('error', __('Aduan Tidak Berjaya Dikemaskini.'), 'GAGAL');
    }

    return redirect()->back();
  }

  /** 
    * Hapus Maklumat Perincian
  **/
  public function delete(Request $request)
  {
    $id = $request->id; // Dapatkan id yang dihantar

    // Kemaskini unit dalam DB mengikut ID diatas; 1 = aktif, 0 = tidak aktif
    $detail = Detail::find($id);
    $detail->active = 0;
    $detail->save();

    // Audit trail
    Audit::create($id, null, 'Hapus Perincian', null, null, null, null, null, null, null, null);

    if($detail){
      $arr = 1;
    }else{
      $arr = 0;
    }

    return json_encode($arr); 
  }
}
