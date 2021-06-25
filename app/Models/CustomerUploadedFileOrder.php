<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerUploadedFileOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'lrfile',
        'user_id',
        'is_active',
    ];

    protected $table = 'tbl_customer_uploaded_files';

    
    
}
