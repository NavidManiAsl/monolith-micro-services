<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Response as HttpResponse;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $orders = Order::with('orderItems')->paginate(10);

            return OrderResource::collection($orders);

        } catch (\Throwable $th) {
            Log::error('Error retrieving orders: ' . $th->getMessage());
            return response('Unexpected error', HttpResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {

            $order = Order::find($id);

            if (!$order) {
                return response('Not Found', HttpResponse::HTTP_NOT_FOUND);
            }

            return new OrderResource($order);
        } catch (\Throwable $th) {
            Log::error('Error retrieving orders: ' . $th->getMessage());
            return response('Unexpected error', HttpResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


}
