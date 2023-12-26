<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use PhpParser\Node\Stmt\TryCatch;

class UserController extends Controller
{
    /**
     * retrieves all the users from the database
     */
    public function index()
    {
        return User::all();
    }

    /**
     * retrieves a specific user user from the dastabase
     */
    public function show($userId)
    {
        return User::find($userId);
    }

    /**
     * add a new user to the database
     */
    public function store(Request $userData)
    {

        try {
            $user = User::create([

                'first_name' => $userData['first_name'],
                'last_name' => $userData['last_name'],
                'email' => $userData['email'],
                'password' => Hash::make($userData['password'])
            ]);
            return response($user, HttpResponse::HTTP_CREATED);

        } catch (\Throwable $th) {
            Log::error('Error creating user: ' . $th->getMessage());
        }
    }

    /**
     * updates an existing user
     */
    public function update($userId, Request $userData)
    {
        $user = User::find($userId);

        if (!$user) {
            throw new ModelNotFoundException('User not found with ID: ' . $userId);
        }
        try {
            $user->update([
                'first_name' => $userData['first_name'],
                'last_name' => $userData['last_name'],
                'email' => $userData['email'],
                'password' => Hash::make($userData['password']),
            ]);
            return response($user, HttpResponse::HTTP_ACCEPTED);
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
}
