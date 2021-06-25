<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountManage extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'amount',
        'account_datetime',
        'is_active',
        'comments',
        'type',
        'account_subcategory_id',
        'added_by',
        'anybillno',
        'anybillno_document',
        'order_id',
    ];

    protected $table = 'tbl_account_manage';

    
}
