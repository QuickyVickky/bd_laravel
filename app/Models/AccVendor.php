<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccVendor extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'fullname',
        'firstname',
        'lastname',
        'email',
        'mobile',
        'vendor_type',
        'vendor_about',
        'is_active',
        'admin_id',
        'country',
        'state',
        'city',
        'pincode',
        'address',
        'landmark',
    ];

    protected $hidden = [
        'updated_at',
    ];


    protected $table = 'acc_vendors';


    public function driver(){
        return $this->hasOne(Driver::class, 'vendor_id', 'id');
    }




}
