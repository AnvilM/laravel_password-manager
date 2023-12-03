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
        $token = $request->header('token');


        $payload = SessionHelper::getTokenPayload($token);

        $account_id = $payload['id'];


        $Session = new SessionModel();

        $Session = $Session->where('account_id', $account_id)->get();


        if ($Session->count() == 0)
        {
            return response('Invalid account_id', 422);
        }


        return response()->json($Session->toArray());
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $token = $request->header('token');

        $id = $request->route('id');


        $payload = SessionHelper::getTokenPayload($token);

        $account_id = $payload['id'];


        $Session = new SessionModel();

        $Session = $Session->where('id', $id)->where('account_id', $account_id)->get();


        if ($Session->count() == 0)
        {
            return response('Invalid id', 422);
        }


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

        //Get password id.
        $id = $request->route('id');


        $Session = new SessionModel();

        //Checks if password exists.
        if ($Session->where('id', $id)->where('account_id', $account_id)->count() == 0)
        {
            return response('Invalid id', 422);
        }

        //Delete password.
        $Session->where('id', $id)->where('account_id', $account_id)->delete();
    }
}
