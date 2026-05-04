<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helper\Audit;
use App\Helper\EncID;
use App\Helper\Notify;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use App\Models\Vendor;
use DataTables;

class PembekalController extends Controller
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
    
    // Dapatkan senarai pembekal
    if($search){ // Jika ada pilihan sektor
      $vendors = Vendor::where('vendor_name', 'LIKE', '%'.$search.'%')->get();
    }else{
      $vendors = Vendor::get();
    }

    return view('pembekal.index', compact('vendors', 'search'));
  }

  /** 
    * Simpan Maklumat Kategori Baru
  **/
  public function store(Request $request)
  {
      // Validate data yang diperlukan
      $validatedData = $request->validate(
          [
              'vendor_name' => 'required',
          ]

      );

      // umpuk data yang dihantar ke field di DB
      $vendor = new Vendor;
      $vendor->vendor_name = $request->vendor_name;
      $vendor->save();
      $id = $vendor->id;

      // Audit trail
      Audit::create($id, null, 'Tambah Pembekal', null, null, null, null, null, null, null, null);

      // Papar notifikasi berjaya
      Notify::flash('success', __('Maklumat berjaya disimpan.'), 'BERJAYA');
      return redirect()->back();
  }

  /** 
    * Dapatkan Maklumat Kategori
  **/
  public function getPembekal($id)
  {
      // Dapatkan maklumat kategori mengikut id diatas
      $vendor = Vendor::where('id', $id)
                        ->first();
    
      return response()->json($vendor); // hantar hasil dalam bentuk JSON
  }

  /** 
    * Kemaskini Maklumat Kategori
  **/
  public function update(Request $request)
  {
    // Validate data yang diperlukan
    $validatedData = $request->validate(
      [
        'vendor_name' => 'required'
      ]
    );

    // umpuk data yang dihantar ke field di DB
    $id = $request->id;
    $vendor_name = $request->vendor_name;

    $vendor = Vendor::find($id);
    $vendor->vendor_name = $vendor_name;
    $vendor->save();

    // Audit trail
    Audit::create($id, null, 'Kemaskini Pembekal', null, null, null, null, null, null, null, null);

    // Papar notifikasi berjaya
    if($vendor){
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
    $vendor = Vendor::find($id);
    $vendor->active = 0;
    $vendor->save();

    // Audit trail
    Audit::create($id, null, 'Hapus Kategori', null, null, null, null, null, null, null, null);

    if($vendor){
      $arr = 1;
    }else{
      $arr = 0;
    }

    return json_encode($arr); 
  }

  /** 
    * Aktifkan Pembekal
  **/
  public function activate_pembekal(Request $request)
  {
    $id = $request->id; // Dapatkan id yang dihantar

    // Kemaskini pengguna aktif dalam DB mengikut ID diatas; 1 = aktif, 0 = tidak aktif
    $vendor = Vendor::where('id', $id)
                    ->update(['active' => 1]);

    // Audit trail
    Audit::create($id, null, 'Aktifkan Pembekal', null, null, null, null, null, null, null, null);

    if($vendor){
      $arr = 1;
    }else{
      $arr = 0;
    }

    return json_encode($arr); 
  }

}

