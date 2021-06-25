<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'fullname',
        'email',
        'mobile',
        'address',
        'pan_card',
        'password',
        'is_active',
        'status',
        'country',
        'state',
        'city',
        'pincode',
        'profile_pic',
        'current_location',
        'current_latitude',
        'current_longitude',
        'aadhar_card_file',
        'license_file',
        'license_expiry',
        'is_salary_based',
        'salary_amount',
        'pan_card_file',
        'device_token',
        'ipaddress',
        'vendor_id',
    ];

    protected $hidden = [
        'password',
        'device_token',
    ];

    protected $table = 'tbl_drivers';

    public function vehicle(){
        return $this->hasOne(Vehicle::class, 'driver_id', 'id');
    }

    public function driver_file(){
        return $this->hasMany(DriverFile::class, 'driver_id', 'id');
    }



    
}
