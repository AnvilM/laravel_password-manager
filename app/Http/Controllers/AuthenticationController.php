<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Jenssegers\Agent\Agent;
use Stevebauman\Location\Facades\Location;
use Illuminate\Support\Str;
use phpseclib3\Crypt\RSA;
use App\Helpers\SessionHelper;

class AuthenticationController extends Controller
{
    /** 
     * API Authentication
     *
     * @param  Request $request
     * @return string $token - API Authentication token.
     */
    public function signin(Request $request)
    {

        //Get account from email.
        $Account = new Account();
        $Account = $Account->where('email', $request->post('email'))->get();



        //If account exists.
        if ($Account->count() == 0)
        {
            return response('Unauthorized', 401);
        }

        //If password correct.
        if (!Hash::check($request->post('password'), $Account->pluck('password')->first()))
        {
            return response('Unauthorized', 401);
        }

        //If email verified.
        if ($Account->pluck('verified')->first() == 0)
        {
            return response('Email not confirmed', 401);
        }



        //Generate session token.
        $payload = [
            'email' => $request->post('email'),
            'id' => $Account->pluck('id')[0]
        ];

        $token = SessionHelper::generateToken($payload);


        //Get account ID.
        $accountId = $Account->pluck('id')[0];

        //Get session token from session token
        $session_token = explode('.', $token)[0];

        //Get client IP.
        $ip = "178.155.4.141"; //$request->ip();

        //Get client location.
        $position = Location::get($ip);

        $location = "{$position->cityName}, {$position->countryName}";

        //Get client platform and browser.
        $Agent = new Agent();

        $platform = $Agent->platform();

        $app = $Agent->browser();



        //Save session.
        $Session = new Session();

        $Session->account_id = $accountId;

        $Session->session_token = $session_token;

        $Session->ip = $ip;

        $Session->location = $location;

        $Session->platform = $platform;

        $Session->app = $app;

        $Session->save();



        //Return token.
        return response($token);
    }

    /**
     * Signup users.
     *
     * @param  Request $request
     * @return void
     */
    public function signup(Request $request)
    {
        //Email, password validation.
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8'
        ]);


        //TODO: Здесть нужно реальзовать отправку токена на почту

        $token = hash('sha256', Str::random() . $request->post('email')); //TODO: потом это удалить


        $Account = new Account();

        //Checks if account alredy exists.
        if ($Account->where('email', $request->post('email'))->where('verified', 0)->count() == 0)
        {
            //Save account.
            $Account->email = $request->post('email');

            $Account->password = $request->post('password');

            $Account->verify_token = $token;

            $Account->save();
        }

        //For exists account update verify_token.
        else
        {
            $Account->where('email', $request->post('email'))->where('verified', 0)->update([
                'verify_token' => $token
            ]);
        }


        return response($token); //TODO:потом это удалить
    }

    /**
     * Verify user email.
     *
     * @param  Request $request
     * @return void
     */
    public function verifyEmail(Request $request)
    {
        //Get verify token from url.
        $verify_token = $request->route('verify_token');


        //Get account from verify token.
        $Account = new Account();


        //Checks if account exists.
        if ($Account->where('verify_token', $verify_token)->count() == 0)
        {
            return response('Invalid token', 422);
        }


        //Update account verified and verify_token.
        $Account->where('verify_token', $verify_token)->update([
            'verified' => true,
            'verify_token' => null
        ]);
    }
}
