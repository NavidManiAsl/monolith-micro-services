<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use Dotenv\Loader\Resolver;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Throwable;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Role::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {

            $role = Role::create([
                'name' => $request->input('name')
            ]);

            return response($role, Response::HTTP_CREATED);
        } catch (Throwable $th) {
            Log::error('Error create role: ' . $th->getMessage());
            return response(['Error' => 'Unexpected error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $role = Role::find($id);
        if(!$role){
            return response(['Error' => 'Not found'], Response::HTTP_NOT_FOUND);
        }
        return response($role, Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $role = Role::find($id);
       
        if (!$role) {
            return response(['Error' => 'Not found'], Response::HTTP_NOT_FOUND);
        }
        try {

            $role->update([
                'name' => $request->input('name')
            ]);
            return response($role, Response::HTTP_ACCEPTED);
        } catch (Throwable $th) {
            Log::error('Error update role: ' . $th->getMessage());
            return response(['Error' => 'Unexpected error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $role = Role::find($id);
        if (!$role) {
            return response(['Error' => 'Not found'], Response::HTTP_NOT_FOUND);
        }
        try {
            $role->delete();
            return response(null, Response::HTTP_NO_CONTENT);
        } catch (Throwable $th) {
            Log::error('Error delete role: ' . $th->getMessage());
            return response(['Error' => 'Unexpected error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
