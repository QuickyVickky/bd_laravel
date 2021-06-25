<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReasonFor extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'details',
        'type',
        'is_active',
    ];

    protected $table = 'tbl_reasonfor';

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
    
}
