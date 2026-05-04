<?php
/** 
  * Fail ini mengandungi fungsi audit trail / activity logs
  * By : MUHD SYARIZAN B. YAACOB
  * Date Created : Disember 2020
**/

namespace App\Helper;
//use Illuminate\Support\Facades\Auth;
//use Illuminate\Http\Request;
use App\Models\AuditTrail;

use Session;
use Request;
use Auth;

class Audit
{

    static public function create($object_id, $app_no, $message, $staff, $officer, $category, $subcategory, $details, $status, $remark, $vendor) {
        $log = [];
    	$log['object_id'] = $object_id;
    	//$log['table'] = $table;
    	$log['application_no'] = $app_no;
    	$log['description'] = $message;
    	$log['user_id'] = Auth::check() ? Auth::user()->id : null;
        $log['name'] = Auth::check() ? Auth::user()->name : null;
        $log['staff'] = $staff;
        $log['officer'] = $officer;
        $log['vendor'] = $vendor;
        $log['category'] = $category;
        $log['subcategory'] = $subcategory;
        $log['details'] = $details;
        $log['status'] = $status;
        $log['remark'] = $remark;
    	$log['ip_address'] = Request::ip();
    	$log['agent'] = Request::header('user-agent');
    	AuditTrail::create($log);
    }

}


