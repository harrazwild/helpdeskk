<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use afiqiqmal\MalaysiaHoliday\MalaysiaHoliday;
use App\Models\Holiday;
use App\Helper\Notify;

class HolidayController extends Controller
{
  public function index(Request $request)
  {
    $holiday = new MalaysiaHoliday;

    $holiday = new MalaysiaHoliday;
    $result = $holiday->fromState("Putrajaya")->get();

    foreach($result['data'][0]['collection'][0]['data'] as $data){
      
      $date = Holiday::where('date', $data['date'])
                     ->count();

      if($date <> 1){
        $year = date('Y', strtotime($data['date']));

        $hldy = new Holiday;
        $hldy->day = $data['day'];
        $hldy->year = $year;
        $hldy->date = $data['date'];
        $hldy->name = $data['name'];
        $hldy->save();
      }

    }

    Notify::flash('success', __('Cuti Umum Berjaya Disimpan.'), 'BERJAYA');

    if(isset($request->tahun)){
      $y = $request->tahun;
    }else{
      $y = date('Y');
    }

    $holidays = Holiday::where('active', 1)
                       ->where('year', $y);

    if($request->search){ // Jika ada pilihan sektor
      $holidays->where('name', 'LIKE', '%'.$request->search.'%');
    }

    $holidays = $holidays->get();

    return view('holiday.index', compact('holidays', 'y'));                   
  }

}
