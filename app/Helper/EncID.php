<?php
/** 
  * Fail ini mengandungi fungsi encrypt ID yang dibekalkan
  * By : MUHD SYARIZAN B. YAACOB
  * Date Created : Disember 2020
**/

namespace App\Helper;


use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;
use Hashids\Hashids;

class EncID
{
    public static function get($encrypted_id) {
        try {
            return Crypt::decrypt($encrypted_id);
        } catch (DecryptException $e) {
            abort(404);
        }
    }
}
