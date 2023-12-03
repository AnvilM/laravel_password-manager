<?php

namespace App\Http\Middleware;

use App\Helpers\SessionHelper;
use App\Models\Account;
use App\Models\Session as SessionModel;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Symfony\Component\HttpFoundation\Response;

class Session
{
    /**
     * Сhecks if the user is authorized.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        //Get session token.
        $token = $request->header('token');

        if ($token == '')
        {
            return response('Invalid token', 422);
        }

        //Validate API Authentication token.
        if (!SessionHelper::validateToken($token))
        {
            return response('Invalid token', 422);
        };


        //Get session token from token.
        $session_token = explode('.', $token)[0];


        //Get token payload.
        $payload = SessionHelper::getTokenPayload($token);


        //Get email from token payload.
        $email = $payload['email'];

        // Get account id from token payload.
        $account_id = $payload['id'];



        //Get account from token payload email and id.
        $Account = new Account();

        $Account = $Account->where('email', $email)
            ->where('id', $account_id)
            ->get();

        //Checks if account exists.
        if ($Account->count() == 0)
        {
            return response('Unauthorized', 401);
        }



        //Get session from session_token and account_id
        $Session = new SessionModel();

        $Session = $Session
            ->where('session_token', $session_token)
            ->where('account_id', $account_id)->get();


        //Checks if session exists
        if ($Session->count() == 0)
        {
            return response('Unauthorized', 401);
        }



        //Return next.
        return $next($request);
    }
}
