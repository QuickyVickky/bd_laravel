<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccAccountBanks extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'account_category_id',
        'name',
        'account_id',
        'description',
        'is_active',
        'admin_id',
        'is_editable',
    ];

    protected $hidden = [
        'updated_at',
        'created_at',
    ];


    protected $table = 'acc_accounts_or_banks';

}
