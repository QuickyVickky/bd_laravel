<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testonly extends Model
{
    use HasFactory;


    protected $fillable = [
        'id',
        'data',
    ];


    protected $table = 'testonly';

}
