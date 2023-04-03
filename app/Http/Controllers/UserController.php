<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Auth;
use App\Models\User;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $Users = User::where('role','!=', 'admin')->paginate(15);
        return jsonResponse(TRUE, '', ['Users' => $Users]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|unique:users|max:255',
                'password' => 'required|string|min:6',
            ]);
    
            if ($validator->fails()) {
                return jsonResponse(FALSE, 'Has Some Errors', ['errors' => $validator->errors()], 422);
                
            }
    
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
    
            $token = $user->createToken('OrderToken')->accessToken;
            return jsonResponse(TRUE, 'You registered successfuly !', [ 'token'=> $token ], 201);
           
        } catch(Exception $e)
        {
            return jsonResponse(FALSE, $e->getMessage(), [ ]);
            
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }


    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|string|email',
                'password' => 'required|string',
            ]);
    
            if ($validator->fails()) {
                return jsonResponse(FALSE, 'Error', $validator->errors(), 422);
            }
    
            $credentials = request(['email', 'password']);
    
            if (!Auth::attempt($credentials)) {
                return jsonResponse(FALSE, 'Please check your email and password !', [], 401);
            }
            
            $user = $request->user();
            $accessToken = $user->createToken('OrderToken')->accessToken;
            return jsonResponse(TRUE, 'You Login Successfuly !', ['access_token' => $accessToken]);
        } catch (Exception $e) {
            return jsonResponse(FALSE, $e->getMessage(), []);
        }
    }


    /**
     * Store a newly created resource in storage.
     */
    public function getUserDetail()
    {
        if(Auth::guard('api')->check()){
            $user = Auth::guard('api')->user();
            return jsonResponse(TRUE, '', ['data' => $user], 200);
        }
        return Response(['data' => 'Unauthorized'],401);
    }

    /**
     * Display the specified resource.
     */
    public function userLogout(): Response
    {
        if(Auth::guard('api')->check()){
            $accessToken = Auth::guard('api')->user()->token();

                \DB::table('oauth_refresh_tokens')
                    ->where('access_token_id', $accessToken->id)
                    ->update(['revoked' => true]);
            $accessToken->revoke();

            return Response(['data' => 'Unauthorized','message' => 'User logout successfully.'],200);
        }
        return Response(['data' => 'Unauthorized'],401);
    }



}
