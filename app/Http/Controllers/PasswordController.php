<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Helpers\SessionHelper;
use App\Models\Password;
use phpseclib3\Crypt\RSA;

class PasswordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //Get token.
        $token = $request->header('token');


        //Get payload data from token.
        $payload = SessionHelper::getTokenPayload($token);

        //Get account id from token payload data.
        $account_id = $payload['id'];


        //Get password from id and account_id.
        $Password = new Password();

        $Password = $Password->where('account_id', $account_id)->get();


        //Checks if password exists.
        if ($Password->count() == 0)
        {
            return response('Invalid id', 422);
        }

        return response()->json($Password->toArray());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //Validate password, name and description.
        $request->validate([
            'password' => 'required',
            'name' => 'required',
            'description' => 'max:256'
        ]);


        //Get password, name and description.
        $password = $request->post('password');

        $name = $request->post('name');

        $description = $request->post('description');


        //Get token.
        $token = $request->header('token');


        //Get payload data from token.
        $payload = SessionHelper::getTokenPayload($token);

        //Get account id from token payload data.
        $account_id = $payload['id'];



        //Encrypt client password.
        $RSA = RSA::loadPrivateKey(env('SECRET'));

        $password = base64_encode($RSA->getPublicKey()->encrypt($password));


        //Save password.
        $Password = new Password();

        $Password->account_id = $account_id;

        $Password->name = $name;

        $Password->password = $password;

        $Password->description = $description;

        $Password->save();
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        //Get password id.
        $id = $request->route('id');

        //Get token.
        $token = $request->header('token');


        //Get payload data from token.
        $payload = SessionHelper::getTokenPayload($token);

        //Get account id from token payload data.
        $account_id = $payload['id'];


        //Get password from id and account_id.
        $Password = new Password();

        $Password = $Password->where('id', $id)->where('account_id', $account_id)->get();


        //Checks if password exists.
        if ($Password->count() == 0)
        {
            return response('Invalid id', 422);
        }


        //Return response.
        return response()->json($Password->toArray());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        //Get name.
        $name = $request->post('name');

        //Get password.
        $password = $request->post('password');

        //Get description.
        $description = $request->post('description');

        //Get password id.
        $id = $request->route('id');


        //Get token.
        $token = $request->header('token');



        //Get payload data from token.
        $payload = SessionHelper::getTokenPayload($token);


        //Get account id from token payload data.
        $account_id = $payload['id'];



        //Encrypt clien password.
        $RSA =  RSA::loadPrivateKey(env('SECRET'));

        $password = base64_encode($RSA->getPublicKey()->encrypt($password));



        $Password = new Password();


        //Checks if password exists.
        if ($Password->where('id', $id)->where('account_id', $account_id)->count() == 0)
        {
            return response('Invalid id', 422);
        }


        //Update password.
        $Password->where('id', $id)
            ->where('account_id', $account_id)
            ->update([
                'name' => $name,
                'password' => $password,
                'description' => $description
            ]);
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


        $Password = new Password();

        //Checks if password exists.
        if ($Password->where('id', $id)->where('account_id', $account_id)->count() == 0)
        {
            return response('Invalid id', 422);
        }

        //Delete password.
        $Password->where('id', $id)->where('account_id', $account_id)->delete();
    }
}
