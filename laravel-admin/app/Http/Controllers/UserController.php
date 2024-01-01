<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateInfoRequest;
use App\Http\Requests\UserUpdatePasswordRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;


class UserController extends Controller
{
    /**
     * retrieves all the users from the database
     */
    public function index()
    {
        $users = User::paginate(10);
        return UserResource::collection($users);
    }

    /**
     * retrieves a specific user user from the dastabase
     */
    public function show($userId)
    {
        $user = User::find($userId);
        return new UserResource($user);
    }

    /**
     * add a new user to the database
     */
    public function store(UserCreateRequest $userData)
    {

        try {
            $user = User::create([

                'first_name' => $userData['first_name'],
                'last_name' => $userData['last_name'],
                'email' => $userData['email'],
                'password' => Hash::make('password'),
                'role_id' => $userData['role_id']
            ]);
            return response(new UserResource($user), HttpResponse::HTTP_CREATED);

        } catch (\Throwable $th) {
            Log::error('Error creating user: ' . $th->getMessage());
            return response('Unexpected error', HttpResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

    }

    /**
     * updates an existing user
     */
    public function update($userId, UserUpdateRequest $userData)
    {
        $user = User::find($userId);

        if (!$user) {
            throw new ModelNotFoundException('User not found with ID: ' . $userId);
        }
        try {
            $user->update($userData->only('first_name', 'last_name', 'email', 'role_id'));

            return response(new UserResource($user), HttpResponse::HTTP_ACCEPTED);
        } catch (\Throwable $th) {
            Log::error('Error updating user: ' . $th->getMessage());
        }


    }

    /**
     * delete a user from the database
     */
    public function destroy($userId)
    {
        $user = User::find($userId);

        if (!$user) {
            throw new ModelNotFoundException('User not found with ID: ' . $userId);
        }

        try {
            $user->delete();
            return response(null, HttpResponse::HTTP_NO_CONTENT);
        } catch (\Throwable $th) {
            Log::error('Error deleting user: ' . $th->getMessage());
        }
    }

    /**
     * Show user information
     */
    public function user()
    {
        return Auth::user();
    }

    /**
     * Authorized user can update its personal info
     */
    public function updateInfo(UserUpdateInfoRequest $request)
    {
        $user = Auth::user();


        try {
            $user->update($request->only('first_name', 'last_name', 'email'));

            return response(new UserResource($user), HttpResponse::HTTP_ACCEPTED);
        } catch (\Throwable $th) {
            Log::error('Error updating user: ' . $th->getMessage());
        }
    }

    /**
     * Authorized user can update its password
     */
    public function updatePassword(UserUpdatePasswordRequest $request)
    {
        $user = Auth::user();
        try {
            $user->update([
                'password' => Hash::make($request->input('password'))
            ]);
            return response('password has been updated', HttpResponse::HTTP_ACCEPTED);
        } catch (\Throwable $th) {
            Log::error('Error updating password' . $th->getMessage());
        }
    }
}
