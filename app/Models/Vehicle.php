<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;


    protected $fillable = [
        'id',
        'driver_id',
        'vehicle_no',
        'is_active',
        'about',
        'driver_assigned_datetime',
        'vehicle_img',
        'rc_book_file',
        'insurance_file',
        'permit_file',
        'puc_file',
        'insurance_expiry',
        'permit_expiry',
        'puc_expiry',
    ];



    protected $table = 'tbl_vehicles';


    protected $hidden = [
        'updated_at',
    ];


    public function driver(){
        return $this->hasOne(Driver::class, 'id', 'driver_id');
    }


}
