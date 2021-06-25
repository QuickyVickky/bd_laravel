<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderArrange extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'arrangement_number',
        'order_id',
        'driver_id',
        'is_active',
        'arrangement_type',
        'driveraction_datetime',
        'orderaction_datetime',
        'is_completed',
        'difference_seconds',
        'between_meters',
        'between_seconds',
        'is_early_fulfilled',
        'origins_latitude',
        'origins_longitude',
        'destinations_latitude',
        'destinations_longitude',
    ];

    protected $table = 'tbl_driver_order_arrangement';


    protected $hidden = [
        'created_at',
        'updated_at',
    ];


    public function order(){
        return $this->hasOne(Order::class, 'id', 'order_id');
    }

    public function driver(){
        return $this->hasOne(Driver::class, 'id', 'driver_id');
    }

}
