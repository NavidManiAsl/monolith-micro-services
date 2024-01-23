<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Response as HttpResponse;
use App\Http\Resources\PermissionResource;
use Illuminate\Support\Facades\Log;


class PermissionController extends Controller
{
        /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $permissions = Permission::paginate(10);
            return PermissionResource::collection($permissions);
        } catch (\Throwable $th) {
            Log::error('Error retrieving permissions: ' . $th->getMessage());
            return response('Unexpected Error', HttpResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
