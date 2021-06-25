<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'invoice_file',
        'invoice_number',
        'is_active',
    ];

    protected $table = 'tbl_invoice';

    protected $hidden = [
        'updated_at',
    ];

    public function order(){
        return $this->hasMany(Order::class, 'invoice_id', 'id');
    }

    
    
}
