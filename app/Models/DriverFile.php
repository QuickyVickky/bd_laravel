<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DriverFile extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'id',
        'img',
        'driver_id',
        'img_type_name',
        'short_helper_name',
        'is_active',
        'if_expiry_date',
    ];

    protected $table = 'tbl_drivers_files';
}
