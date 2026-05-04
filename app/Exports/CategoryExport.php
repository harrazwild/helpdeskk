<?php

namespace App\Exports;

use App\Models\Complaintlist;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CategoryExport implements FromView, ShouldAutoSize, WithStyles
{
  protected $sDate, $eDate, $cat;

	function __construct($sDate, $eDate, $cat) {
	      $this->sDate = $sDate;
	      $this->eDate = $eDate;
	      $this->cat = $cat;
	}

  /**
  * @return \Illuminate\Support\Collection
  */
  public function view(): View
  {
    $categories = Category::where('active', 1);
                            
    if($this->cat){
      $categories->where('id', $this->cat);
    }else{
      $categories->where('section_id', Auth::user()->section_id);
    }                      

    $categories = $categories->get();

    return view('reports.tbl_category', [
       'categories' => $categories, 'sDate' => $this->sDate, 'eDate' => $this->eDate
    ]);
  }

  public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true]],
        ];
    }

}
