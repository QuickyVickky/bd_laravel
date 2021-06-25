<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShortHelper extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'short',
        'details',
        'type',
        'is_active',
        'classhtml',
    ];

    protected $table = 'tbl_short_helper';


    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
        'is_active',
    ];


}
