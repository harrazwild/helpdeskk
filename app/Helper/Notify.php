<?php
/** 
  * Fail ini mengandungi fungsi paparan notifikasi pada sistem
  * By : MUHD SYARIZAN B. YAACOB
  * Date Created : Disember 2020
**/

namespace App\Helper;

use Session;

class Notify
{

    static public function flash($type, $message, $title) {
        Session::flash('alert', ['type' => $type, 'message' => $message, 'title' => $title]);
        return true;
    }

}
