<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerNotificationLog extends Model
{
    use HasFactory;

     protected $fillable = [
        'id',
        'notification_text',
        'user_id',
        'order_id',
        'classhtml',
        'created_at'
    ];

    protected $table = 'tbl_customer_notifications';


    public $timestamps = false;


}
