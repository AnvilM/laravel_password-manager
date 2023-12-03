<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\SessionHelper;
use App\Models\Session as SessionModel;

class SessionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        //Get token.
        $token = $request->header('token');


        //Get token payload.
        $payload = SessionHelper::getTokenPayload($token);

        //Get account id from token payload.
        $account_id = $payload['id'];


        //Get session from account id.
        $Session = new SessionModel();

        $Session = $Session->where('account_id', $account_id)->get();


        //Checks if sessions exists.
        if ($Session->count() == 0)
        {
            return response('Invalid account_id', 422);
        }


        //Return sessions.
        return response()->json($Session->toArray());
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        //Get token.
        $token = $request->header('token');

        //Get session id.
        $id = $request->route('id');


        //Get payload from token.
        $payload = SessionHelper::getTokenPayload($token);

        //Get account id from token payload.
        $account_id = $payload['id'];


        //Get session from session id and account id.
        $Session = new SessionModel();

        $Session = $Session->where('id', $id)->where('account_id', $account_id)->get();


        //Checks if session exists.
        if ($Session->count() == 0)
        {
            return response('Invalid id', 422);
        }


        //Return session.
        return response()->json($Session->toArray());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(Request $request)
    {
        //Get token.
        $token = $request->header('token');


        //Get payload data from token.
        $payload = SessionHelper::getTokenPayload($token);

        //Get account id from token payload data.
        $account_id = $payload['id'];

        //Get session id.
        $id = $request->route('id');


        $Session = new SessionModel();

        //Checks if session exists.
        if ($Session->where('id', $id)->where('account_id', $account_id)->count() == 0)
        {
            return response('Invalid id', 422);
        }

        //Delete session.
        $Session->where('id', $id)->where('account_id', $account_id)->delete();
    }
}
