<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccTransactionSubCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'transaction_type',
        'name',
        'name2',
        'details',
        'is_active',
        'details',
    ];

    protected $hidden = [
        'updated_at',
    ];


    protected $table = 'acc_transaction_subcategory';

}
