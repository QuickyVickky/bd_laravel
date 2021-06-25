<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'coupon_title',
        'coupon_description',
        'coupon_terms',
        'coupon_code',
        'discount_type',
        'start_datetime',
        'end_datetime',
        'applied_for',
        'user_id',
        'is_active',
        'min_order_value',
        'discount_value',
        'maximum_discount',
        'maximum_use_count',
        'used_count',
        'applied_for_platform',
        'maximum_use_count_peruser',
    ];

    protected $table = 'tbl_coupons';


    protected $hidden = [
        'admin_id',
        'updated_at',
    ];

    public function customer(){
        return $this->hasOne(Customer::class, 'id', 'user_id');
    }


}
