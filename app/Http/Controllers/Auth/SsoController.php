<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use InvalidArgumentException;
use GuzzleHttp\Client;

class SsoController extends Controller
{
    /**
     * Request for authorization from the SSO provider
     */
    public function redirect(Request $request)
    {
        $request->session()->put('state', $state = Str::random(40));

        $query = http_build_query([
            'client_id' => config('sso.client_id'),
            'redirect_uri' => config('sso.client_redirect_url'),
            'response_type' => 'code',
            'scope' => '*',
            'state' => $state,
            // 'prompt' => '', // "none", "consent", or "login"
        ]);

        return redirect(config('sso.host_url') . '/oauth/authorize?' . $query);
    }

    /**
     * Triggered after the provider respond to the client login request
     */
    public function callback(Request $request)
    {
        $state = $request->session()->pull('state');

        throw_unless(strlen($state) > 0 && $state === $request->state, InvalidArgumentException::class, 'Invalid state or scope value.');

        $client = new Client();
        $response = $client->request('POST',
            config('auth.sso_host_url') . '/oauth/token',
            [
                'verify' => false,
                'form_params' => [
                    'grant_type' => 'authorization_code',
                    'client_id' => config('auth.sso_client_id'),
                    'client_secret' => config('auth.sso_client_secret'),
                    'redirect_uri' => config('auth.sso_client_redirect_url'),
                    'code' => $request->code,
                ]
            ]
        );
        $body = $response->getBody();
       $json = json_decode( $body );
       $request->session()->put('access_token', $json->access_token );
       $request->session()->put('token_type', $json->token_type );
       $request->session()->put('expires_in', $json->expires_in );
       $request->session()->put('refresh_token', $json->refresh_token );

        return to_route('sso.login');
    }

    /**
     * Do the actual login operation 
     */
    public function login(Request $request)
    {
        $access_token = $request->session()->get('access_token');

        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $access_token
        ])
            ->get(config('sso.host_url') . '/api/auth/user');

        $data = $response->json();
dd($data);
        if ($response->successful()) {
        //     $user = User::updateOrCreate(
        //         [
        //             'ic_no' => $data['ic_no'],
        //             'email' => $data['email'],
        //         ],
        //         [
        //             'name' =>  $data['name'],
        //         ]
        //     );

        //     Profile::updateOrCreate(
        //         [
        //             'id_no' => $data['ic_no']
        //         ],
        //         [
        //             'name' =>  $data['name'],
        //             'email' => $data['email'],
        //             'user_id' => $user->id,
        //         ]
        //     );

            $user = User::where('ic_number', $json['username'])
                        ->where('active', 1)
                        ->first();

            //Auth::loginUsingId($user->id);

            if ($user->is_active == 0) {
                return redirect()->route('login')->with(['message' => 'Log Masuk Tidak Berjaya']);
            } else {
                Auth::login($user);
                Audit::create(null, null, 'Log Masuk SSO', null, null, null, null, null, null, null, null);
                return redirect($this->redirectPath());

                //return to_route('home')->with('success', 'Authenticated');
            }
        } else {
            //return redirect('login')->with('error', 'Failed to log in');
            return redirect()->route('login')->with(['message' => 'Log Masuk Tidak Berjaya!']);
        }
    }
}
