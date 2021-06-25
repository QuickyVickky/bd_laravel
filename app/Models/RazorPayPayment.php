<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RazorPayPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'razorpay_order_id',
        'amount',
        'order_id',
        'is_active',
        'created_at',
        'updated_at',
        'payment_status',
        'razorpay_payment_id',
        'razorpay_signature',
        'payment_type',
        'notes',
    ];

    protected $table = 'razorpay_payments';

    public function order(){
        return $this->hasOne(Order::class, 'id', 'order_id')->where('is_active', constants('is_active_yes'));
    }

   

    




}
