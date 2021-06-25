<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TempToken extends Model {

    protected $table      = 'temp_token';

    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'user_id',
        'usertype',
        'devicetype',
        'token',
        'created_at',
    ];


    public $timestamps = false;




}
