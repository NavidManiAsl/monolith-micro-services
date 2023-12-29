<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();
            $token = $user->createToken('admin')->accessToken;

            return [
                'token' => $token
            ];
        }

        return response([
            'message' => 'invalid credentials'
        ],Response::HTTP_UNAUTHORIZED);
    }

    public function register(AuthRegisterRequest $request){
        try{
            $user = User::create([
                'first_name' => $request->input('first_name'),
                'last_name' => $request->input('last_name'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password'))
            ]);

            return response($user, Response::HTTP_CREATED);
        }catch(\Throwable $th){
            Log::error('Error register a user: '. $th->getMessage());
            return response(['Error' =>'Unexpected Error'],Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
