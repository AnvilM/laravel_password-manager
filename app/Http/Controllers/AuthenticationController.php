<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Jenssegers\Agent\Facades\Agent;
use Stevebauman\Location\Facades\Location;

class AuthenticationController extends Controller
{
    public function login(Request $request){
        $email = $request->post('email');
        $password = $request->post('password');

        if($email == '' || $password == ''){
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $account = new Account();
        if($account->where('email', $email)->count() < 1 || !Hash::check($password, $account->where('email', $email)->pluck('password')->first())){
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $session_id = Str::uuid();
        $ip = "178.155.5.9"; /*$request->getClientIp()*/ 

        $platform = Agent::platform();  
        $app = Agent::browser();

        $location = Location::get($ip);
        $location = "{$location->cityName}, {$location->countryName}";

        $session = new Session();

        $session->id = $session_id;
        $session->ip = $ip;
        $session->location = $location;
        $session->platform = $platform;
        $session->app = $app;
        $session->save();
        
        
        return response()->json([
            'session_id' => $session_id,
        ]);

        
    }
}
