<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderLog extends Model
{
    use HasFactory;

     protected $fillable = [
        'id',
        'logs',
        'order_id',
        'type',
    ];

    protected $hidden = [
        'type',
    ];

    protected $table = 'tbl_order_logs';

    public function order(){
        return $this->hasOne(Order::class, 'id', 'order_id');
    }


}
