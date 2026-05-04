<?php

namespace App\Exports;

use App\Models\Complaintlist;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class StaffDetailExport implements FromView, ShouldAutoSize
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
    $complaints = Complaintlist::leftJoin('tasks', 'tasks.complaint_id', '=', 'complaints.id')
                                 ->leftJoin('status', 'status.id', '=', 'complaints.status_id')
                                 ->where('complaints.active', 1)
                                 ->where('tasks.user_id', $this->staff)
                                 ->whereBetween('complaints.date_open', [$this->sDate.' 00:00:00', $this->eDate.' 23:59:59']);

      if($this->status){
        $complaints->where('complaints.status_id', $this->status);
      }

      if($this->kpi == 2 || $this->kpi == 3){
        if($this->kpi == 2){
          $complaints->whereRaw('DATEDIFF(IFNULL(complaints.date_close, CURDATE()), complaints.date_open) > 2 && DATEDIFF(IFNULL(complaints.date_close, CURDATE()), complaints.date_open) < 5');
        }elseif($this->kpi == 3){
          $complaints->whereRaw('DATEDIFF(IFNULL(complaints.date_close, CURDATE()), complaints.date_open) > 5');
        }
      }

      $complaints->select(array(
                                'complaints.application_no', 'complaints.name', 'complaints.sector_id', 
                                'complaints.department_id', 'complaints.status_id', 'complaints.date_open', 'status.status_desc',
                                DB::raw("DATEDIFF(IFNULL(complaints.date_close, CURDATE()), complaints.date_open) as tempoh")
                              )
                          );
      $complaints = $complaints->get();

    return view('reports.tbl_staff_detail', [
       'complaints' => $complaints, 's' => $this->staff
    ]);
  }
}
