<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SMSRemind extends Model
{
    protected $table = "send_sms_remind_log";
    protected $fillable = [
        'mobile', 'date', 'type', 'status',
    ];
}
