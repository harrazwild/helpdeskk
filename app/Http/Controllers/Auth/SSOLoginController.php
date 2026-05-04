<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Providers\Auth\AuthenticatesUsers;
use App\Http\Controllers\Auth\JanssoHelper;
use App\Helper\Audit;
use App\Models\User;

class SSOLoginController extends Controller
{
    use JanssoHelper;
    protected function attempSSOLogin(Request $request) {
        $encryptedParam = $request->p;
        
        if (is_null($encryptedParam))
            return self::ssoFailed('Tiada maklumat SSO diberikan!');

        // echo "encryptedParam = $encryptedParam<br>";
        $key = config('auth.login_jan_sso_key');
        $jsonstr = JanssoHelper::JANSSODecrypt($encryptedParam, $key);

        if (is_null($jsonstr) || $jsonstr == '') return self::ssoFailed('Gagal membaca maklumat SSO!');
        // print_r($jsonstr);

        $json = json_decode($jsonstr, true);
        
        if (is_null($json) || $json == '') return self::ssoFailed('Gagal memproses maklumat SSO!');
        // print_r($json);

        if (
            !isset($json['action'])
            || !isset($json['type'])
            || !isset($json['lastvisitDate'])
            // || !isset($json['username'])
            // || !isset($json['email'])
        )
            return self::ssoFailed('Maklumat SSO tidak mencukupi!');

        // Logout current user
        Auth::logout();

        // login user
        if( ( $json['action'] == 'redirect' || $json['action'] == 'demon' ) && $json['type'] == 'login' ){
            
            $user = null;
            // Check nric as username
            // Also check enabled is true
            if (isset($json['username']))
                $user = User::where('ic_number',$json['username'])
                        ->where('active', 1)
                        ->first();

            // Disable other checking
            // Check username
            // if (!$user && isset($json['username'])) $user = User::where('username',$json['username'])->first();
            // // Check email
            // if (!$user && isset($json['email'])) $user = User::where('email',$json['email'])->first();
            // // Check nric (if available)
            // if (!$user && isset($json['nric'])) $user = User::where('nric',$json['nric'])->first();

            if (!$user) return self::ssoFailed('Pengguna tidak dijumpai atau tidak aktif!');

            // This will not happened as enabled was checked
            if (!$user->active) return self::ssoFailed('Pengguna tidak aktif!');

            Auth::loginUsingId($user->id);
            // Audit trail
            Audit::create($user->id, null, 'Log Masuk SSO', null, null, null, null, null, null, null, null);

            if (Auth::user()) {
                if (isset($json['action']) && $json['action'] == 'demon')
                    return "200";
                else
                    return redirect('/login');                
            } else {
                if (isset($json['action']) && $json['action'] == 'demon')
                    return "300";
                else
                    return redirect('/login')
                        ->with('alert', 'ssofailed')
                        ->with('failreason', "Tidak dapat log masuk pengguna!");
            }
        }

        // create user
        if( $json['action'] == 'demon' && $json['type'] == 'create' ){
            
            $userid = $json['username'];
            $password = $json['password'];
            $name = $json['name'];
            $email = $json['email'];
            
            $user = new User;
            $user->role_id = 6;
            $user->ic_number = $userid;
            $user->name = $name;
            $user->email = $email;        
            $user->password = Hash::make($password);
            $user->save();
            $id = $user->id;

            if($user){
                // Audit trail
                Audit::create($id, null, 'Kemaskini Pengguna', null, null, null, null, null, 'SSO create', null, null);
                echo "200";
                exit;
            } else {
                echo "300";
                exit;
            }       
        }
        
        // update user
        if( $json['action'] == 'demon' && $json['type'] == 'update' ){

            $userid = $json['username'];
            $password = $json['password'];
            $name = $json['name'];
            $email = $json['email'];
            
            $user = User::where('ic_number', $userid)
                        ->update([
                                'ic_number' => $userid,
                                'name' => $name,
                                'password' => Hash::make($password),
                                'email' => $email,
                        ]);

            if($user){
                // Audit trail
                Audit::create($user->id, null, 'Kemaskini Pengguna', null, null, null, null, null, 'SSO update', null, null);
                echo "200";
                exit;
            } else {
                echo "300";
                exit;
            }           
            
        }
        
    }

    private function ssoFailed($reason = '') {
        return redirect('/login')->with('alert', 'ssofailed')->with('failreason', $reason);
    }

    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;
}
