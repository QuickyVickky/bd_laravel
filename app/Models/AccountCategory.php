<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'image',
        'is_active',
        'level',
        'path_to',
        'type',
        'is_editable',
    ];

    protected $table = 'tbl_account_category';
    
}
