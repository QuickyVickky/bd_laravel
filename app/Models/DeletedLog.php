<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeletedLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'data',
    ];

    protected $table = 'deleted_data_logs';


}
