<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'user_id',
        'country',
        'state',
        'city',
        'pincode',
        'address',
        'landmark',
        'longitude',
        'latitude',
        'address_type',
        'added_by',
        'updated_by',
        'is_default',
        'is_active',
        'contact_person_name',
        'contact_person_number',
        'transporter_name',
    ];

    protected $table = 'tbl_address';

    protected $hidden = [
        'user_id',
        'added_by',
        'updated_by',
        'created_at',
        'updated_at',
        'is_active',
    ];


    public function customer_address_type(){
        return $this->hasOne(ShortHelper::class, 'short', 'address_type')->where('is_active',constants('is_active_yes'))->where('type','address_type')->select('tbl_short_helper.short','tbl_short_helper.name'); 
    }
    
}
