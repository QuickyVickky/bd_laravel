<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccAccountCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'details',
        'is_active',
        'level',
        'path_to',
        'is_editable',
    ];

    protected $hidden = [
        'updated_at',
        'created_at',
    ];

    protected $table = 'acc_account_category';

}
