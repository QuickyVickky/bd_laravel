<?php

namespace App\Models;

use App\Models\AccessToken;
use Illuminate\Database\Eloquent\Model;
date_default_timezone_set('Asia/Kolkata');

class TokenValidorNot extends Model {

    function ValidToken($token,$usertype=1) {
        $data = AccessToken::where('token', $token)->where('usertype', $usertype)->first();
        if(!empty($data)) {
        	AccessToken::where('id', $data->id)->update(['updated_at' => date('Y-m-d H:i:s'), 'count_hits' => $data->count_hits + 1]);
        	return $data;
        }
        DeleteAccessToken();
        return false;
    }







}