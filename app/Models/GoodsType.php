<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoodsType extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'details',
        'img',
        'is_active',
        'is_editable',
    ];


    protected $hidden = [
        'created_at',
        'updated_at',
        'is_active',
        'is_editable',
    ];

    protected $table = 'tbl_goods_type';

    
}
