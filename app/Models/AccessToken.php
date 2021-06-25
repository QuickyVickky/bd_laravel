<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccessToken extends Model {
    protected $table      = 'access_token';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'user_id',
        'usertype',
        'devicetype',
        'token',
        'created_at',
        'updated_at',
        'count_hits',
    ];
}
