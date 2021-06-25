<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletCreditList extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'wallet_amount',
        'wallet_credit',
        'is_active',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $table = 'tbl_wallet_creditlist';

}
