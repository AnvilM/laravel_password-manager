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

        if($account->where('email', $email)->where('verified', 1)->count() < 1){
            return response()->json(['error' => 'Email not confirmed'], 401);
        }

        $Account = new Account();

        //Id аккаунта для которого генерируется сессия
        $account_id = $Account->where('email', $email)->where 

        //Генерация токена сессии
        $token = hash('sha256', Str::uuid());

        //IP клиента
        $ip =  $request->getClientIp();

        //Оперционная система клиента
        $platform = Agent::platform();  

        //Приложение клиента
        $app = Agent::browser();

        //Местоположение клиента
        $location = Location::get($ip);
        $location = "{$location->cityName}, {$location->countryName}";

        $session = new Session();

        $session->account_id = $account->where('email', $email)->pluck('id')->first();
        $session->id = $session_id;
        $session->ip = $ip;
        $session->location = $location;
        $session->platform = $platform;
        $session->app = $app;
        $session->save();
    
        return response()->json([
            'session_id' => $session_id
        ]);

        
    }
}
