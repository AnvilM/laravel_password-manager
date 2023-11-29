<?php

namespace App\Http\Middleware;

use App\Helpers\SessionHelper;
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
        $session_id = $request->header('session_id');

        if($session_id == ''){
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $session = new SessionModel();
        
        if($session->where('id', $session_id)->count() < 1){
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}
