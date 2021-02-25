<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\User;
use App\Notifications\WelcomeToFandK;
use App\Mail\WelcomeToOurPlatform;
use Illuminate\Support\Facades\Mail;
use App\Notifications\YouAreWelcome;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    public function __construct() {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function login(Request $request)
    {
        //Validate the request
        $validator= Validator::make($request->all(), [
            'email'=>'required |email',
            'password'=>'required|string|min:6'
        ]);

        //If the request fails, return the error
        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }

        if(!$token= auth()->attempt($validator->validated()))
        {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);

    }


        public function register(Request $request)
        {
            //Validate the  Request
            $validator= Validator::make($request->all(), [
            'username' => 'required|string|alpha_dash|unique:users|max:50',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|confirmed|min:6',
            ]);


        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user= User::create([
            'username'=> $request->username,
            'email'=>$request->email,
            'password'=>bcrypt($request->password),
        ]);
            //Send an email notification and queue it
            mail::to($user->email)->queue(new WelcomeToOurPlatform($user));
            return response()->json([
                'status'=>true,
                'message' => 'User successfully registered',
                'user' => $user
            ], 201);

        }

        public function profile()
        {
            return response()->json(auth()->user());
        }




    protected function respondWithToken($token){

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user()
        ]);


        }

        public function logout()
        {
            auth()->logout();
            return response()->json(['message' => 'User successfully signed out'], 200);
        }
}
