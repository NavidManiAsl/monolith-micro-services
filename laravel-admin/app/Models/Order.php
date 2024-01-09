<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }


    protected function total(): Attribute
    {

        return Attribute::make(

            get: fn() => $this->orderItems->sum(function ($item) {
                return $item->price * $item->quantity;
            })
        );
    }
}
