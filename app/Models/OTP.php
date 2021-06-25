<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OTP extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'otp',
        'user_id',
        'for_whom',
        'for_what',
        'is_active',
        'created_at',
        'device_type',
    ];

    protected $table = 'tbl_otp';

}
