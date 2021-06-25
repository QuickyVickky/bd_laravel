<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DriverNotificationLog extends Model
{
    use HasFactory;

     protected $fillable = [
        'id',
        'title',
        'notification_text',
        'driver_id',
        'order_id',
        'created_at'
    ];

    protected $table = 'tbl_driver_notifications';


    public $timestamps = false;


}
