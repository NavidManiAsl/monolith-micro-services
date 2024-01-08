<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Order::factory()
        ->count(30)
        ->create()
        ->each(function (Order $order) {
            OrderItem::factory()
            ->count(random_int(1,5))
            ->create([
                'order_id' => $order->id,
            ]);
        });
    }
}
