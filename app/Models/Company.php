<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'company_name',
        'email',
        'mobile',
        'is_active',
        'country',
        'state',
        'city',
        'pincode',
        'address',
        'landmark',
        'logo',
        'about_us',
        'privacy_policy',
        'terms_condition',
        'driver_emergency_no',
        
    ];

    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
    ];

    protected $table = 'company_configurations';


}
