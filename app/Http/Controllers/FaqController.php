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
use App\Models\Faq;
use DataTables;

class FaqController extends Controller
{
  /** 
    * Senarai Sub Kategori
  **/
  public function index()
  {
  	// Dapatkan soalan lazim
    $faq = Faq::where('active', 1)
                ->get();

  	return view('faq.index', compact('faq'));
  }

  /** 
    * Kemaskini Maklumat Sub Kategori
  **/
  public function store(Request $request)
  {
    // Validate data yang diperlukan
    $validatedData = $request->validate(
        [
            'question' => 'required',
            'answer' => 'required',
            //'column' => 'required',
        ]

    );

    // umpuk data yang dihantar ke field di DB
    if($request->show == 1){
    	$show = 1;
    }else{
    	$show = 0;
    }

    $faq = new Faq;
    $faq->question = $request->question;
    $faq->answer = $request->answer;
    //$faq->column = $request->column;
    $faq->show = $show;
    $faq->save();
    $id = $faq->id;

    // Audit trail
    Audit::create($id, null, 'Tambah Soalan Lazim', null, null, null, null, null, null, null, null);

    // Papar notifikasi berjaya
    if($faq){
      Notify::flash('success', __('Maklumat berjaya disimpan.'), 'BERJAYA');
    }else{
      Notify::flash('error', __('Maklumat tidak berjaya disimpan.'), 'GAGAL');  
    }

  	return redirect()->back();
  }

  /** 
    * Dapatkan Maklumat Sub Kategori
  **/
  public function getfaq($id)
  {
    // Dapatkan maklumat sub kategori mengikut id diatas
    $faq = Faq::where('id', $id)
			        ->first();
				 
  	return response()->json($faq); // Hantar data dalam bentuk JSON
  }

  /** 
    * Kemaskini Maklumat Sub Kategori
  **/
  public function update(Request $request)
  {
  	// Validate data yang diperlukan
    $validatedData = $request->validate(
      [
        'question' => 'required',
        'answer' => 'required',
      ]
    );

    // umpuk data yang dihantar ke field di DB
    if($request->show == 1){
    	$show = 1;
    }else{
    	$show = 0;
    }

    $id = $request->id;
    $question = $request->question;
    $answer = $request->answer;
    $show = $show;

  	// Kemaskini data dalam DB mengikut ID diatas
    $faq = Faq::find($id);
    $faq->question = $question;
    $faq->answer = $answer;
    $faq->show = $show;
    $faq->save();

    // Audit trail
    Audit::create($id, null, 'Kemaskini Soalan Lazim', null, null, null, null, null, null, null, null);

    // Papar notifikasi berjaya
    if($faq){
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
    $faq = Faq::find($id);
    $faq->active = 0;
    $faq->save();

    // Audit trail
    Audit::create($id, null, 'Hapus Soalan Lazim', null, null, null, null, null, null, null, null);

    if($faq){
      $arr = 1;
    }else{
      $arr = 0;
    }

    return json_encode($arr); 
  }

}
