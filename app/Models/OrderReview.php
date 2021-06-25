<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'driver_star',
        'bigdaddy_service_star',
        'order_id',
        'user_id',
        'is_active',
        'subject',
        'message',
    ];

    protected $hidden = [
        'updated_at',
        'user_id',
    ];

    protected $table = 'tbl_reviews';
    
}
