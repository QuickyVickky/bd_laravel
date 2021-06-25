<?php

namespace App\Http\Controllers\Client\Api\customer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Models\CustomerNotificationLog;
use App\Models\ShortHelper;
use Illuminate\Support\Facades\Log;
date_default_timezone_set('Asia/Kolkata');
use App\Models\SubscriptionPurchase;




class ProfileController extends Controller
{

  public function __construct()
  {
      ini_set('max_execution_time', 300);
  }

   public function testWebNotification(Request $request)
   {
      pushNotificationToUser("Order ", "Dear Customer Your Order has been Assigned To Driver.",'','','',1);

      return response()->json([
            'success' => constants('validResponse.success'),
            'message' => "notification sent ok.",
        ], constants('validResponse.statusCode'));
   }


   /*------get address listing of customer-------*/
   public function addressList(Request $request)
   {
      try {
   		$customerData = Customer::with([
                'customerAddress' => function ($qryCustomerAddress) {
                	$qryCustomerAddress->with(['customer_address_type']);
                  $qryCustomerAddress->where('is_active', constants('is_active_yes'));
                },
	   		])
	   		->where('is_active', constants('is_active_yes'))
	   		->where('id', $request->get('user_id'))
	   		->limit(constants('max_user_address_limit'))
	   		->first();

	   	return response()->json([
            'success' => constants('validResponse.success'),
            'message' => "",
            'data'    => $customerData,
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

   /*------get profile information of customer-------*/
   public function profileInfo(Request $request)
   {
      try {
   		$customerData = Customer::where('is_active', constants('is_active_yes'))->where('id', $request->get('user_id'))->first();

      $lastSubscriptionPurchase = SubscriptionPurchase::whereColumn('amount_used','<', 'maximum_discount_amount')->where('expired_datetime', '>', date('Y-m-d H:i:s'))->where('user_id', $request->get('user_id'))->where('is_active', constants('is_active_yes'))->first();

	   	return response()->json([
            'success' => constants('validResponse.success'),
            'message' => "Customer Profile Information",
            'data'    => $customerData,
            'customerData'    => [ 'lastSubscriptionPurchase' => $lastSubscriptionPurchase, ],
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

   /*------address edit------*/
   public function getAddressEdit(Request $request)
   {
      try {
   		if(!isset($request->address_id) || valid_id($request->address_id)==false){
	   		return response()->json(constants('somethingWentWrong'), constants('invalidResponse.statusCode'));
	   	}

   		$customerAddressData = CustomerAddress::with(['customer_address_type'])
   		->where('is_active', constants('is_active_yes'))
   		->where('user_id', $request->get('user_id'))
   		->where('id', $request->address_id)->first();

   		if(!empty($customerAddressData)){
   			return response()->json([
            'success' => constants('validResponse.success'),
            'message' => "Address Detail",
            'data'    => $customerAddressData,
        	], constants('validResponse.statusCode'));
   		}
   		else
   		{
   			return response()->json([
		    'success' => constants('invalidResponse.success'),
		    'message' => "No Address Found.",
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


   /*------address edit------*/
   public function pincodeVerifyApi(Request $request)
   {
   		if(strlen($request->pincode)!=6){
	   		return response()->json([
		    'success' => constants('invalidResponse.success'),
		    'message' => "Invalid Pincode.",
		    'data'    => constants('emptyData'),
		    ], constants('invalidResponse.statusCode'));
	   	}
	   	else
	   	{
	   		$pincodeData = verifyPincode($request->pincode);
	   		if($pincodeData['success']==true){
	   			return response()->json([
            		'success' => constants('validResponse.success'),
            		'message' => "Pincode Found",
            		'data'    => $pincodeData['data'],
            		'instruction' => "use key [Country,State,District,Pincode only]",
        		], constants('validResponse.statusCode'));
	   		}
	   		else
	   		{
	   			return response()->json([
		    		'success' => constants('invalidResponse.success'),
		    		'message' => "No Pincode Found or Fill Manually.",
		    		'data'    => constants('emptyData'),
		    	], constants('invalidResponse.statusCode'));
	   		}
	   	}
   }

   /*------address update------*/
   public function updateAddressEdit(Request $request)
   {
      try {

   		if(!isset($request->address_id) || valid_id($request->address_id)==false){
	   		return response()->json(constants('bad_request'), constants('invalidResponse.statusCode'));
	   	}

	   	if(trim($request->country)=='' || trim($request->state)=='' || strlen($request->pincode)!=6 || trim($request->city)=='' || trim($request->address)=='' || strlen($request->latitude)<4 || strlen($request->longitude)<4 || trim($request->address_type)=='' || !in_array($request->address_type, constants('address_type')) || trim($request->is_default)=='' || !in_array($request->is_default, constants('is_default'))){
	   		return response()->json(constants('checkRequiredField'), constants('invalidResponse.statusCode'));
	   	}

   		$count = CustomerAddress::where('is_active', constants('is_active_yes'))->where('user_id', $request->get('user_id'))->where('id', $request->address_id)->count();

   		if($count>0){

   		$is_default = constants('is_default.no');
			if($request->is_default==constants('is_default.yes')){
				CustomerAddress::where('user_id', $request->get('user_id'))->update([ 'is_default' => constants('is_default.no') ]);
				$is_default = constants('is_default.yes');
			}
			else
			{	
				$count = CustomerAddress::where('user_id', $request->get('user_id'))->where('is_active', constants('is_active_yes'))->where('is_default', constants('is_default.yes'))->count();
				if($count==0){
					$is_default = constants('is_default.yes');
				}
			}

   			$addressData = [
   				     'country' => isset($request->country) ? $request->country : 'India',
                'state' => $request->state,
                'city' => $request->city,
                'pincode' => $request->pincode,
                'address' => $request->address,
                'landmark' => isset($request->landmark) ? $request->landmark : NULL,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'address_type' => $request->address_type,
                'is_default' => $is_default,
   			];

   			$returnedAddressData =  CustomerAddress::where('is_active', constants('is_active_yes'))->where('user_id', $request->get('user_id'))->where('id', $request->address_id)->update($addressData);

   			return response()->json([
            'success' => constants('validResponse.success'),
            'message' => "Address Updated Successfully.",
            'data'    => $request->address_id,
        	], constants('validResponse.statusCode'));
   		}
   		else
   		{
   			return response()->json([
		    'success' => constants('invalidResponse.success'),
		    'message' => "No Address Found to Update.",
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

   /*------address add new------*/
   public function addAddressNew(Request $request)
   {
      try {

	   	if(trim($request->country)=='' || trim($request->state)=='' || strlen($request->pincode)!=6 || trim($request->city)=='' || trim($request->address)=='' || strlen($request->latitude)<4 || strlen($request->longitude)<4 || trim($request->address_type)=='' || !in_array($request->address_type, constants('address_type')) || trim($request->is_default)=='' || !in_array($request->is_default, constants('is_default'))){
	   		return response()->json(constants('checkRequiredField'), constants('invalidResponse.statusCode'));
	   	}

	   	$count = CustomerAddress::where('user_id', $request->get('user_id'))->where('is_active', constants('is_active_yes'))->count();
  		if($count>=constants('max_user_address_limit')){
  			return response()->json([
  		    'success' => constants('invalidResponse.success'),
  		    'message' => "You can not Add more than ".constants('max_user_address_limit')." Addresses.",
  		    'data'    => constants('emptyData'),
  		    ], constants('invalidResponse.statusCode'));
  		}

  	   	$is_default = constants('is_default.no');
  		if($request->is_default==constants('is_default.yes')){
  			CustomerAddress::where('user_id', $request->get('user_id'))->update([ 'is_default' => constants('is_default.no') ]);
  			$is_default = constants('is_default.yes');
  		}
  		else
  		{	
  			$count = CustomerAddress::where('user_id', $request->get('user_id'))->where('is_active', constants('is_active_yes'))->where('is_default', constants('is_default.yes'))->count();
  			if($count==0){
  				$is_default = constants('is_default.yes');
  			}
  		}

   			  $addressData = [
   				      'user_id' => $request->get('user_id'),
   				      'country' => isset($request->country) ? $request->country : 'India',
                'state' => $request->state,
                'city' => $request->city,
                'pincode' => $request->pincode,
                'address' => $request->address,
                'landmark' => isset($request->landmark) ? $request->landmark : NULL,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'address_type' => $request->address_type,
                'is_default' => $is_default,
                'is_active' => constants('is_active_yes'),
   			];

   			$lastData =  CustomerAddress::create($addressData);

   			return response()->json([
            'success' => constants('validResponse.success'),
            'message' => "Address Added Successfully.",
            'data'    => $lastData->id,
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

   /*------address update------*/
   public function setAddressDefault(Request $request)
   {
      try {

   		if(!isset($request->address_id) || valid_id($request->address_id)==false){
	   		return response()->json(constants('somethingWentWrong'), constants('invalidResponse.statusCode'));
	   	}

	   	if(trim($request->is_default)=='' || !in_array($request->is_default, constants('is_default'))){
	   		return response()->json(constants('checkRequiredField'), constants('invalidResponse.statusCode'));
	   	}

	   	$count = CustomerAddress::where('is_active', constants('is_active_yes'))->where('user_id', $request->get('user_id'))->where('id', $request->address_id)->count();

   		if($count>0){

   			$is_default = constants('is_default.no');
			if($request->is_default==constants('is_default.yes')){
				CustomerAddress::where('user_id', $request->get('user_id'))->update([ 'is_default' => constants('is_default.no') ]);
				$is_default = constants('is_default.yes');
			}
			else
			{	
				$count = CustomerAddress::where('user_id', $request->get('user_id'))->where('is_active', constants('is_active_yes'))->where('is_default', constants('is_default.yes'))->count();
				if($count==0){
					$is_default = constants('is_default.yes');
				}
			}

   			$addressData = [
                'is_default' => $is_default,
   			];

   			$returnedAddressData =  CustomerAddress::where('is_active', constants('is_active_yes'))->where('user_id', $request->get('user_id'))->where('id', $request->address_id)->update($addressData);

   			return response()->json([
            'success' => constants('validResponse.success'),
            'message' => "Address Updated Successfully.",
            'data'    => ["address_id" => $request->address_id],
        	], constants('validResponse.statusCode'));
   		}
   		else
   		{
   			return response()->json([
		    'success' => constants('invalidResponse.success'),
		    'message' => "No Address Found to Update.",
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


   /*------getAddressType-------*/
   public function getAddressType(Request $request) {
      try {

        $addressTypeData = ShortHelper::where('is_active', constants('is_active_yes'))->where('type', 'address_type')->limit(25)->get();
        
        return response()->json([
            'success' => constants('validResponse.success'),
            'message' => "AddressType List",
            'data'    => $addressTypeData,
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


   /*------get selected customer notification lists limit 10-------*/
   public function getNotificationList(Request $request)
   {
      try {
        $from = date('Y-m-d', strtotime(date('Y-m-d') . ' -30 day'));
        $to = date('Y-m-d', strtotime(date('Y-m-d') . ' +1 day'));
        CustomerNotificationLog::whereNotBetween('created_at', [$from, $to])->delete();
        $notificationData = CustomerNotificationLog::where('user_id', $request->get('user_id'))->limit(constants('basic_limit'))->orderBy('id','DESC')->get();

        return response()->json([
            'success' => constants('validResponse.success'),
            'message' => "",
            'data'    => $notificationData,
            'count'    => count($notificationData),
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


   /*------delete an Address------*/
   public function deleteThisAddress(Request $request)
   {
      try {

      if(!isset($request->address_id) || valid_id($request->address_id)==false){
        return response()->json(constants('somethingWentWrong'), constants('invalidResponse.statusCode'));
      }

      $count = CustomerAddress::where('is_active', constants('is_active_yes'))->where('user_id', $request->get('user_id'))->where('id', $request->address_id)->count();

      if($count>0){
        $addressData = ['is_active' => 2];
        CustomerAddress::where('user_id', $request->get('user_id'))->where('id', $request->address_id)->update($addressData);

        return response()->json([
            'success' => constants('validResponse.success'),
            'message' => "Address Deleted Successfully.",
            'data'    => $request->address_id,
          ], constants('validResponse.statusCode'));
      }
      else
      {
        return response()->json([
        'success' => constants('invalidResponse.success'),
        'message' => "No Address Found to Delete.",
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
























}
