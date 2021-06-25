<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleManage extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'main_url',
        'details',
        'is_active',
        'level',
        'path_to',
        'any_svg',
        'any_html',
        'any_order',
        'remove_class',
        'has_submenu',
        'one_url',
    ];

    protected $table = 'tbl_role_management';

}
