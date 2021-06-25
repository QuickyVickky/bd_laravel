<?php

namespace App\Http\Controllers\Client\Api\driver;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\AccessToken;
use Illuminate\Support\Facades\Validator;
use App\Models\Driver;
use App\Models\OTP;
date_default_timezone_set('Asia/Kolkata');

class LoginController extends Controller
{

   /*------Send OTP to a valid mobile number for login or to Resend Call same Function driver-------*/
   public function sendOTPforLoginMobile(Request $request)
   {
   		try {
   		CreateApiLogs($request->all());

	   	if($request->header('token')!='' || $request->header('devicetype')=='' || !in_array($request->header('devicetype'), constants('devicetype'))){
	   		return response()->json([
                    'success' => constants('invalidToken.success'),
                    'message' => constants('invalidResponse.message'),
                    'data'    => constants('emptyData'),
                ], constants('invalidToken.statusCode'));
	   	}
	   	else if(!isset($request->mobile) || valid_mobile($request->mobile)==false){
	   		return response()->json([
                    'success' => constants('invalidResponse.success'),
                    'message' => "Please Provide a Valid Mobile Number.",
                    'data'    => constants('emptyData'),
                ], constants('invalidResponse.statusCode'));
	   	}
	   	else if(!isset($request->for_what) || !in_array($request->for_what, constants('for_what')) || $request->for_what!=constants('for_what.login')){
	   		return response()->json(constants('bad_request'), constants('invalidResponse.statusCode'));
	   	}

	   	$one =  Driver::where('mobile', $request->mobile)->whereNotNull('mobile')->where('is_active', constants('is_active_yes'))->first();


	   	if(!empty($one)){
	   		$returnData = $this->sendOTPtoMobile($request->mobile , constants('usertype.driver') , $request->for_what, $one->id , $request->header('devicetype'));

	   		if($returnData['success']==constants('validResponse.success')){
	   			$data = ['smsid' => $returnData['data'], 'for_what' => $request->for_what, 'currentdatetime' => time() ];

	   			return response()->json([
                    'success' => constants('validResponse.success'),
                    'message' => $returnData['message'],
                    'data'    => $data ,
                ], constants('validResponse.statusCode'));
	   		}
	   		else
	   		{
	   			return response()->json([
                    'success' => constants('invalidResponse.success'),
                    'message' => $returnData['message'],
                    'data'    => constants('emptyData'),
                ], constants('invalidResponse.statusCode'));
	   		}
	   	}
	   	else
	   	{
	   		return response()->json([
                'success' => constants('invalidResponse.success'),
                'message' => "This Mobile Number is Not Registered or Disabled.",
                'data'    => constants('emptyData'),
            ], constants('invalidResponse.statusCode'));

	   	}

	   	} catch(\Exception $e) {
            Log::error($e);
            return response()->json([
            'success' => constants('invalidResponse.success'),
            'message' => "Error! Something Went Wrong.",
            'data'    => constants('emptyData'),
            ], constants('invalidResponse.statusCode'));
        } 
   }


   protected function sendOTPtoMobile($mobile='',$for_whom=2, $for_what='', $driver_id=0, $devicetype=1)
   {
   		$start = date('Y-m-d H:i:s');
   		$from = date('Y-m-d H:i:s', strtotime('-10 minutes', strtotime($start)));
        $to = date('Y-m-d H:i:s', strtotime(date('Y-m-d') . ' +1 day'));

	   	if(valid_mobile($mobile)==false){
		   		return [
		   			'success' => constants('invalidResponse.success'),
		   			'message' => "Please Provide a Valid Mobile Number."
		   		];
		}
		else if($for_whom!=constants('usertype.driver') || !is_numeric($driver_id))
		{
			return ['success' => constants('invalidResponse.success'),'message' => "This is a Bad Request."];
		}
		else
		{
			$count =  OTP::where('user_id', $driver_id)->where('for_whom', $for_whom)->where('is_active', constants('is_active_yes'))->where('created_at', 'LIKE' , date('Y-m-d').'%')->count();

			if($count >= constants('max_otp_limit_count_reached')){
				return [
					'success' => constants('invalidResponse.success'),
					'message' => "You have Reached to Your OTP Limit, Please Try Again After Sometime."
				];
			}
			else
			{
				$newOTP = createOTP(4);
				$data = OTP::create(['otp' => $newOTP, 'user_id' => $driver_id, 'for_whom' => $for_whom, 'for_what' => $for_what, 'is_active' => constants('is_active_yes') , 'device_type' => $devicetype ]);

				sendMsg("Hi, Your ".constants('title')." OTP is ".$newOTP , $mobile);

				return [
	                    'success' => constants('validResponse.success'),
	                    'message' => "An OTP has been Sent to ".$mobile." from ".constants('title')."." ,
	                    'data'    => $data->id,
	                    'mobile'    => $mobile,
	                ];
			}
		}
   }

	/*------check otp for login and allow login from mobile-------*/
   public function checkOTPforLoginMobile(Request $request)
   {	
   		try {
   		CreateApiLogs($request->all());

	   	$validator = Validator::make($request->all(), [ 
	        'device_token' => 'required|string|min:24',
	    ]);   

	    if($validator->fails()) {          
	          return response()->json(constants('checkRequiredField'), constants('invalidResponse.statusCode'));
	    }

		  if(isset($request->current_latitude) && isset($request->current_longitude)){
			Driver::where('id', $request->get('driver_id'))->update(['current_latitude' => $request->current_latitude, 'current_longitude' => $request->current_longitude]);
		  }


   		if($request->header('devicetype')=='' || !in_array($request->header('devicetype'), constants('devicetype'))){
	   		return response()->json([
                    'success' => constants('invalidToken.success'),
                    'message' => constants('invalidResponse.message'),
                    'data'    => constants('emptyData'),
                ], constants('invalidToken.statusCode'));
	   	}
	   	else if(!isset($request->mobile) || valid_mobile($request->mobile)==false){
	   		return response()->json([
                    'success' => constants('invalidResponse.success'),
                    'message' => "Please Provide a Valid Mobile Number.",
                    'data'    => constants('emptyData'),
                ], constants('invalidResponse.statusCode'));
	   	}
	   	else if(!isset($request->otp) || strlen($request->otp)!=4 || !is_numeric($request->otp)){
	   		return response()->json([
                    'success' => constants('invalidResponse.success'),
                    'message' => "Please Provide a Valid OTP.",
                    'data'    => constants('emptyData'),
                ], constants('invalidResponse.statusCode'));
	   	}
	   	else if(!isset($request->smsid) || !isset($request->for_what) || !isset($request->currentdatetime) || !is_numeric($request->currentdatetime) || ($request->currentdatetime + constants('otp_time_out') < time()) ){
	   		return response()->json([
                    'success' => constants('invalidResponse.success'),
                    'message' => "Time Out.",
                    'data'    => constants('emptyData'),
                ], constants('invalidResponse.statusCode'));
	   	}
	   	else {

	   		$otpData =  OTP::where('id', $request->smsid)->where('for_what', $request->for_what)->where('for_whom', constants('usertype.driver'))->where('otp', $request->otp)->where('is_active', constants('is_active_yes'))->orderBy('id','DESC')->first();

	   		if(!empty($otpData)){
	   			$tokenLogin = GeneratetNewToken();

	   			AccessToken::where('usertype',constants('usertype.driver'))->where('user_id',$otpData->user_id)->delete();
	   			AccessToken::create(['usertype' => constants('usertype.driver'), 'user_id' => $otpData->user_id, 'devicetype' => $request->header('devicetype') , 'token' => $tokenLogin ]);

	   			OTP::where('user_id', $otpData->user_id)->where('for_whom', constants('usertype.driver'))->where('is_active', constants('is_active_yes'))->update(['is_active' => constants('is_active_no')]);
	   			DeleteOTP();

	   			Driver::where('device_token', $request->device_token)->whereNotNull('device_token')->update(['device_token' => NULL]);
	   			Driver::where('id', $otpData->user_id)->update(['device_token' => $request->device_token ]);

	   			CreateApiLogs([ "token" =>  $tokenLogin, "driver_id" => $otpData->user_id ]);

	   			return response()->json([
                    'success' => constants('validResponse.success'),
                    'message' => "Logged In Successfully.",
                    'data'    => [ "token" =>  $tokenLogin, "driver_id" => $otpData->user_id ],
                ], constants('validResponse.statusCode'));

	   		}
	   		else
	   		{
	   			return response()->json([
                    'success' => constants('invalidResponse.success'),
                    'message' => "OTP Not Matched or Expired, Please Try Again.",
                    'data'    => constants('emptyData'),
                ], constants('invalidResponse.statusCode'));

	   		}
	   	}

	   	} catch(\Exception $e) {
            Log::error($e);
            return response()->json([
            'success' => constants('invalidResponse.success'),
            'message' => "Error! Something Went Wrong.",
            'data'    => constants('emptyData'),
            ], constants('invalidResponse.statusCode'));
        } 
   }




   /*-------api for driver sample call for everything on splash screen-----------*/
   public function checkDriveAppRequest(Request $request)
   {
   			try { 
   		 $ch = curl_init(sendPath().constants("dir_name.app")."/"."driverApp.apk");
		 curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		 curl_setopt($ch, CURLOPT_HEADER, TRUE);
		 curl_setopt($ch, CURLOPT_NOBODY, TRUE);
		 $data = curl_exec($ch);
		 $size = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
		 curl_close($ch);

   		$dataArray = 
   		[
   			"latest_version" => 3,
   			"app_url" => sendPath().constants("dir_name.app")."/"."driverApp.apk",
   			"message" => "New Version Available.",
   			"download_from" => 0, /* 0- own folder, 1-- playstore */
   			"appsize" => @$size,
   			"if_check" => true,
   		];
   		
	   	return response()->json([
            'success' => constants('validResponse.success'),
            'message' => "",
            'data'    => $dataArray ,
            'instruction' => "0 for ownfolder download_from and 1 for google playstore."
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
