<?php

namespace App\Http\Controllers\Client\Api\customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Razorpay\Api\Api;
use App\Models\RazorPayPayment;
use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Support\Facades\Log;
date_default_timezone_set('Asia/Kolkata');
use Carbon\Carbon;
use App\Models\Subscription;
use Illuminate\Support\Facades\Validator;
use App\Models\SubscriptionPurchase;
use App\Models\SubscriptionFeature;
use App\Models\Customer;
use App\Models\Coupon;
use App\Http\Controllers\Client\Api\customer\RazorPayController;
use App\Http\Controllers\Client\Api\customer\OrderController;


class SubscriptionController extends Controller
{

    public function __construct()
    {
        ini_set('max_execution_time', 300);
    }


    /*------ getSubscriptionList-------*/
   public function getSubscriptionList(Request $request)
   {
        $getSubscriptionFeatureDataArray = [];

        try {
            $getSubscriptionData = Subscription::where('is_active', constants('is_active_yes'))->limit(10)->get();
            $getSubscriptionFeatureData = SubscriptionFeature::where('is_active', constants('is_active_yes'))->limit(100)->get();
            $dataLastSubscriptionPurchaseValid = SubscriptionPurchase::whereColumn('amount_used','<', 'maximum_discount_amount')->where('expired_datetime', '>', date('Y-m-d H:i:s'))->where('user_id', $request->get('user_id'))->where('is_active', constants('is_active_yes'))->first();


            $increment = 0;

            if(count($getSubscriptionFeatureData)>0){
                foreach ($getSubscriptionFeatureData as $key => $value) {
                $getSubscriptionFeatureDataArray[$increment]['id'] = $value->id;
                $getSubscriptionFeatureDataArray[$increment]['subscription_feature'] = $value->subscription_feature;
                    $increment1 = 0;
                    foreach ($getSubscriptionData as $key1 => $value1) {
                        $getSubscriptionFeatureDataArray[$increment]['featureSeleted'][$increment1]['id'] = $value1->id;
                        $getSubscriptionFeatureDataArray[$increment]['featureSeleted'][$increment1]['subscription_shortname'] = $value1->subscription_shortname;
                        if(in_array($value->id, explode(',',$value1->subscription_feature_ids))){
                            $getSubscriptionFeatureDataArray[$increment]['featureSeleted'][$increment1]['is_seleted'] = true;
                        }
                        else
                        {
                            $getSubscriptionFeatureDataArray[$increment]['featureSeleted'][$increment1]['is_seleted'] = false;  
                        }
                        
                        $increment1++;
                    }
                    $increment++;
                }
            }


            return response()->json([
                'success' => constants('validResponse.success'),
                'message' => "",
                'data'    => [ 
                    "subscriptionData" => $getSubscriptionData, 
                    "subscriptionFeatureData" => $getSubscriptionFeatureData, 
                    "subscriptionFeatureDataWithSelected" => $getSubscriptionFeatureDataArray, 
                    "lastSubscriptionPurchaseValid" => $dataLastSubscriptionPurchaseValid, 
                    ] ,
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

   public function createNewOrderRazorPayForSubscriptionPurchase($orderData=[]) {
   	 try {
        if(empty($orderData)){
        	$response = ["msg" => "Missing Value.", "success" => 0 ];
        	return $response;
        }
        else
        {
        	$api = new Api(env('RAZOR_KEY'), env('RAZOR_SECRET'));
        	$orderInfo = [
                'receipt' => $orderData['receipt'],
                'currency' => 'INR',
                'amount' => intval($orderData['amount']*100),
                'notes' => $orderData['notes'],
            ];

            $order = $api->order->create($orderInfo);

            if(isset($order->id) && isset($order->created_at)){
	            $CreateData = [
	            	'razorpay_order_id' => $order->id,
	            	'amount' => $orderData['amount'],
	            	'order_id' => 0,
	            	'payment_status' => $order->status,
	            	'is_active' => constants('is_active_yes'),
                    'payment_type' => constants('paymentTypeOnline.Subscription'),
	            ];

	            RazorPayPayment::create($CreateData);
	            Log::info(json_encode($orderInfo));
	            Log::info(json_encode((array)$order));

	            $response = ["msg" => "RazorPay Order Created Successfully.", "data" => $order, "success" => 1];
	        	return $response;
            }
            else
            {
            	$response = ["msg" => "RazorPay Order Not Created, Error With Razorpay.", "success" => 0];
        		return $response;
            }
        }
	   	} catch(\Exception $e) {
	   		$response = ["msg" => $e->getMessage(), "success" => 0];
            return $response;
	   	} 
   }

   public function preCheckSubscriptionPurchaseValidation(Request $request)
   {
        try {

        $validator = Validator::make($request->all(), [ 
            'subscription_id' => 'required|integer|min:1',
        ]);   

        if($validator->fails()) {  
            return response()->json([
            'success' => constants('invalidResponse.success'),
            'message' => "Subscription Selection is Required.",
            'data'    => constants('emptyData'),
            ], constants('invalidResponse.statusCode'));    
        } 

        DeleteAccessToken();


        $dataSubscription = Subscription::where('id', $request->subscription_id)->where('is_active', constants('is_active_yes'))->first();

            if(empty($dataSubscription)){
                return response()->json([
                'success' => constants('invalidResponse.success'),
                'message' => "Subscription Must Be Valid & Available.",
                'data'    => constants('emptyData'),
                ], constants('invalidResponse.statusCode')); 
            }

            $dataLastSubscriptionPurchaseValid = SubscriptionPurchase::whereColumn('amount_used','<', 'maximum_discount_amount')->where('expired_datetime', '>', date('Y-m-d H:i:s'))->where('user_id', $request->get('user_id'))->where('is_active', constants('is_active_yes'))->first();

            if(!empty($dataLastSubscriptionPurchaseValid)){
                    return response()->json([
                    'success' => constants('invalidResponse.success'),
                    'message' => "You Can Not Purchase Another Subscription As Your Last Subscription Has Not Been Expired/Used.",
                    'data'    => constants('emptyData'),
                    ], constants('invalidResponse.statusCode')); 
            }


            $orderDataRazorPaySubscriptionPurchase = [
                'receipt' => "",
                'amount' => floatval($dataSubscription->subscription_value),
                'notes' => array(
                        "user_id" => $request->get('user_id'),
                        "type" => "Subscription Purchase", 
                        "timestamp" => time(),
                        "datetime" => date('Y-m-d H:i:s'), 
                        ),
            ];


            $createNewOrderRazorPayDataSubscriptionPurchase = $this->createNewOrderRazorPayForSubscriptionPurchase($orderDataRazorPaySubscriptionPurchase);
            if(isset($createNewOrderRazorPayDataSubscriptionPurchase['success']) && $createNewOrderRazorPayDataSubscriptionPurchase['success']==0){
                    return response()->json([
                    'success' => constants('invalidResponse.success'),
                    'message' => $createNewOrderRazorPayDataSubscriptionPurchase['msg'],
                    'data'    => constants('emptyData'),
                    ], constants('invalidResponse.statusCode'));
            }


            return response()->json([
                'success' => constants('validResponse.success'),
                'message' => "",
                'data'    => [
                        'amount' => floatval($dataSubscription->subscription_value),
                        'razor_orderid' => $createNewOrderRazorPayDataSubscriptionPurchase['data']['id'],
                        'subscription_id' => $request->subscription_id,
                        'currentdatetime' => time(),
                    ],
                'temptoken' => CreateTempToken(constants('usertype.customer'),$request->get('devicetype'),$request->get('user_id')),
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
   
   public function capturePaymentAddSubscriptionPurchaseRazorPay(Request $request)
   {
        try { 
        $input = $request->all();

        $validator = Validator::make($request->all(), [ 
            'razorpay_payment_id' => 'required|min:10',
            'razorpay_signature' => 'required|min:10',
            'razor_orderid' => 'required|min:10',
            'subscription_id' => 'required|integer|min:1',
        ]);   

        if($validator->fails() || $request->header('temptoken')=='') {  
            return response()->json([
            'success' => constants('invalidResponse.success'),
            'message' => "There is Problem to Verify Payment Request, Please Try Again.",
            'data'    => constants('emptyData'),
            ], constants('invalidResponse.statusCode'));    
        } 

        if(hash_hmac('sha256', $request->razor_orderid."|".$request->razorpay_payment_id, env('RAZOR_SECRET')) != $request->razorpay_signature) {
            return response()->json([
            'success' => constants('invalidResponse.success'),
            'message' => "There is Problem to Verify Payment Request, Please Try Again.",
            'data'    => ['razor_orderid' => $request->razor_orderid ],
            ], constants('invalidResponse.statusCode')); 
        }

        if(CheckTempToken(constants('usertype.customer'),$request->get('devicetype'),$request->get('user_id'),$request->header('temptoken'))!=true) {
            return response()->json([
            'success' => constants('invalidResponse.success'),
            'message' => "Time Out, Please Try Again.",
            'data'    => ['razor_orderid' => $request->razor_orderid ],
            ], constants('invalidResponse.statusCode')); 
        }

        $dataSubscription = Subscription::where('id', $request->subscription_id)->where('is_active', constants('is_active_yes'))->first();

        if(empty($dataSubscription)){
            return response()->json([
            'success' => constants('invalidResponse.success'),
            'message' => "Subscription Must Be Valid & Available.",
            'data'    => constants('emptyData'),
            ], constants('invalidResponse.statusCode')); 
        }


        $api = new Api(env('RAZOR_KEY'), env('RAZOR_SECRET'));
        $payment = $api->payment->fetch($request->razorpay_payment_id);
        $paymentArray = (array) $payment;
        Log::info(json_encode($paymentArray));

 
        $orderDataRazorPay = (new RazorPayController())->fetchOrderDetailOfRazorPay($request->razor_orderid);
        $paymentDataRazorPay = (new RazorPayController())->fetchPaymentDetailOfRazorPay($request->razorpay_payment_id);


            if(isset($orderDataRazorPay['success']) && $orderDataRazorPay['success']==1 && isset($orderDataRazorPay['data']['status']) && $orderDataRazorPay['data']['status']=='paid' && isset($paymentDataRazorPay['success']) && $paymentDataRazorPay['success']==1 && isset($paymentDataRazorPay['data']['status']) && $paymentDataRazorPay['data']['status']=='captured' && ($orderDataRazorPay['data']['created_at'] > time()-6*60) && ($orderDataRazorPay['data']['created_at'] < time()+1*60)) {


                $countRazorPayPayment = RazorPayPayment::where('order_id', 0)->where('razorpay_order_id', $request->razor_orderid)->where('payment_type', constants('paymentTypeOnline.Subscription'))->where('is_active',constants('is_active_yes'))->whereNull('razorpay_payment_id')->whereNull('razorpay_signature')->count();

                if($countRazorPayPayment==0) {
                    return response()->json([
                    'success' => constants('invalidResponse.success'),
                    'message' => "This Payment is Already Paid Or This is a Bad Request, Please Contact Us.",
                    'data'    => [ "razor_orderid" => $request->razor_orderid ],
                    'count'    => $countRazorPayPayment,
                    ], constants('invalidResponse.statusCode'));
                }


                $paidAmountRazorPay = floatval($orderDataRazorPay['data']['amount_paid'])/100;

                $updateDataRazorPayPayment = [
                    'razorpay_payment_id' => $request->razorpay_payment_id,
                    'razorpay_signature' => $request->razorpay_signature,
                    'payment_status' => $orderDataRazorPay['data']['status'],
                    'order_id' => 0,
                    'amount' => $paidAmountRazorPay,
                    'is_active' => constants('is_active_yes'),
                    'notes' => @$paymentDataRazorPay['data']['method'].'|'.@$paymentDataRazorPay['data']['bank'].'|'.@$paymentDataRazorPay['data']['vpa'],
                ];

                RazorPayPayment::where('order_id', 0)->where('razorpay_order_id', $request->razor_orderid)->where('payment_type', constants('paymentTypeOnline.Subscription'))->where('is_active',constants('is_active_yes'))->update($updateDataRazorPayPayment);

                $addDataSubscriptionPurchase = [
                    'subscription_value' => $paidAmountRazorPay,
                    'user_id' => $request->get('user_id'),
                    'purchase_datetime' => date('Y-m-d H:i:s'),
                    'transaction_number' => $request->razorpay_payment_id,
                    'notes' => "RazorPay Order Id: ". $request->razor_orderid,
                    'admin_id' => 0,
                    'subscription_shortname' => $dataSubscription->subscription_shortname,
                    'subscription_validity_months' => $dataSubscription->subscription_validity_months,
                    'subscription_title' => $dataSubscription->subscription_title,
                    'subscription_description' => $dataSubscription->subscription_description,
                    'subscription_terms' => $dataSubscription->subscription_terms,
                    'discount_type' => $dataSubscription->discount_type,
                    'is_active' => constants('is_active_yes'),
                    'min_order_value' => $dataSubscription->min_order_value,
                    'discount_value_min' => $dataSubscription->discount_value_min,
                    'discount_value_max' => $dataSubscription->discount_value_max,
                    'maximum_discount_perorder' => $dataSubscription->maximum_discount_perorder,
                    'maximum_discount_amount' => $dataSubscription->maximum_discount_amount,
                    'subscription_feature_ids' => $dataSubscription->subscription_feature_ids,
                    'amount_used' => 0,
                    'expired_datetime' => date('Y-m-d', strtotime(date('Y-m-d') . ' +'.$dataSubscription->subscription_validity_months.' month')),
                    'transaction_number' => $request->razorpay_payment_id,
                    'is_manually_added' => 0,
                    'subscription_id' => $request->subscription_id,
                ];

                SubscriptionPurchase::create($addDataSubscriptionPurchase);

                 /*---transaction create-starts----*/
                $dataAccountManageRazorPay = [
                    'transaction_uuid' => $request->razorpay_payment_id,
                    'transaction_date' => $addDataSubscriptionPurchase['purchase_datetime'],
                    'amount' => $paidAmountRazorPay,
                    'transaction_type' =>  constants('transaction_type.Credit'),
                    'description' => 'Subscription Purchase Payment',
                    'anybillno' => NULL,
                    'anybillno_document' => NULL,
                    'admin_id' => 0,
                    'is_active' => constants('is_active_yes'),
                    'payment_method' => constants('payment_method_for_accounting.P.short'),
                    'vendor_id' => NULL,
                    'is_reviewed' => 0,
                    'notes' => "Subscription Purchase",
                    'accountid_from' => constants('DefaultRazorPayAccountBankId'),
                    'accountid_transferredto' => NULL,
                    'is_editable'=> constants('is_editable_no'),
                    'transaction_subcategory_id' => constants('subscriptionTransactionSubCategoryId'),
                    'order_id' => NULL,
                    'user_id' => $request->get('user_id'),
                    'invoice_id' => NULL,
                ];

                Transaction::create($dataAccountManageRazorPay);
                /*---transaction create- ends----*/

                return response()->json([
                    'success' => constants('validResponse.success'),
                    'message' => 'Subscription Purchased Successfully.',
                    'data'    => [ 'razorpay_payment_id' => $request->razorpay_payment_id ],
                ], constants('validResponse.statusCode'));

            }
            else
            {
                return response()->json([
                'success' => constants('invalidResponse.success'),
                'message' => "This Payment is Wrong OR Expired.",
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

    /*------customer will spin & get discount--only one time per order----*/
   public function getLuckySpinWheelDiceSubscriptionDiscount(Request $request)
   {
        try {

        $validator = Validator::make($request->all(), [ 
            'order_id' => 'required|integer|min:1',
            'subscription_purchase_id'=> 'required|integer|min:1', 
        ]);   

        if($validator->fails()) {  
            return response()->json(constants('checkRequiredField'), constants('invalidResponse.statusCode'));   
        } 

        
        $orderData = Order::with([ 'order_status', 'order_payment_status', 'order_logs_user','orderParcel','customer',])->where('is_active', constants('is_active_yes'))->whereIn('status', constants('order_status.approved_orders'))->where('user_id', $request->get('user_id'))->where('id', $request->order_id)->where('payment_status', constants('payment_status.Pending'))->first();

        if(empty($orderData)) {
            return response()->json([
            'success' => constants('invalidResponse.success'),
            'message' => "Oops, No Valid Order Found.",
            'data'    => ['order_id' => $request->order_id ],
            ], constants('invalidResponse.statusCode')); 
        }

        $orderNewData = (new OrderController())->returnOrderDetailWithPaymentStatus($request->order_id, $request->get('user_id'));

        if($orderData->subscription_benefit_amount > 0){
            return response()->json([
            'success' => constants('validResponse.success'),
            'message' => "Congratulations, You have Got Rs. ".number_format($orderData->subscription_benefit_amount,2)." Subscription Discount on This Order.",
            'data'    => [
                'order_detail' => $orderNewData,
                'order_id' => $request->order_id,
                ],
            ], constants('validResponse.statusCode'));
        }


        $dataSubscriptionPurchase = SubscriptionPurchase::where('id', $request->subscription_purchase_id)->where('user_id', $request->get('user_id'))->where('is_active', constants('is_active_yes'))->where('expired_datetime', '>', date('Y-m-d H:i:s'))->where('maximum_discount_amount','>', 0)->whereColumn('amount_used','<', 'maximum_discount_amount')->first();

        if(empty($dataSubscriptionPurchase)){
            return response()->json([
            'success' => constants('invalidResponse.success'),
            'message' => "Subscription has Been Expired/Used Or Not Available, Please Purchase A New Subscription.",
            'data'    => ['order_id' => $request->order_id, ],
            ], constants('invalidResponse.statusCode')); 
        }
        if($orderData->total_payable_amount < $dataSubscriptionPurchase->min_order_value){
            return response()->json([
            'success' => constants('invalidResponse.success'),
            'message' => "To Use Subscription Discount On This Order, Your Order Payable Amount Must Be At Least ".$dataSubscriptionPurchase->min_order_value." Rs.",
            'data'    => ['order_id' => $request->order_id ],
            ], constants('invalidResponse.statusCode'));
        }

        $discountValueSubscription = mt_rand($dataSubscriptionPurchase->discount_value_min,$dataSubscriptionPurchase->discount_value_max);
        $discountAmountSubscription = 0;
        if($dataSubscriptionPurchase->discount_type==constants('discount_type.P.key')){
            $discountAmountSubscription = $discountValueSubscription*$orderData->total_payable_amount*0.01;
        }
        else if($dataSubscriptionPurchase->discount_type==constants('discount_type.F.key')){
            $discountAmountSubscription = $discountValueSubscription;
        }

        

        $pendingDiscountValueSubscription = $dataSubscriptionPurchase->maximum_discount_amount-$dataSubscriptionPurchase->amount_used;

        if($pendingDiscountValueSubscription <= 0){
            $discountAmountSubscription = 0;
        }

        if($dataSubscriptionPurchase->maximum_discount_perorder < $discountAmountSubscription){
            $discountAmountSubscription = $dataSubscriptionPurchase->maximum_discount_perorder;
        }

        if($pendingDiscountValueSubscription < $discountAmountSubscription){
            $discountAmountSubscription = $pendingDiscountValueSubscription;
        }


        if($discountAmountSubscription==0 || $orderData->discount > 0){
            return response()->json([
            'success' => constants('invalidResponse.success'),
            'message' => "You Can Not Apply Subscription Discount On This Order.",
            'data'    => ['order_id' => $request->order_id ],
            ], constants('invalidResponse.statusCode'));
        }



        $dataCouponApplied = Coupon::where('id', $orderData->coupon_code_id)->where('id', '>', 0)->first(['used_count']);
        if(!empty($dataCouponApplied)){
            $couponUpdate = [            
                "used_count" => $dataCouponApplied->used_count - 1,
            ];
            Coupon::where('id', $orderData->coupon_code_id)->update($couponUpdate);
        }


        $orderUpdate = [
            "subscription_purchase_id" => $request->subscription_purchase_id,
            "subscription_benefit_amount" => $discountAmountSubscription,
            "coupon_code_applied" => NULL,
            "coupon_code_id" => NULL,
            "coupon_benefit_amount" => 0,
            "total_payable_amount" => $orderData->total_payable_amount - $discountAmountSubscription,
            "discount" => $discountAmountSubscription,
        ];
        Order::where('is_active', constants('is_active_yes'))->whereIn('status', constants('order_status.approved_orders'))->where('user_id', $request->get('user_id'))->where('id', $request->order_id)->where('payment_status', constants('payment_status.Pending'))->update($orderUpdate);

        $subscriptionPurchaseUpdate = [
            "amount_used" => $dataSubscriptionPurchase->amount_used + $discountAmountSubscription,
        ];
        SubscriptionPurchase::where('id', $request->subscription_purchase_id)->where('user_id', $request->get('user_id'))->where('is_active', constants('is_active_yes'))->where('expired_datetime', '>', date('Y-m-d H:i:s'))->where('maximum_discount_amount','>', 0)->update($subscriptionPurchaseUpdate);



        //$orderNewData = Order::with([ 'order_status', 'order_payment_status', 'order_logs_user','orderParcel','customer',])->where('id', $request->order_id)->first();

        $orderNewData = (new OrderController())->returnOrderDetailWithPaymentStatus($request->order_id, $request->get('user_id'));

        return response()->json([
            'success' => constants('validResponse.success'),
            'message' => "Congratulations, You have Won Rs. ".number_format($discountAmountSubscription,2)." Subscription Discount on This Order.",
            'data'    => [
                'order_detail' => $orderNewData,
                'order_id' => $request->order_id,
                ],
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
