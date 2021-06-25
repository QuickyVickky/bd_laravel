<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionFeature extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'subscription_feature',
        'is_active',
    ];

    protected $table = 'tbl_subscription_features';


    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    


}
