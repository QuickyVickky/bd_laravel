<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'transaction_uuid',
        'amount',
        'transaction_date',
        'description',
        'is_active',
        'admin_id',
        'is_editable',
        'transaction_type',
        'anybillno',
        'anybillno_document',
        'order_id',
        'vendor_id',
        'accountid_from',
        'accountid_transferredto',
        'transaction_subcategory_id',
        'vendor_id',
        'user_id',
        'invoice_id',
        'created_at',
        'updated_at',
        'payment_method',
        'notes',
        'is_reviewed',
    ];

    protected $hidden = [
        'updated_at',
    ];

    protected $table = 'acc_transactions';


    public function customer(){
        return $this->hasOne(Customer::class, 'id', 'user_id');
    }

    public function invoice(){
        return $this->hasOne(Invoice::class, 'id', 'invoice_id');
    }

    public function vendor(){
        return $this->hasOne(AccVendor::class, 'id', 'vendor_id');
    }

    public function order(){
        return $this->hasOne(Order::class, 'id', 'order_id');
    }

    public function accountbanks(){
        return $this->hasOne(AccAccountBanks::class, 'id', 'accountid_from');
    }
    public function accounttransferredto(){
        return $this->hasOne(AccAccountBanks::class, 'id', 'accountid_transferredto');
    }

    public function transaction_subcategory(){
        return $this->hasOne(AccTransactionSubCategory::class, 'id', 'transaction_subcategory_id');
    }


    




}
