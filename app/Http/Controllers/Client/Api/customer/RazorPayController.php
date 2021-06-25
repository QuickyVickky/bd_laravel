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
use Illuminate\Support\Facades\Validator;
use App\Models\Customer;
use App\Models\WalletTransaction;


class RazorPayController extends Controller
{
    public function __construct()
    {
        ini_set('max_execution_time', 300);
    }

     /*---- will fetch razorpay order details---*/
    public function fetchOrderDetailOfRazorPay($razorpay_order_id='') {
        try {
        $ch = curl_init();
        $url = "https://api.razorpay.com/v1/orders/".$razorpay_order_id;
        curl_setopt($ch, CURLOPT_USERPWD, env('RAZOR_KEY').':'.env('RAZOR_SECRET'));
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt( $ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        $result = curl_exec($ch);
        curl_close($ch);
        $resultArray = json_decode($result, true);

        if(isset($resultArray['error'])) {
            return ['success' => 0, 'data' => $resultArray['error'] , 'msg' => 'Error With Razorpay.' ];
        }
        else
        {
            return ['success' => 1, 'data' => $resultArray , 'msg' => 'Success With Razorpay.' ];
        }

        } catch(\Exception $e) {
            Log::error($e);
            return ['success' => 0, 'data' => $e , 'msg' => 'Error With Razorpay.' ];
        }
    }

        /*---- will fetch razorpay payment details---*/
    public function fetchPaymentDetailOfRazorPay($razorpay_payment_id='') {
        try {
        $ch = curl_init();
        $url = "https://api.razorpay.com/v1/payments/".$razorpay_payment_id;
        curl_setopt($ch, CURLOPT_USERPWD, env('RAZOR_KEY').':'.env('RAZOR_SECRET'));
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        $result = curl_exec($ch);
        curl_close($ch);
        $resultArray = json_decode($result, true);

        if(isset($resultArray['error'])) {
            return ['success' => 0, 'data' => $resultArray['error'] , 'msg' => 'Error With Razorpay.' ];
        }
        else
        {
            return ['success' => 1, 'data' => $resultArray , 'msg' => 'Success With Razorpay.' ];
        }
        } catch(\Exception $e) {
            Log::error($e);
            return ['success' => 0, 'data' => $e , 'msg' => 'Error With Razorpay.' ];
        }
    }

     /*---- will create new razorpay order for prepaid order---*/
    public function createNewOrderRazorPay($orderData=[]) 
    {

   	 try {
        if(empty($orderData)){
        	$response = ["msg" => "Missing Value.", "success" => 0];
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
	            $updateOrCreateData = [
	            	'razorpay_order_id' => $order->id,
	            	'amount' => $orderData['amount'],
	            	'order_id' => $orderData['notes']['order_id'],
	            	'payment_status' => $order->status,
	            	'is_active' => constants('is_active_yes'),
	            ];

	            RazorPayPayment::updateOrCreate(['order_id' => $orderData['notes']['order_id'], 'is_active' => constants('is_active_yes'), 'payment_type' => constants('paymentTypeOnline.Order')], $updateOrCreateData);
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

        /*---- will captire & confirm razorpay payment successful or not---*/
    public function capturePaymentOrderRazorPay(Request $request)
    {
        try {
        $input = $request->all();

        $validator = Validator::make($request->all(), [ 
            'razorpay_payment_id' => 'required|min:10',
            'razorpay_signature' => 'required|min:10',
            'razor_orderid' => 'required|min:10',
            'bigdaddy_orderid' => 'required|integer|min:1',
            'is_wallet_amount' => 'required',
            'use_wallet_amount' => 'required',
        ]);   

        if($validator->fails() || $request->header('temptoken')=='') {  
            return response()->json([
            'success' => constants('invalidResponse.success'),
            'message' => "Validation Failed, Please Refresh & Try Again.",
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
            'message' => "Time Out, Please Refresh Page & Try Again.",
            'data'    => ['razor_orderid' => $request->razor_orderid ],
            ], constants('invalidResponse.statusCode')); 
        }


        $orderData = Order::with([ 'order_status', 'order_payment_status'])->where('is_active', constants('is_active_yes'))->whereIn('status', constants('order_status.approved_orders'))->where('id', $request->bigdaddy_orderid)->where('payment_status', constants('payment_status.Pending'))->first();

        if(empty($orderData)) {
            return response()->json([
                'success' => constants('invalidResponse.success'),
                'message' => "Oops, No Valid Order Found.",
                'data'    => constants('emptyData'),
            ], constants('invalidResponse.statusCode')); 
        }

        $countRazorPayPayment = RazorPayPayment::where('order_id', '>', 0)->where('razorpay_signature', $request->razorpay_signature)->where('razorpay_payment_id', $request->razorpay_payment_id)->where('payment_type', constants('paymentTypeOnline.Order'))->count();

        if($countRazorPayPayment>0){
            return response()->json([
            'success' => constants('invalidResponse.success'),
            'message' => "This Payment is Invalid, Please Refresh & Try Again.",
            'data'    => constants('emptyData'),
            ], constants('invalidResponse.statusCode')); 
        }


        $api = new Api(env('RAZOR_KEY'), env('RAZOR_SECRET'));
        $payment = $api->payment->fetch($request->razorpay_payment_id);
        $paymentArray = (array) $payment;
        Log::info(json_encode($paymentArray));
 
        $orderDataRazorPay = $this->fetchOrderDetailOfRazorPay($request->razor_orderid);
        $paymentDataRazorPay = $this->fetchPaymentDetailOfRazorPay($request->razorpay_payment_id);


        if(isset($orderDataRazorPay['success']) && $orderDataRazorPay['success']==1 && isset($orderDataRazorPay['data']['status']) && $orderDataRazorPay['data']['status']=='paid' && isset($paymentDataRazorPay['success']) && $paymentDataRazorPay['success']==1 && isset($paymentDataRazorPay['data']['status']) && $paymentDataRazorPay['data']['status']=='captured' && ($orderDataRazorPay['data']['created_at'] > time()-6*60) && ($orderDataRazorPay['data']['created_at'] < time()+1*60)) {

            $countRazorPayPayment = RazorPayPayment::where('order_id', '>', 0)->where('razorpay_order_id', $request->razor_orderid)->where('payment_type', constants('paymentTypeOnline.Order'))->where('is_active',constants('is_active_yes'))->whereNull('razorpay_payment_id')->whereNull('razorpay_signature')->count();


            $paidAmountRazorPay = floatval($orderDataRazorPay['data']['amount_paid'])/100;

                if($countRazorPayPayment==0) {
                    return response()->json([
                    'success' => constants('invalidResponse.success'),
                    'message' => "This Payment is Already Paid Or This is a Bad Request, Please Contact Us With Order Number.",
                    'data'    => [ "razor_orderid" => $request->razor_orderid ],
                    ], constants('invalidResponse.statusCode'));
                }


                $updateDataRazorPayPayment = [
                    'razorpay_payment_id' => $request->razorpay_payment_id,
                    'razorpay_signature' => $request->razorpay_signature,
                    'payment_status' => $orderDataRazorPay['data']['status'],
                    'amount' => $paidAmountRazorPay,
                    'notes' => @$paymentDataRazorPay['data']['method'].'|'.@$paymentDataRazorPay['data']['bank'].'|'.@$paymentDataRazorPay['data']['vpa'],
                ];

                RazorPayPayment::where('order_id', $request->bigdaddy_orderid)->where('razorpay_order_id', $request->razor_orderid)->where('is_active',constants('is_active_yes'))->where('payment_type', constants('paymentTypeOnline.Order'))->update($updateDataRazorPayPayment);


            $dataCustomer = Customer::where('id',$request->get('user_id'))->first(['wallet_credit']);

            if($request->is_wallet_amount==constants('confirmation.yes') && $request->use_wallet_amount>0 && $dataCustomer->wallet_credit>0 && $dataCustomer->wallet_credit >= $dataCustomer->use_wallet_amount && $orderData->total_payable_amount > $request->use_wallet_amount) {

                $updateDataOrderPlaced = [
                    'payment_status' => constants('payment_status.Paid'),
                    'payment_type' => constants('payment_type.Prepaid'),
                    'status' => "PU",
                    'bigdaddy_lr_number' => createBigDaddyLrNumber(),
                    'payment_datetime' => date('Y-m-d H:i:s'),
                    'if_transaction_number' => $request->razorpay_payment_id,
                    'payment_comment' => "RazorPay Order Id: ". $request->razor_orderid,
                    'wallet_amount_used' => $request->use_wallet_amount, 
                    'prepaid_amount_used' => $orderData->total_payable_amount - $request->use_wallet_amount, 
                    'cod_amount_used' =>  0, 
                    'payment_type_manual' => constants('payment_type_manual.P.short'),
                ];

            
                $addDataWalletTransaction = [
                    'transaction_amount' => 0,
                    'transaction_credit' => $updateDataOrderPlaced['wallet_amount_used'],
                    'transaction_type' => constants('transaction_type.Debit'),
                    'user_id' => $request->get('user_id'),
                    'transaction_datetime' => date('Y-m-d H:i:s'),
                    'transaction_number' => "ORDERID_".$request->bigdaddy_orderid,
                    'notes' => "Used With Wallet Order",
                    'order_id' => $request->bigdaddy_orderid,
                    'admin_id' => 0,
                    'is_active' => constants('is_active_yes'),
                    'is_manually_added' => 0,
                ];

                WalletTransaction::where('order_id', $request->bigdaddy_orderid)->where('transaction_type', constants('transaction_type.Debit'))->where('user_id', $request->get('user_id'))->updateOrCreate($addDataWalletTransaction);        
            }
            else
            {
                $updateDataOrderPlaced = [
                    'payment_status' => constants('payment_status.Paid'),
                    'payment_type' => constants('payment_type.Prepaid'),
                    'status' => "PU",
                    'bigdaddy_lr_number' => createBigDaddyLrNumber(),
                    'payment_datetime' => date('Y-m-d H:i:s'),
                    'if_transaction_number' => $request->razorpay_payment_id,
                    'payment_comment' => "RazorPay Order Id: ". $request->razor_orderid,
                    'wallet_amount_used' => 0, 
                    'prepaid_amount_used' => $orderData->total_payable_amount, 
                    'cod_amount_used' =>  0,
                    'payment_type_manual' => constants('payment_type_manual.P.short'),
                ];
            }

            Order::where('id', $request->bigdaddy_orderid)->where('is_active',constants('is_active_yes'))->update($updateDataOrderPlaced);

            Customer::where('id',$request->get('user_id'))->update(['wallet_credit' => $dataCustomer->wallet_credit - $updateDataOrderPlaced['wallet_amount_used'] ]);

                
                 /*---transaction create-starts----*/
                $dataAccountManageRazorPay = [
                    'transaction_uuid' => random_text(10).uniqid().mt_rand(1000,9999),
                    'transaction_date' => $updateDataOrderPlaced['payment_datetime'],
                    'amount' => $paidAmountRazorPay,
                    'transaction_type' =>  constants('transaction_type.Credit'),
                    'description' => 'Razorpay Payment From #'.$updateDataOrderPlaced['bigdaddy_lr_number'],
                    'anybillno' => NULL,
                    'anybillno_document' => NULL,
                    'admin_id' => 0,
                    'is_active' => constants('is_active_yes'),
                    'payment_method' => constants('payment_method_for_accounting.CARD.short'),
                    'vendor_id' => NULL,
                    'is_reviewed' => 0,
                    'notes' => "Razorpay Payment",
                    'accountid_from' => constants('DefaultRazorPayAccountBankId'),
                    'accountid_transferredto' => NULL,
                    'is_editable'=> constants('is_editable_no'),
                    'transaction_subcategory_id' => constants('Income_Invoice_Payment_Subcategory_Id'),
                    'order_id' => $request->bigdaddy_orderid,
                    'user_id' => $orderData->user_id,
                    'invoice_id' => NULL,
                ];

                Transaction::updateOrCreate(['order_id' => $request->bigdaddy_orderid, 'user_id' => $orderData->user_id, 'is_active' => constants('is_active_yes'), 'invoice_id' => NULL, 'is_editable' => constants('is_editable_no') , ], $dataAccountManageRazorPay );
                /*---transaction create- ends----*/


                createOrderLogs("Order Payment Paid Online By Customer, Amount INR [".$paidAmountRazorPay."]", $request->bigdaddy_orderid);
                createOrderLogs("Order Successfully Placed.", $request->bigdaddy_orderid, 1);
                pushNotificationToAdmin("Order Payment","Order Payment Paid Online By Customer.",'','', route('view-order')."/".$request->bigdaddy_orderid);
                createAdminNotificationLogs("Order Payment","Order Payment Paid Online By Customer.", route('view-order')."/".$request->bigdaddy_orderid);

                return response()->json([
                    'success' => constants('validResponse.success'),
                    'message' => 'Order Successfully Placed.',
                    'data'    => ['bigdaddy_orderid' => $request->bigdaddy_orderid ],
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
            'message' => "Error! Something Went Wrong With This Payment.",
            'data'    => constants('emptyData'),
            ], constants('invalidResponse.statusCode'));
        } 
    }

        /*---- will check if bigdaddy order is valid or not for furthur---*/
    public function preCheckPrepaidPlaceOrderValidation(Request $request)
    {    
        try {
        $validator = Validator::make($request->all(), [ 
            'order_id' => 'required|integer|min:1',
            'payment_type' => 'required',
            'is_wallet_amount' => 'required',
            'use_wallet_amount' => 'required',
            'currentdatetime' => 'required',
        ]);   

        if($validator->fails() || !in_array($request->is_wallet_amount, constants('confirmation')) || $request->payment_type!=constants('payment_type.Prepaid')) {  
            return response()->json([
            'success' => constants('invalidResponse.success'),
            'message' => "Validation Failed! Please Refresh Page & Try Again.",
            'data'    => constants('emptyData'),
            ], constants('invalidResponse.statusCode'));    
        }

        if(!isset($request->currentdatetime) || !is_numeric($request->currentdatetime) || ($request->currentdatetime + 4*3600 < time()) ){
            return response()->json([
                'success' => constants('invalidResponse.success'),
                'message' => "Time Out, Plese Refresh & Try Again.",
                'data'    => ['order_id' => $request->order_id, ],
            ], constants('invalidResponse.statusCode'));
        }

        DeleteAccessToken();

        $orderData = Order::where('is_active', constants('is_active_yes'))->whereIn('status', constants('order_status.approved_orders'))->where('user_id', $request->get('user_id'))->where('payment_status', constants('payment_status.Pending'))->where('id', $request->order_id)->first(['total_payable_amount']);
        if(empty($orderData)) {
            return response()->json([
                'success' => constants('invalidResponse.success'),
                'message' => "Oops, No Valid Order Found.",
                'data'    => constants('emptyData'),
            ], constants('invalidResponse.statusCode')); 
        }


        if($request->is_wallet_amount==constants('confirmation.yes') && $request->use_wallet_amount > 0){
          $dataCustomer = Customer::where('id',$request->get('user_id'))->first(['wallet_credit']);

          if($dataCustomer->wallet_credit < $request->use_wallet_amount){
            return response()->json([
                'success' => constants('invalidResponse.success'),
                'message' => "Sorry, Your Wallet Does Not Have Enough Credit.",
                'data'    => constants('emptyData'),
            ], constants('invalidResponse.statusCode')); 
          }
        }
        else
        {
            $request->use_wallet_amount = 0;
        }

        $orderNewData = Order::where('id', $request->order_id)->first();


        $orderDataRazorPayOrder = [
            'receipt' => $orderNewData->id,
            'amount' => floatval($orderNewData->total_payable_amount - $request->use_wallet_amount),
            'notes' => 
                array(
                        "order_id" => $orderNewData->id,
                        "user_id" => $orderNewData->user_id, 
                        "customer_name" => $orderNewData->transporter_name, 
                        "bigdaddy_lr_number" => $orderNewData->bigdaddy_lr_number, 
                        "contact_person_phone_number" => $orderNewData->contact_person_phone_number,
                        "timestamp" => time(),
                        "datetime" => date('Y-m-d H:i:s'), 
                        "is_wallet_amount" => $request->is_wallet_amount,
                        "use_wallet_amount" => $request->use_wallet_amount,
                ),
        ];


        $createNewOrderRazorPayDataOrder = $this->createNewOrderRazorPay($orderDataRazorPayOrder);
        if(!isset($createNewOrderRazorPayDataOrder['success']) || $createNewOrderRazorPayDataOrder['success']==0){
            return response()->json([
                'success' => constants('invalidResponse.success'),
                'message' => @$createNewOrderRazorPayDataOrder['msg'],
                'data'    => constants('emptyData'),
            ], constants('invalidResponse.statusCode'));
        }


        return response()->json([
            'success' => constants('validResponse.success'),
            'message' => "",
            'data'    => [
              'bigdaddy_orderid' => $request->order_id, 
              'payment_type' => constants('payment_type.Prepaid'),
              'is_wallet_amount' => $request->is_wallet_amount,
              'use_wallet_amount' => $request->use_wallet_amount,
              'currentdatetime' => time(),
              'amount' => $orderDataRazorPayOrder['amount'],
              'razor_orderid' => $createNewOrderRazorPayDataOrder['data']['id'],
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





}
