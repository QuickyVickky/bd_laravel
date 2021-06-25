<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\TokenValidorNot;

class CheckAccessTokenDriver
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $Token  = new TokenValidorNot;
        $UserData = $Token->ValidToken($request->header('token'),constants('usertype.driver'));

        if ($UserData == false) {
            return response()->json([
                    'success' => Config('constants.invalidToken.success'),
                    'message' => Config('constants.invalidToken.message'),
                    'data'    => Config('constants.emptyData'),
                ], Config('constants.invalidToken.statusCode'));
        }
        
        $request->attributes->add(['driver_id' => $UserData->user_id]);
        $request->attributes->add(['devicetype' => $UserData->devicetype]);
        $request->attributes->add(['usertype' => $UserData->usertype]);
        $request->attributes->add(['created_at' => $UserData->created_at]);
        return $next($request);
    }



    
}
