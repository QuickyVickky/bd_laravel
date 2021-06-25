<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesExecutive extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'fullname',
        'email',
        'mobile',
        'is_active',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $table = 'tbl_salesexecutive';


}
