<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'fullname',
        'email_address',
        'mobile',
        'is_active',
        'subject',
        'message',
        'devicetype',
        'ipaddress',
    ];

    protected $table = 'tbl_inquiry';

    
}
