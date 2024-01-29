<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Gate;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('view','orders');
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
        Gate::authorize('view','orders');
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

    public function export()
    {
        Gate::authorize('view','orders');
        $headers = [
            'Content-Type' => 'text/csv'
        ];
        $callback = function () {
            $file = fopen('php://output', 'w');
            $orders = Order::with('orderItems')->get();
            fputcsv($file, ['ID', 'NAME', 'EMAIL', 'PRODUCT TITLE', 'PRICE', 'QUANTITY']);
            foreach ($orders as $order) {
                foreach ($order->orderItems as $orderItem) {
                    fputcsv($file, [$order->id, $order->name, $order->email, $orderItem->title, $orderItem->price, $orderItem->quantity]);
                }
            }
        };

        return response()->streamDownload($callback, 'orders.csv', $headers, );


    }


}
