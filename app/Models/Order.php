<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'landlord_id', 'house_id', 'status', 'startdate', 'enddate', 'order_owner', 'owner_phone', 'number', 'sum_day', 'sum_people', 'sum_price', 'livers',
    ];
}
