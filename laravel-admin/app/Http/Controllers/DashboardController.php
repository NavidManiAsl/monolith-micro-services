<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Response as HttpResponse;

class DashboardController extends Controller
{
    public function chart(){


        try{
            $orders = DB::table('orders')
            ->join('order_items','orders.id', '=', 'order_items.order_id')
            ->selectRaw('orders.created_at as Date, sum(order_items.quantity*order_items.price) as Total')
            ->groupBy('Date')
            ->get();
            return response($orders) ;

        }catch(\Throwable $th){
            Log::error('error retrieve chart: '. $th->getMessage());
            return response('Unexpected error', HttpResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
