<?php

namespace App\Exports;

use App\Models\Complaintlist;
use App\Models\V_KPI;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StaffDetailExport implements FromView, ShouldAutoSize, WithStyles
{
  protected $sDate, $eDate, $staff, $status, $kpi;

	function __construct($sDate, $eDate, $staff, $status, $kpi) {
	      $this->sDate = $sDate;
	      $this->eDate = $eDate;
	      $this->staff = $staff;
	      $this->status = $status;
	      $this->kpi = $kpi;
	}

  /**
  * @return \Illuminate\Support\Collection
  */
  public function view(): View
  {
    $complaints = V_KPI::where('id_pelaksana', $this->staff)
                       ->whereBetween('date_open', [$this->sDate.' 00:00:00', $this->eDate.' 23:59:59']);

    if($this->status){
      $complaints->where('status_id', $this->status);
    }

    if($this->kpi == 2 || $this->kpi == 3){
      if($this->kpi == 2){
        $complaints->where('tempoh_ditutup', '>', 2)
                   ->where('tempoh_ditutup', '<', 5);
      }elseif($this->kpi == 3){
        $complaints->where('tempoh_ditutup', '>=', 5);
      }
    }

    $complaints = $complaints->get();

    return view('reports.tbl_staff_detail', [
       'complaints' => $complaints, 's' => $this->staff
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
