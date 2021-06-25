<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'transaction_number',
        'user_id',
        'order_id',
        'transaction_datetime',
        'transaction_amount',
        'transaction_credit',
        'transaction_type',
        'is_active',
        'notes',
        'is_manually_added',
        'admin_id',
    ];

    protected $table = 'tbl_wallet_transaction';

}
