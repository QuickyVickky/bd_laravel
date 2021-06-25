<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPurchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'user_id',
        'admin_id',
        'subscription_id',
        'subscription_shortname',
        'subscription_value',
        'subscription_validity_months',
        'subscription_title',
        'subscription_description',
        'subscription_terms',
        'discount_type',
        'is_active',
        'min_order_value',
        'discount_value_min',
        'discount_value_max',
        'maximum_discount_perorder',
        'maximum_discount_amount',
        'subscription_feature_ids',
        'purchase_datetime',
        'expired_datetime',
        'amount_used',
        'transaction_number',
        'is_manually_added',
        'notes',
    ];

    protected $table = 'tbl_subscription_purchase';


    protected $hidden = [
        'admin_id',
        'updated_at',
    ];

    


}
