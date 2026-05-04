<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StaffStatExport implements FromView, ShouldAutoSize, WithStyles
{
  protected $sDate, $eDate, $staff, $section;

	function __construct($sDate, $eDate, $staff, $section) {
	      $this->sDate = $sDate;
	      $this->eDate = $eDate;
	      $this->staff = $staff;
        $this->section = $section;
	}

  /**
  * @return \Illuminate\Support\Collection
  */
  public function view(): View
  {
    $p = User::leftJoin('positions', 'users.position_id', '=', 'positions.id')
             ->leftJoin('grade', 'users.grade_id', '=', 'grade.id')
             ->where('users.active', 1)
             ->where(function($query){
                $query->where('users.role_id', '!=', 4)
                      ->where('users.role_id', '!=', 6);
             })
             ->orderBy('grade.order', 'ASC')
             ->orderBy('users.name', 'ASC');

    if($this->staff != 0){
      $p = $p->where('users.id', $this->staff);
    }

    if($this->section != 0){
      $p = $p->where('users.section_id', $this->section);
    }         
      $p = $p->select('users.id', 'users.name', 'positions.position_desc')
             ->get();

    return view('reports.tbl_staff_stat', [
       'p' => $p, 'sDate' => $this->sDate, 'eDate' => $this->eDate
    ]);
  }

  public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true]]
        ];
    }

}
