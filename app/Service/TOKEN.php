<?php

namespace App\Service;
use App\Service\JWT;


class TOKEN {

    public static function validateTimestamp($token) {
        $token = self::validateToken($token);
        if ($token != false && (now() - $token->timestamp < (120 * 60))) {
            return $token;
        }
        return false;
    }

    public static function decode($token) {
        $token = JWT::decode($token, Config('constants.jwt_key'));
        return $token;
    }

    public static function generate($data) {
        $str = rand();
        $attachedString = hash("sha256", $str);
        $token = JWT::encode($data, Config('constants.jwt_key'));
        return $token;
    }

}
