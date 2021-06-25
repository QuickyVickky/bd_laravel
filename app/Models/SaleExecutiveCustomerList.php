<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleExecutiveCustomerList extends Model
{
    use HasFactory;


    protected $fillable = [
        'id',
        'user_id',
        'admin_id',
        'is_active',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];


    protected $table = 'tbl_salesexecutive_cutomerlist';




}
