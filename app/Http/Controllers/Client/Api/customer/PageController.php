<?php

namespace App\Http\Controllers\Client\Api\customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Inquiry;
use App\Models\Company;
date_default_timezone_set('Asia/Kolkata');
use Illuminate\Support\Facades\Log;

class PageController extends Controller
{



	/*------check otp for login and allow login from mobile-------*/
   public function contactUsSubmit(Request $request)
   {
        try {
   		if($request->header('devicetype')=='' || !in_array($request->header('devicetype'), constants('devicetype'))){
	   		return response()->json(constants('bad_request'), constants('invalidResponse.statusCode'));  
	   	}
	   	else if(!isset($request->mobile) || valid_mobile($request->mobile)==false){
	   		return response()->json([
                    'success' => constants('invalidResponse.success'),
                    'message' => "Please Type a Valid Mobile Number.",
                    'data'    => constants('emptyData'),
                ], constants('invalidResponse.statusCode'));
	   	}
	   	else if(!isset($request->email) || valid_email($request->email)==false){
	   		return response()->json([
                    'success' => constants('invalidResponse.success'),
                    'message' => "Please Type a Valid Email Address.",
                    'data'    => constants('emptyData'),
                ], constants('invalidResponse.statusCode'));
	   	}
	   	else if(!isset($request->fullname) || strlen($request->fullname)<2){
	   		return response()->json([
                    'success' => constants('invalidResponse.success'),
                    'message' => "Please Enter Your Name Properly.",
                    'data'    => constants('emptyData'),
                ], constants('invalidResponse.statusCode'));
	   	}
	   	else if(!isset($request->msg) || strlen($request->msg)<10 || strlen($request->msg)>1000){
	   		return response()->json([
                    'success' => constants('invalidResponse.success'),
                    'message' => "Please Enter Your Message Minimum Between 10 Characters to 1000 Characters.",
                    'data'    => constants('emptyData'),
                ], constants('invalidResponse.statusCode'));
	   	}

	   		$insertData = [
                'fullname' => $request->fullname,
                'email_address' => isset($request->email) ? $request->email : NULL,
                'mobile' => isset($request->mobile) ? $request->mobile : NULL,
                'subject' => "Contact Us Inquiry",
                'message' => $request->msg,
                'devicetype' => $request->header('devicetype'),
                'ipaddress' => getIPAddress(),
                'is_active' => constants('is_active_yes'),
                ];


            $dataLast = Inquiry::create($insertData);

        	if($dataLast->id>0){

        	/*--------------send email-------------------*/
            $subject = $insertData['subject'];
            $maildata =
            [
                "message" => $insertData['message'],
                "fullname" => $insertData['fullname'],
                "emailid" => $insertData['email_address'],
                "mobile" => $insertData['mobile'],
                "ipaddress" => $insertData['ipaddress'],
                "date" => date('Y-m-d H:i:s'),
            ];

            $html = 'hellok';

            $useremail = $request->email;
            $username = $insertData['fullname'];
            //sendMail($html, $useremail, $username, $subject, $maildata);
            /*--------------send email-------------------*/

                pushNotificationToAdmin("New Inquiry","New Inquiry By Someone.",'','',route('inquiry-list'));

	   			return response()->json([
                    'success' => constants('validResponse.success'),
                    'message' => "Thank You ".$request->fullname.", Your Inquiry has been Submitted Successfully, We will Contact You Soon.",
                    'data'    => $dataLast->id,
                ], constants('validResponse.statusCode'));
            }
            else
            {
            	return response()->json(constants('somethingWentWrong'), constants('invalidResponse.statusCode'));
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


   /*------get Company Details Configuration------*/
   public function getCompanyConfiguration(Request $request)
   {
        try {
        $companyData = Company::where('id', constants('company_configurations_id'))->orderBy('id','ASC')->first();

        return response()->json([
            'success' => constants('validResponse.success'),
            'message' => "Company Information",
            'data'    => $companyData,
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
