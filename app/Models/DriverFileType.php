<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DriverFileType extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'short',
        'details',
        'is_active',
        'is_exclude',
        'ask_expiry',
        'is_multiple',
    ];

    protected $table = 'tbl_driver_files_type';
    
}
