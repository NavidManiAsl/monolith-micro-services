<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Throwable;
use App\Http\Resources\RoleResource;
use Illuminate\support\Facades\DB;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('view','roles');
        return RoleResource::collection(Role::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('edit','roles');
        try {

            $role = Role::create([
                'name' => $request->input('name')
            ]);
            if ($permissions = $request->input('permissions')) {
                $role->permissions()->attach($permissions);
            }

            return response()->json(new RoleResource($role), Response::HTTP_CREATED);
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
        Gate::authorize('view','roles');
        $role = Role::find($id);
        if (!$role) {
            return response(['Error' => 'Not found'], Response::HTTP_NOT_FOUND);
        }
        return response()->json(new RoleResource($role), Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        Gate::authorize('edit','roles');
        $role = Role::find($id);
        if (!$role) {
            return response(['Error' => 'Not found'], Response::HTTP_NOT_FOUND);
        }
        $currentPermissions = $role->permissions;
        try {

            $role->update([
                'name' => $request->input('name')
            ]);
            if ($newPermissions = $request->input('permissions')) {
                $permissions = array_merge($currentPermissions, $newPermissions);
                $role->permissions()->detach();

                $role->permissions()->attach($permissions);
            }

            return response()->json(new RoleResource($role), Response::HTTP_ACCEPTED);
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
        Gate::authorize('edit','roles');
        $role = Role::find($id);
        if (!$role) {
            return response(['Error' => 'Not found'], Response::HTTP_NOT_FOUND);
        }
        try {
            $role->permissions()->detach();
            $role->delete();
            return response(null, Response::HTTP_NO_CONTENT);
        } catch (Throwable $th) {
            Log::error('Error delete role: ' . $th->getMessage());
            return response(['Error' => 'Unexpected error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
