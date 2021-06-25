<?php

namespace App\Http\Controllers\Client\Api\customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\AccessToken;
use Illuminate\Support\Facades\Validator;
use App\Models\Customer;
use Illuminate\Support\Facades\Log;
date_default_timezone_set('Asia/Kolkata');


class MainApiController extends Controller
{
    

   /*------get order listing with filters also -------*/
   public function logOut(Request $request)
   {
   		try {
   		if(!isset($request->is_this_device) || !in_array($request->is_this_device, constants('login_device'))){
	   		return response()->json(constants('bad_request'), constants('invalidResponse.statusCode'));
	   	}
      DeleteAccessToken();

	   	if($request->is_this_device==constants('login_device.this_device')){
	   		AccessToken::where('token', $request->header('token'))->delete();
	   	}
	   	else
	   	{
	   		AccessToken::where('user_id', $request->get('user_id'))->where('usertype', constants('usertype.customer'))->delete();
	   	}

	   	return response()->json([
            'success' => constants('validResponse.success'),
            'message' => "Logged Out Successfully.",
            'data'    => [ "logout" => true, "homepageurl" => constants('CLIENTBASE_URL'), ],
            'instruction' => "Please Redirect to Homepage URL.",
        ], constants('validResponse.statusCode'));

        } catch(\Exception $e) {
            Log::error($e);
            return response()->json([
            'success' => constants('invalidResponse.success'),
            'message' => "Error! Something Went Wrong.",
            'data'    => constants('emptyData'),
            ], constants('invalidResponse.statusCode'));
        }
   }






}
