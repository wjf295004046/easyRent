<?php

namespace App\Models\House;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'user_id', 'house_id', 'order_id', 'landlord_id', 'comment_type'
    ];
}
