<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'fullname',
        'email',
        'mobile',
        'is_active',
        'role',
        'about',
        'active_session',
        'assigned_role_management_ids',
        'notassigned_role_management_ids',
        'ipaddress',
    ];

    protected $table = 'admins';

    protected $hidden = [
        'password',
        'created_at',
        'updated_at',
        'active_session',
        'assigned_role_management_ids',
        'notassigned_role_management_ids',
        'is_active',
    ];
    
}
