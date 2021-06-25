<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
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
        'is_default_bestvalue',
    ];

    protected $table = 'tbl_subscriptions';


    protected $hidden = [
        'admin_id',
        'updated_at',
    ];



    


}
