<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SMS extends Model
{
    protected $table = "send_sms_log";
    protected $fillable = [
        'mobile', 'verify', 'type', 'status','result'
    ];
}
