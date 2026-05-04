<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Helper\Audit;
use Lunaweb\RecaptchaV3\Facades\RecaptchaV3;
use Auth;

class LoginController extends Controller
{
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

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        
        if(Auth::check() && Auth::user()->role->id == 6){
            $this->redirectTo = route('home');
        } else {
            $this->redirectTo = route('dashboard');
        }
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        $validator = $request->validate([
            'ic_number' => 'required',
             'password' => 'required'
        ],
        [
             'ic_number.required' => 'Masukkan ID Pengguna',
             'password.required' => 'Masukkan Katalaluan',
        ]
        );

        if(auth()->attempt(array('ic_number' => $request->ic_number, 'password' => $request->password)))
        {
            if(Auth::user()->active == 0){
                Auth::logout();
                return redirect('/')->with(['message' => 'ID Pengguna/Katalaluan Salah!']);
            }else{
                Audit::create(null, null, 'Log Masuk', null, null, null, null, null, null, null, null);
                return redirect($this->redirectPath());
            }
        }else{
            return redirect()->route('login')->with(['message' => 'ID Pengguna/Katalaluan Salah!']);
        }  
    }

    public function logout(Request $request)
    {
        Audit::create(null, null, 'Log Keluar', null, null, null, null, null, null, null, null);
        Auth::logout();
        return redirect('/');
    }

    public function username()
    {
       return 'ic_number';
    }
}
