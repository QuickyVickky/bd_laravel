<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'firstname',
        'lastname',
        'fullname',
        'business_name',
        'transporter_name',
        'GST_number',
        'customer_type',
        'email',
        'mobile',
        'added_by',
        'updated_by',
        'password',
        'is_active',
        'pan_no',
        'business_type',
        'ownership',
        'profile_pic',
        'customer_gst_exempted_type',
        'wallet_credit',
        'ipaddress',
        'user_paymentbill_type',
    ];

    protected $table = 'tbl_users';

    protected $hidden = [
        'password',
        'added_by',
        'updated_by',
        'created_at',
        'updated_at',
        'is_active',
        'device_token',
    ];

    public function customerAddress(){
        return $this->hasMany(CustomerAddress::class, 'user_id', 'id');
    }

    public function customerAddressFirst(){
        return $this->hasOne(CustomerAddress::class, 'user_id', 'id');
    }

    public function lastSubscriptionPurchasedValid(){
        return $this->hasOne(SubscriptionPurchase::class, 'subscription_id', 'id')->where('amount_used','<', 'maximum_discount_amount')->where('expired_datetime', '>', date('Y-m-d H:i:s'));
    }
    
}
