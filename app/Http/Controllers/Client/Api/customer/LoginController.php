<?php

namespace App\Http\Controllers\Client\Api\customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\AccessToken;
use Illuminate\Support\Facades\Validator;
use App\Models\Customer;
use App\Models\OTP;
use Illuminate\Support\Facades\Log;
date_default_timezone_set('Asia/Kolkata');


class LoginController extends Controller
{

	/*private $tokenData;

	public function __construct(Request $request) {
    	$token = ($request->header('tokendata')!='' ? $request->header('tokendata') : NULL);
		$this->tokenData = jwtCustomTokenDecode($token);
    }*/


    public function __construct()
    {
        ini_set('max_execution_time', 200);
    }


   /*------Send OTP to a valid mobile number for login or to Resend Call same Function -------*/
   public function sendOTPforLoginMobile(Request $request)
   {
   		try {

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


	   	$one =  Customer::where('mobile', $request->mobile)->where('mobile','!=', '')->where('is_active', constants('is_active_yes'))->first();


	   	if(!empty($one)){
	   		$returnData = $this->sendOTPtoMobile($request->mobile , constants('usertype.customer') , $request->for_what, $one->id , $request->header('devicetype'));

	   		if($returnData['success']==constants('validResponse.success')){
	   			$data = ['smsid' => $returnData['data'], 'for_what' => $request->for_what, 'currentdatetime' => time() ];
	   			$token = $data; /*-jwtCustomTokenEncode($data);-*/

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
                    'message' => "This Mobile Number is Not Registered with Us.",
                    'data'    => constants('emptyData'),
                ], constants('invalidResponse.statusCode'));

	   	}
	   	} catch (Exception $e) {
            Log::error($e);
            return response()->json([
            'success' => constants('invalidResponse.success'),
            'message' => "Error! Something Went Wrong.",
            'data'    => constants('emptyData'),
            ], constants('invalidResponse.statusCode'));
        }
   }


   protected function sendOTPtoMobile($mobile='',$for_whom=1, $for_what='', $user_id='', $devicetype=1)
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
		else if($for_whom!=constants('usertype.customer') || !is_numeric($user_id))
		{
			return ['success' => constants('invalidResponse.success'),'message' => "This is a Bad Request."];
		}
		else
		{
			$count =  OTP::where('user_id', $user_id)->where('for_whom', $for_whom)->where('is_active', constants('is_active_yes'))->where('created_at', 'LIKE' , date('Y-m-d').'%')->count();

			if($count >= constants('max_otp_limit_count_reached')){
				return [
					'success' => constants('invalidResponse.success'),
					'message' => "You have Reached to Your OTP Limit, Please Try Again After Sometime."
				];
			}
			else
			{

                $fromTimeAgo = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . ' -60 minutes'));
                $toTimeAhead = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . ' +30 minutes'));
                $countInLastnHour =  OTP::where('user_id', $user_id)->where('for_whom', $for_whom)->where('created_at', 'LIKE' , date('Y-m-d').'%')->whereBetween('created_at', [$fromTimeAgo, $toTimeAhead])->where('is_active', constants('is_active_yes'))->count();
                

                if($countInLastnHour > 2){ 
                    $countInLastnHour = $countInLastnHour - 2;
                    $lastDateTimeInLastnHour =  OTP::where('user_id', $user_id)->where('for_whom', $for_whom)->where('created_at', 'LIKE' , date('Y-m-d').'%')->whereBetween('created_at', [$fromTimeAgo, $toTimeAhead])->where('is_active', constants('is_active_yes'))->orderBy('id','DESC')->first(['created_at']);

                    if(isset($lastDateTimeInLastnHour->created_at) && date('Y-m-d H:i:s', strtotime($lastDateTimeInLastnHour->created_at . ' + '.$countInLastnHour.' minutes')) > date('Y-m-d H:i:s')){
                        $startDateTime = strtotime(date('Y-m-d H:i:s', strtotime($lastDateTimeInLastnHour->created_at . ' + '.$countInLastnHour.' minutes')));
                        $endDateTime = strtotime(date('Y-m-d H:i:s'));
                        $seconds = abs(intval($startDateTime - $endDateTime));

                        return [
                            'success' => constants('invalidResponse.success'),
                            'message' => "Please Wait, ".secToHR($seconds)." Before Resend OTP."
                        ];
                    }
                }


				$newOTP = createOTP(4);
				$data = OTP::create(['otp' => $newOTP, 'user_id' => $user_id, 'for_whom' => $for_whom, 'for_what' => $for_what, 'is_active' => constants('is_active_yes') , 'device_type' => $devicetype ]);

				//sendMsg("Your ".constants('title')." OTP for verification is ".$newOTP , $mobile);

				return [
	                    'success' => constants('validResponse.success'),
	                    'message' => "OTP has been Sent to ".$mobile ,
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

	   		$otpData =  OTP::where('id', $request->smsid)->where('for_what', $request->for_what)->where('for_whom', constants('usertype.customer'))->where('otp', $request->otp)->where('is_active', constants('is_active_yes'))->orderBy('id','DESC')->first();
            DeleteAccessToken();

	   		if(!empty($otpData)){
	   			
	   			$tokenLogin = GeneratetNewToken();
	   			AccessToken::create(['usertype' => constants('usertype.customer'), 'user_id' => $otpData->user_id, 'devicetype' => $request->header('devicetype') , 'token' => $tokenLogin ]);
	   			OTP::where('user_id', $otpData->user_id)->where('for_whom', constants('usertype.customer'))->where('is_active', constants('is_active_yes'))->update(['is_active' => constants('is_active_no')]);
	   			DeleteOTP();

	   			if(isset($request->device_token)){
	   				Customer::where('id', $otpData->user_id)->update(['device_token' => $request->device_token ]);
	   			}
                $dataCustomer = Customer::where('id', $otpData->user_id)->first(['fullname','business_name','id','customer_type','mobile','email','device_token','wallet_credit']);

	   			return response()->json([
                    'success' => constants('validResponse.success'),
                    'message' => "Logged In Successfully.",
                    'data'    => $tokenLogin,
                    'customer'    => $dataCustomer,
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
	   	} catch (Exception $e) {
            Log::error($e);
            return response()->json([
            'success' => constants('invalidResponse.success'),
            'message' => "Error! Something Went Wrong.",
            'data'    => constants('emptyData'),
            ], constants('invalidResponse.statusCode'));
        }
   }

   /*------Login with a valid email address and password -------*/
   public function loginWithEmail(Request $request)
   {
   		try {
   		if($request->header('token')!='' || $request->header('devicetype')=='' || !in_array($request->header('devicetype'), constants('devicetype'))){
	   		return response()->json([
                    'success' => constants('invalidToken.success'),
                    'message' => constants('invalidResponse.message'),
                    'data'    => constants('emptyData'),
                ], constants('invalidToken.statusCode'));
	   	}
	   	else if(!isset($request->email) || valid_email($request->email)==false){
	   		return response()->json([
                    'success' => constants('invalidResponse.success'),
                    'message' => "Please Provide a Valid Email Address.",
                    'data'    => constants('emptyData'),
                ], constants('invalidResponse.statusCode'));
	   	}
	   	else if(!isset($request->password) || strlen(trim($request->password))<1){
	   		return response()->json([
                    'success' => constants('invalidResponse.success'),
                    'message' => "Please Provide a Valid Password.",
                    'data'    => constants('emptyData'),
                ], constants('invalidResponse.statusCode'));
	   	}
	   	else
	   	{
	   		$customerData = Customer::where('email', $request->email)->where('email','!=','')->where('is_active', constants('is_active_yes'))->first();
            DeleteAccessToken();

	   		if(!empty($customerData)){
	   			if(!password_verify($request->password, $customerData->password)) 
		        {
		        	return response()->json([
                    'success' => constants('invalidResponse.success'),
                    'message' => "Please Check, Email or Password is Wrong.",
                    'data'    => constants('emptyData'),
                	], constants('invalidResponse.statusCode'));
		        }
		        else
		        {		
		        	
		        	$tokenLogin = GeneratetNewToken();
		        	AccessToken::create(['usertype' => constants('usertype.customer'), 'user_id' => $customerData->id, 'devicetype' => $request->header('devicetype') , 'token' => $tokenLogin ]);

		        	if(isset($request->device_token)){
	   					Customer::where('id', $customerData->id)->update(['device_token' => $request->device_token ]);
	   				}
                    $dataCustomer = Customer::where('id', $customerData->id)->first(['fullname','business_name','id','customer_type','mobile','email','device_token','wallet_credit']);

		        	return response()->json([
                    'success' => constants('validResponse.success'),
                    'message' => "Logged In Successfully.",
                    'data'    => $tokenLogin,
                    'customer'    => $dataCustomer,
                	], constants('validResponse.statusCode'));
		        }
	   		}
	   		else
	   		{
	   			return response()->json([
                    'success' => constants('invalidResponse.success'),
                    'message' => "This Email is Not Registered With Us.",
                    'data'    => constants('emptyData'),
                ], constants('invalidResponse.statusCode'));
	   		}
	   	}
	   	} catch (Exception $e) {
            Log::error($e);
            return response()->json([
            'success' => constants('invalidResponse.success'),
            'message' => "Error! Something Went Wrong.",
            'data'    => constants('emptyData'),
            ], constants('invalidResponse.statusCode'));
        }
   }

   /*------Send OTP to a valid mobile number for sign-up or to Resend Call same Function -------*/
   public function sendOTPforSignUpMobile(Request $request)
   {
   		try{ 
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
	   	else if(!isset($request->for_what) || !in_array($request->for_what, constants('for_what')) || $request->for_what!=constants('for_what.register')){
	   		return response()->json(constants('bad_request'), constants('invalidResponse.statusCode'));
	   	}

	   	$one =  Customer::where('mobile', $request->mobile)->where('mobile','!=', '')->first();

	   	if(empty($one)){
	   		$returnData = $this->sendOTPtoMobile($request->mobile , constants('usertype.customer') , $request->for_what, constants('guest_user_id') , $request->header('devicetype'));

	   		if($returnData['success']==constants('validResponse.success')){
	   			$data = ['smsid' => $returnData['data'], 'for_what' => $request->for_what, 'currentdatetime' => time(), 'mobile' => $request->mobile, ];

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
                    'message' => "This Mobile Number is Already Registered with Us, Please Login.",
                    'data'    => constants('emptyData'),
                ], constants('invalidResponse.statusCode'));

	   	}
	   	} catch (Exception $e) {
            Log::error($e);
            return response()->json([
            'success' => constants('invalidResponse.success'),
            'message' => "Error! Something Went Wrong.",
            'data'    => constants('emptyData'),
            ], constants('invalidResponse.statusCode'));
        }
   }

   /*------check otp for signup and allow signup from mobile-------*/
   public function checkOTPforSignUpMobile(Request $request)
   {
   		try{
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


	   		$one =  Customer::where('mobile', $request->mobile)->where('mobile','!=', '')->first();

	   		if(!empty($one)){
	   			return response()->json([
                    'success' => constants('invalidResponse.success'),
                    'message' => "This Mobile Number is Already Registered with Us, Please Login.",
                    'data'    => constants('emptyData'),
                ], constants('invalidResponse.statusCode'));
	   		}

	   		$otpData =  OTP::where('id', $request->smsid)->where('for_what', $request->for_what)->where('otp', $request->otp)->where('is_active', constants('is_active_yes'))->orderBy('id','DESC')->first();

	   		if(!empty($otpData)){

	   			OTP::where('user_id', constants('guest_user_id'))->where('for_whom', constants('usertype.customer'))->where('is_active', constants('is_active_yes'))->update(['is_active' => constants('is_active_no')]);
	   			DeleteOTP();

	   			$data = ['user_id' => constants('guest_user_id'), 'mobile' => $request->mobile, 'currentdatetime' => time() ];

	   			return response()->json([
                    'success' => constants('validResponse.success'),
                    'message' => "",
                    'data'    => $data,
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
	   	} catch (Exception $e) {
            Log::error($e);
            return response()->json([
            'success' => constants('invalidResponse.success'),
            'message' => "Error! Something Went Wrong.",
            'data'    => constants('emptyData'),
            ], constants('invalidResponse.statusCode'));
        }
   }

   /*------forword to fill signup details of customer-------*/
   public function signUpWithMobile(Request $request)
   {
   		try{
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
	   	else if(!isset($request->transporter_fullname) || strlen($request->transporter_fullname)<2){
	   		return response()->json([
                    'success' => constants('invalidResponse.success'),
                    'message' => "Please Check All Required Fields.",
                    'data'    => constants('emptyData'),
                ], constants('invalidResponse.statusCode'));
	   	}
	   	else if(!isset($request->email) || valid_email($request->email)==false){
	   		return response()->json([
                    'success' => constants('invalidResponse.success'),
                    'message' => "Please Provide a Valid Email Address.",
                    'data'    => constants('emptyData'),
                ], constants('invalidResponse.statusCode'));
	   	}
	   	else if(!isset($request->password) || valid_password($request->password)==false){
	   		return response()->json([
                    'success' => constants('invalidResponse.success'),
                    'message' => "Please Type a Valid Password Minimum 6 Digits.",
                    'data'    => constants('emptyData'),
                ], constants('invalidResponse.statusCode'));
	   	}
	   	else if(!isset($request->confirm_password) || $request->confirm_password!=$request->password){
	   		return response()->json([
                    'success' => constants('invalidResponse.success'),
                    'message' => "Password and Confirm Password must be Same.",
                    'data'    => constants('emptyData'),
                ], constants('invalidResponse.statusCode'));
	   	}
	   	else if(!isset($request->customer_type) || !in_array($request->customer_type, constants('customer_type')) ){
	   		return response()->json(constants('bad_request'), constants('invalidResponse.statusCode'));
	   	}
	   	else {

	   		if($request->customer_type==constants('customer_type.Transporter')){
	   			if(!isset($request->gst_number) || valid_gstNo($request->gst_number)==false){
			   		return response()->json([
		                    'success' => constants('invalidResponse.success'),
		                    'message' => "Please Provide a Valid GST Number.",
		                    'data'    => constants('emptyData'),
		                ], constants('invalidResponse.statusCode'));
	   			}
	   		}
	   		else if($request->customer_type==constants('customer_type.Business')){
	   			if(!isset($request->customer_gst_exempted_type) || !in_array($request->customer_gst_exempted_type, constants('customer_gst_exempted_type'))){
			   		return response()->json(constants('bad_request'), constants('invalidResponse.statusCode'));
	   			}
	   			else if($request->customer_gst_exempted_type==constants('customer_gst_exempted_type.yes') 
	   				&& ( !isset($request->pan_no) || valid_panNo($request->pan_no)==false)) {
			   		return response()->json([
		                    'success' => constants('invalidResponse.success'),
		                    'message' => "Please Provide a Valid PAN Number.",
		                    'data'    => constants('emptyData'),
		                ], constants('invalidResponse.statusCode'));
	   			}
	   			else if($request->customer_gst_exempted_type==constants('customer_gst_exempted_type.no') && (!isset($request->gst_number) || valid_gstNo($request->gst_number)==false)){
			   		return response()->json([
		                    'success' => constants('invalidResponse.success'),
		                    'message' => "Please Provide a Valid GST Number.",
		                    'data'    => constants('emptyData'),
		                ], constants('invalidResponse.statusCode'));
	   			}
	   		}
	   		else if($request->customer_type==constants('customer_type.Individual')){
	   			if(!isset($request->pan_no) || valid_panNo($request->pan_no)==false){
			   		return response()->json([
		                    'success' => constants('invalidResponse.success'),
		                    'message' => "Please Provide a Valid PAN Number.",
		                    'data'    => constants('emptyData'),
		                ], constants('invalidResponse.statusCode'));
	   			}
	   		}
	   	}

	   	$mobileNumber = $request->mobile;
	   	$emailAddress = $request->email;
	   	$gstNo = isset($request->gst_number) ? $request->gst_number : NULL;
	   	$panNo = isset($request->pan_no) ? $request->pan_no : NULL;


	   		$count =  Customer::where('id','>', 0)
	   	    ->where(function($queryEmail) use ($emailAddress) {
                    $queryEmail->where('email', $emailAddress)->whereNotNull('email');
            })
            ->orWhere(function($queryMobile) use ($mobileNumber) {
                    $queryMobile->where('mobile', $mobileNumber)->whereNotNull('mobile');
            })
            ->orWhere(function($queryGst) use ($gstNo) {
                    $queryGst->where('GST_number', $gstNo)->whereNotNull('GST_number');
            })
            ->orWhere(function($queryPan) use ($panNo) {
                    $queryPan->where('pan_no', $panNo)->whereNotNull('pan_no');
            })->count();

            if($count > 0){
            	return response()->json([
                    'success' => constants('invalidResponse.success'),
                    'message' => "This Mobile Number or Email Address or GST Number or PAN Number is Already Registered with Us, Please Check.",
                    'data'    => constants('emptyData'),
                ], constants('invalidResponse.statusCode'));
            }

	   		$insertData = [
                'firstname' => NULL,
                'lastname' => NULL,
                'fullname' => $request->transporter_fullname,
                'customer_gst_exempted_type' => isset($request->customer_gst_exempted_type) ? $request->customer_gst_exempted_type : NULL,
                'pan_no' => isset($request->pan_no) ? $request->pan_no : NULL,
                'GST_number' => isset($request->gst_number) ? $request->gst_number : NULL,
                'business_type' => isset($request->business_type) ? $request->business_type : NULL,
                'customer_type' => $request->customer_type,
                'ownership' => isset($request->ownership) ? $request->ownership : NULL,
                'business_name' => ($request->customer_type==constants("customer_type.Individual")) ? NULL : $request->transporter_fullname,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'password' => bcrypt($request->password),
                'is_active' => constants('is_active_yes'),
                'user_paymentbill_type' => 0,
                'wallet_credit' => 0,
                ];


            $dataLast = Customer::create($insertData);

            if($dataLast->id>0){
  
            	$tokenLogin = GeneratetNewToken();

	   			AccessToken::create(['usertype' => constants('usertype.customer'), 'user_id' => $dataLast->id, 'devicetype' => $request->header('devicetype'), 'token' => $tokenLogin ]);
	   			OTP::where('user_id', $dataLast->id)->where('is_active', constants('is_active_yes'))->update(['is_active' => constants('is_active_no')]);

	   			if(isset($request->device_token)){
	   				Customer::where('id', $dataLast->id)->update(['device_token' => $request->device_token ]);
	   			}

                $dataCustomer = Customer::where('id', $dataLast->id)->first(['fullname','business_name','id','customer_type','mobile','email','device_token','wallet_credit']);

	   			return response()->json([
                    'success' => constants('validResponse.success'),
                    'message' => "Welcome, You are Logged In Successfully.",
                    'data'    => $tokenLogin,
                    'customer'    => $dataCustomer,
                ], constants('validResponse.statusCode'));
            }
            else
            {
            	return response()->json(constants('somethingWentWrong'), constants('invalidResponse.statusCode'));
            }
        } catch (Exception $e) {
            Log::error($e);
            return response()->json([
            'success' => constants('invalidResponse.success'),
            'message' => "Error! Something Went Wrong.",
            'data'    => constants('emptyData'),
            ], constants('invalidResponse.statusCode'));
        }
   }

   /*------Send OTP to a registered valid email address for forgot password or to Resend Call same Function -------*/
   public function sendOTPforForgotPasswordEmail(Request $request)
   {
   		try{
	   	if($request->header('token')!='' || $request->header('devicetype')=='' || !in_array($request->header('devicetype'), constants('devicetype'))){
	   		return response()->json([
                    'success' => constants('invalidToken.success'),
                    'message' => constants('invalidResponse.message'),
                    'data'    => constants('emptyData'),
                ], constants('invalidToken.statusCode'));
	   	}
	   	else if(!isset($request->email) || valid_email($request->email)==false){
	   		return response()->json([
                    'success' => constants('invalidResponse.success'),
                    'message' => "Please Provide a Valid Email Address.",
                    'data'    => constants('emptyData'),
                ], constants('invalidResponse.statusCode'));
	   	}
	   	else if(!isset($request->for_what) || !in_array($request->for_what, constants('for_what')) || $request->for_what!=constants('for_what.forgot')){
	   		return response()->json(constants('bad_request'), constants('invalidResponse.statusCode'));
	   	}


	   	$one =  Customer::where('email', trim($request->email))->where('email','!=', '')->where('is_active', constants('is_active_yes'))->first();


	   	if(!empty($one)){
	   		$returnData = $this->sendOTPtoEmail($request->email , constants('usertype.customer') , $request->for_what, $one->id , $request->header('devicetype'));

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
                    'message' => "This Email Address is Not Registered with Us.",
                    'data'    => constants('emptyData'),
                ], constants('invalidResponse.statusCode'));

	   	}
	   	} catch (Exception $e) {
            Log::error($e);
            return response()->json([
            'success' => constants('invalidResponse.success'),
            'message' => "Error! Something Went Wrong.",
            'data'    => constants('emptyData'),
            ], constants('invalidResponse.statusCode'));
        }
   }

   protected function sendOTPtoEmail($email='',$for_whom=1, $for_what='', $user_id='', $devicetype=1)
   {
   		$start = date('Y-m-d H:i:s');
   		$from = date('Y-m-d H:i:s', strtotime('-10 minutes', strtotime($start)));
        $to = date('Y-m-d H:i:s', strtotime(date('Y-m-d') . ' +1 day'));

	   	if(valid_email(trim($email))==false){
		   	return [
		   		'success' => constants('invalidResponse.success'),
		   		'message' => "Please Provide a Valid Email Address."
		   	];
		}
		else if($for_whom!=constants('usertype.customer') || !is_numeric($user_id))
		{
			return [
				'success' => constants('invalidResponse.success'),
				'message' => "This is a Bad Request."
			];
		}
		else
		{
			$count =  OTP::where('user_id', $user_id)->where('for_whom', $for_whom)->where('is_active', constants('is_active_yes'))->where('created_at', 'LIKE' , date('Y-m-d').'%')->count();

			if($count >= constants('max_otp_limit_count_reached')){
				return [
					'success' => constants('invalidResponse.success'),
					'message' => "You have Reached to Your OTP Limit, Please Try Again After Sometime."
				];
			}
			else
			{
				$newOTP = createOTP(4);
				$data = OTP::create(['otp' => $newOTP, 'user_id' => $user_id, 'for_whom' => $for_whom, 'for_what' => $for_what, 'is_active' => constants('is_active_yes') , 'device_type' => $devicetype ]);


				/*--------------send email-------------------*/
            $subject = "OTP";
            $maildata =
            [
                "otptext" => "Your ".constants('title')." OTP for verification is ".$newOTP,
                "date" => date('Y-m-d H:i:s'),
            ];

            $html = 'hellok';

            $useremail = $email;
            $username = "Dear Customer";
            sendMail($html, $useremail, $username, $subject, $maildata);
            /*--------------send email-------------------*/

				return [
	                    'success' => constants('validResponse.success'),
	                    'message' => "OTP has been Sent to this Email ".$email ,
	                    'data'    => $data->id,
	                ];
			}
		}
   }

	/*------check otp for forgot password email and reset password-------*/
   public function checkOTPforForgotPasswordEmail(Request $request)
   {
   		try{
   		if($request->header('devicetype')=='' || !in_array($request->header('devicetype'), constants('devicetype'))){
	   		return response()->json([
                    'success' => constants('invalidToken.success'),
                    'message' => constants('invalidResponse.message'),
                    'data'    => constants('emptyData'),
                ], constants('invalidToken.statusCode'));
	   	}
	   	else if(!isset($request->email) || valid_email($request->email)==false){
	   		return response()->json([
                    'success' => constants('invalidResponse.success'),
                    'message' => "Please Provide a Valid Email Address.",
                    'data'    => constants('emptyData'),
                ], constants('invalidResponse.statusCode'));
	   	}
	   	else if(!isset($request->otp) || strlen($request->otp)!=4 || !is_numeric($request->otp)){
	   		return response()->json([
                    'success' => constants('invalidResponse.success'),
                    'message' => "Please Check and Type a Valid OTP.",
                    'data'    => constants('emptyData'),
                ], constants('invalidResponse.statusCode'));
	   	}
	   	else if(!isset($request->password) || valid_password($request->password)==false){
	   		return response()->json([
                    'success' => constants('invalidResponse.success'),
                    'message' => "Please Type a Valid Password Minimum 6 Digits.",
                    'data'    => constants('emptyData'),
                ], constants('invalidResponse.statusCode'));
	   	}
	   	else if(!isset($request->confirm_password) || $request->confirm_password!=$request->password){
	   		return response()->json([
                    'success' => constants('invalidResponse.success'),
                    'message' => "Password and Confirm Password must be Same.",
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

	   		$otpData =  OTP::where('id', $request->smsid)->where('for_what', $request->for_what)->where('otp', $request->otp)->where('is_active', constants('is_active_yes'))->orderBy('id','DESC')->first();

	   		if(!empty($otpData)){
				$dataCustomer = Customer::where('email', $request->email)->whereNotNull('email')->first();
				Customer::where('id', $dataCustomer->id)->update(['password' => bcrypt($request->password) ]);

	   			OTP::where('user_id', $otpData->user_id)->where('for_whom', constants('usertype.customer'))->where('is_active', constants('is_active_yes'))->update(['is_active' => constants('is_active_no')]);
	   			DeleteOTP();
	   			AccessToken::where('user_id',$otpData->user_id)->where('usertype', constants('usertype.customer'))->delete();

	   			return response()->json([
                    'success' => constants('validResponse.success'),
                    'message' => "Your Password has been Changed Successfully. Please Login With New Password.",
                    'data'    => constants('emptyData'),
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
	   	} catch (Exception $e) {
            Log::error($e);
            return response()->json([
            'success' => constants('invalidResponse.success'),
            'message' => "Error! Something Went Wrong.",
            'data'    => constants('emptyData'),
            ], constants('invalidResponse.statusCode'));
        }
   }












































}
