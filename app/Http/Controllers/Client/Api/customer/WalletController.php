<?php

namespace App\Http\Controllers\Client\Api\customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Razorpay\Api\Api;
use App\Models\RazorPayPayment;
use App\Models\Order;
use App\Models\Transaction;
use App\Models\Customer;
use Illuminate\Support\Facades\Log;
date_default_timezone_set('Asia/Kolkata');
use Carbon\Carbon;
use App\Models\WalletCreditList;
use Illuminate\Support\Facades\Validator;
use App\Models\WalletTransaction;
use App\Exports\JustExcelExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Client\Api\customer\RazorPayController;



class WalletController extends Controller
{

    public function __construct()
    {
        ini_set('max_execution_time', 300);
    }

    /*-----getWalletCreditList-------*/
   public function getWalletCreditList(Request $request)
   {

     try {

      $dataWalletCreditList = WalletCreditList::where('is_active', constants('is_active_yes'))->limit(250)->orderBy('id','DESC')->get();
      return response()->json([
            'success' => constants('validResponse.success'),
            'message' => "",
            'data'    => $dataWalletCreditList,
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

    /*------getWalletTransactionListWithFilter------*/
   public function getWalletTransactionListWithFilter(Request $request)
   {
     try {
        $searched = ''; $pageno = 0; $perpage = 15; $startdate = '1970-01-01'; $enddate = date('Y-m-d', strtotime(date('Y-m-d') . ' +1 day'));

        $validator = Validator::make($request->all(), [ 
            'startdate' => 'nullable|date_format:Y-m-d',
            'enddate' => 'nullable|date_format:Y-m-d|after_or_equal:startdate',
        ]);   

        if($validator->fails()) {  
            return response()->json([
              'success' => constants('invalidResponse.success'),
              'message' => $validator->errors()->first(),
              'data'    => constants('emptyData'),
          ], constants('invalidResponse.statusCode')); 
        }

        if(isset($request->search) && trim($request->search)!=''){
            $searched = $request->search;
        }
        if(isset($request->pageno)){
            $pageno = intval($request->pageno);
        }
        if(isset($request->perpage)){
            $perpage = (intval($request->perpage)>200) ? 200 : intval($request->perpage);
        }

        if(isset($request->startdate) && isset($request->enddate) && strlen($request->startdate)==10 && strlen($request->enddate)==10){
            $startdate = date('Y-m-d', strtotime($request->startdate));
            $enddate = date('Y-m-d', strtotime($request->enddate . ' +1 day'));
        }

        $dataCustomer = Customer::where('id',$request->get('user_id'))->first(['wallet_credit']);


        $count = WalletTransaction::where('user_id', $request->get('user_id'))
            ->where('is_active', constants('is_active_yes'))
            ->where(function($querySearch) use ($searched) {
                  if($searched!=''){
                    $querySearch->where('transaction_number', 'LIKE', '%'.$searched.'%');
                    $querySearch->orWhere('transaction_credit', 'LIKE', '%'.$searched.'%');
                    $querySearch->orWhere('transaction_amount', 'LIKE', '%'.$searched.'%');
                    $querySearch->orWhere('transaction_datetime', 'LIKE', '%'.$searched.'%');
                  }
            })
            ->where(function($queryDateBetween) use ($startdate,$enddate) {
                  if($startdate!='' && $enddate!=''){
                    $queryDateBetween->whereBetween('transaction_datetime', [$startdate, $enddate]);
                  }
            })
            ->count();

         $dataWalletTransaction = WalletTransaction::where('user_id', $request->get('user_id'))
            ->where('is_active', constants('is_active_yes'))
            ->where(function($querySearch) use ($searched) {
                    if($searched!=''){
                    $querySearch->where('transaction_number', 'LIKE', '%'.$searched.'%');
                    $querySearch->orWhere('transaction_credit', 'LIKE', '%'.$searched.'%');
                    $querySearch->orWhere('transaction_amount', 'LIKE', '%'.$searched.'%');
                    $querySearch->orWhere('transaction_datetime', 'LIKE', '%'.$searched.'%');
                    }
            })
            ->where(function($queryDateBetween) use ($startdate,$enddate) {
                  if($startdate!='' && $enddate!=''){
                    $queryDateBetween->whereBetween('transaction_datetime', [$startdate, $enddate]);
                  }
            })
            ->offset($pageno*$perpage)
            ->limit($perpage)
            ->orderBy('id','DESC')
            ->get();


            $transactionList = [];

            foreach ($dataWalletTransaction as $row) {

                $transactionList[] = [
                    'id' => $row->id,
                    'transaction_number' => $row->transaction_number,
                    'transaction_datetime' => Carbon::parse($row->transaction_datetime)->format('d/m/Y'),
                    'transaction_datetime1' => Carbon::parse($row->transaction_datetime)->format('d M, Y'),
                    'transactiondatetime' => $row->transaction_datetime,
                    'order_id' => $row->order_id,
                    'transaction_amount' => $row->transaction_amount,
                    'transaction_credit' => $row->transaction_credit,
                    'transaction_type' => $row->transaction_type,
                    'notes' => $row->notes,
                    'is_manually_added' => $row->is_manually_added,
                ];
            }


            return response()->json([
                'success' => constants('validResponse.success'),
                'message' => "",
                'data'    => [
                    "WalletTransactionList" => $transactionList,
                    "dataWallet" => $dataCustomer,
                ],
                'total_count'    => $count,
                'per_page_count'    => $perpage,
                'searched'    => $searched,
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

    /*-----downloadExcelWalletTransactionListWithFilter-------*/
   public function downloadExcelWalletTransactionListWithFilter(Request $request)
   {
     try {

        $validator = Validator::make($request->all(), [ 
            'startdate' => 'required|date_format:Y-m-d',
            'enddate' => 'required|date_format:Y-m-d|after_or_equal:startdate',
        ]);   

        if($validator->fails()) {  
            return response()->json([
            'success' => constants('invalidResponse.success'),
            'message' => "Proper Date Selection is Required.",
            'data'    => constants('emptyData'),
            ], constants('invalidResponse.statusCode'));    
        }

        if(isset($request->startdate) && isset($request->enddate) && strlen($request->startdate)==10 && strlen($request->enddate)==10){
            $startdate = date('Y-m-d', strtotime($request->startdate));
            $enddate = date('Y-m-d', strtotime($request->enddate . ' +1 day'));
        }
        else
        {
            return response()->json([
            'success' => constants('invalidResponse.success'),
            'message' => "Date Field is Required.",
            'data'    => constants('emptyData'),
            ], constants('invalidResponse.statusCode'));
        }

        $count = WalletTransaction::where('user_id', $request->get('user_id'))
            ->where('is_active', constants('is_active_yes'))
            ->where(function($queryDateBetween) use ($startdate,$enddate) {
                  if($startdate!='' && $enddate!=''){
                    $queryDateBetween->whereBetween('transaction_datetime', [$startdate, $enddate]);
                  }
            })
            ->count();

        if($count<1){
            return response()->json([
            'success' => constants('invalidResponse.success'),
            'message' => "No Transaction Found.",
            'data'    => constants('emptyData'),
            ], constants('invalidResponse.statusCode'));
        }

        if($count>5000){
            return response()->json([
            'success' => constants('invalidResponse.success'),
            'message' => "Too Much Transactions, Please Change to Less Date Selection.",
            'data'    => constants('emptyData'),
            ], constants('invalidResponse.statusCode'));
        }

        $column_name =  ['transaction_number','transaction_datetime','transaction_type','transaction_credit'];

        $dataWalletTransaction = WalletTransaction::where('user_id', $request->get('user_id'))
        ->where('is_active', constants('is_active_yes'))
        ->where(function($queryDateBetween) use ($startdate,$enddate) {
                  if($startdate!='' && $enddate!=''){
                    $queryDateBetween->whereBetween('transaction_datetime', [$startdate, $enddate]);
                  }
        })
        ->orderBy('id','DESC')
        ->get($column_name)->toArray();

        DeleteTempFile();


        $path = asset('storage'.'/temp_files').'/';
        $fileName = "WalletTransactions".time().mt_rand(1000,9999).'.xlsx';
        $export = new JustExcelExport($dataWalletTransaction, $column_name );
        //return Excel::download($export, $fileName); 
        Excel::store($export, $fileName, 'temp');

        return response()->json([
            'success' => constants('validResponse.success'),
            'message' => "",
            'data'    => [ 'url' => $path.$fileName, ],
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

    /*---- will create new razorpay order to add amount in wallet---*/
   public function createNewOrderRazorPayForWallet($orderData=[]) {

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
                    'payment_type' => constants('paymentTypeOnline.Wallet'),
	            ];

	            RazorPayPayment::create($CreateData);
	            Log::info(json_encode($orderInfo));
	            Log::info(json_encode((array)$order));

	            $response = ["msg" => "RazorPay Order Created Successfully.", "data" => $order, "success" => 1];
	        	return $response;
            }
            else
            {
            	$response = ["msg" => "Your Request Can Not Be Fulfilled Currently.", "success" => 0];
        		return $response;
            }
        }
              		
	   	} catch(\Exception $e) {
	   		$response = ["msg" => $e->getMessage(), "success" => 0];
        	return $response;
	   	} 
   }

     /*---- will check if it is valid or not for furthur---*/
   public function preCheckWalletValidation(Request $request)
   {    
        try {
        $validator = Validator::make($request->all(), [ 
            'amount'=> 'required|numeric|between:10,99999999.99',
        ]);   

        if($validator->fails()) {  
            return response()->json([
            'success' => constants('invalidResponse.success'),
            'message' => "Amount Must Be Valid & At Least 10 Rs.",
            'data'    => constants('emptyData'),
            ], constants('invalidResponse.statusCode'));    
        } 

        DeleteAccessToken();

        
        $orderDataRazorPayWalletAdd = [
            'receipt' => "",
            'amount' => floatval($request->amount),
            'notes' => array(
                    "user_id" => $request->get('user_id'),
                    "type" => "Add Wallet Amount", 
                    "timestamp" => time(),
                    "datetime" => date('Y-m-d H:i:s'), 
                 ),
        ];
        $createNewOrderRazorPayDataWallet = $this->createNewOrderRazorPayForWallet($orderDataRazorPayWalletAdd);
        if(isset($createNewOrderRazorPayDataWallet['success']) && $createNewOrderRazorPayDataWallet['success']==0){
            return response()->json([
            'success' => constants('invalidResponse.success'),
            'message' => $createNewOrderRazorPayDataWallet['msg'],
            'data'    => constants('emptyData'),
            ], constants('invalidResponse.statusCode'));
        }


        return response()->json([
            'success' => constants('validResponse.success'),
            'message' => "",
            'data'    => ['amount' => floatval($request->amount), 'razor_orderid' => $createNewOrderRazorPayDataWallet['data']['id'] ],
            'temptoken' => CreateTempToken(constants('usertype.customer'),$request->get('devicetype'),$request->get('user_id')),
        ], constants('validResponse.statusCode'))->withHeaders([ 'razor_orderid' => $createNewOrderRazorPayDataWallet['data']['id'] ]);        

        } catch(\Exception $e) {
            Log::error($e);
            return response()->json([
            'success' => constants('invalidResponse.success'),
            'message' => "Error! Something Went Wrong.",
            'data'    => constants('emptyData'),
            ], constants('invalidResponse.statusCode'));
        } 
   }

    
    /*---- will captire & confirm razorpay payment successful or not---*/
   public function capturePaymentAddWalletRazorPay(Request $request)
   {
        $input = $request->all();

        $validator = Validator::make($request->all(), [ 
            'razorpay_payment_id' => 'required|min:10',
            'razorpay_signature' => 'required|min:10',
            'razor_orderid' => 'required|min:10',
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
            'message' => "Time Out, Please Refresh Page & Try Again.",
            'data'    => ['razor_orderid' => $request->razor_orderid ],
            ], constants('invalidResponse.statusCode')); 
        }


        try {

        $api = new Api(env('RAZOR_KEY'), env('RAZOR_SECRET'));
        $payment = $api->payment->fetch($request->razorpay_payment_id);
        $paymentArray = (array) $payment;
        Log::info(json_encode($paymentArray));
 
        $orderDataRazorPay = (new RazorPayController())->fetchOrderDetailOfRazorPay($request->razor_orderid);
        $paymentDataRazorPay = (new RazorPayController())->fetchPaymentDetailOfRazorPay($request->razorpay_payment_id);


        if(isset($orderDataRazorPay['success']) && $orderDataRazorPay['success']==1 && isset($orderDataRazorPay['data']['status']) && $orderDataRazorPay['data']['status']=='paid' && isset($paymentDataRazorPay['success']) && $paymentDataRazorPay['success']==1 && isset($paymentDataRazorPay['data']['status']) && $paymentDataRazorPay['data']['status']=='captured' && ($orderDataRazorPay['data']['created_at'] > time()-6*60) && ($orderDataRazorPay['data']['created_at'] < time()+1*60)) {


                $paidAmountRazorPay = floatval($orderDataRazorPay['data']['amount_paid'])/100;


                $countRazorPayPayment = RazorPayPayment::where('order_id', 0)->where('razorpay_order_id', $request->razor_orderid)->where('payment_type', constants('paymentTypeOnline.Wallet'))->where('is_active',constants('is_active_yes'))->whereNull('razorpay_payment_id')->whereNull('razorpay_signature')->count();

                if($countRazorPayPayment==0) {
                    return response()->json([
                    'success' => constants('invalidResponse.success'),
                    'message' => "This Payment is Already Paid Or This is a Bad Request.",
                    'data'    => [ "razor_orderid" => $request->razor_orderid ],
                    ], constants('invalidResponse.statusCode'));
                }

                $updateDataRazorPayPayment = [
                    'razorpay_payment_id' => $request->razorpay_payment_id,
                    'razorpay_signature' => $request->razorpay_signature,
                    'payment_status' => $orderDataRazorPay['data']['status'],
                    'amount' => $request->amount,
                    'order_id' => 0,
                    'amount' => $paidAmountRazorPay,
                    'is_active' => constants('is_active_yes'),
                    'payment_type' => constants('paymentTypeOnline.Wallet'),
                    'notes' => @$paymentDataRazorPay['data']['method'].'|'.@$paymentDataRazorPay['data']['bank'].'|'.@$paymentDataRazorPay['data']['vpa'],
                ];

                RazorPayPayment::where('order_id', 0)->where('razorpay_order_id', $request->razor_orderid)->where('payment_type', constants('paymentTypeOnline.Wallet'))->where('is_active',constants('is_active_yes'))->update($updateDataRazorPayPayment);


                $dataWalletCreditList = WalletCreditList::where('is_active', constants('is_active_yes'))->where('wallet_amount',$paidAmountRazorPay)->first();

                $addDataWalletTransaction = [
                    'transaction_amount' => $paidAmountRazorPay,
                    'transaction_credit' => isset($dataWalletCreditList->wallet_credit) ? floatval($dataWalletCreditList->wallet_credit) : $paidAmountRazorPay,
                    'transaction_type' => constants('transaction_type.Credit'),
                    'user_id' => $request->get('user_id'),
                    'transaction_datetime' => date('Y-m-d H:i:s'),
                    'transaction_number' => $request->razorpay_payment_id,
                    'notes' => $paidAmountRazorPay." Amount Added in Wallet",
                    'order_id' => 0,
                    'admin_id' => 0,
                    'is_active' => constants('is_active_yes'),
                    'is_manually_added' => 0,
                ];

                WalletTransaction::where('order_id', 0)->where('transaction_type', constants('transaction_type.Credit'))->where('transaction_number', $request->razorpay_payment_id)->updateOrCreate($addDataWalletTransaction);

                $dataCustomer = Customer::where('id',$request->get('user_id'))->first(['wallet_credit']);
                Customer::where('id',$request->get('user_id'))->update(['wallet_credit' => floatval($dataCustomer->wallet_credit) + $addDataWalletTransaction['transaction_credit'] ]);

                 /*---transaction create-starts----*/
                $dataAccountManageRazorPay = [
                    'transaction_uuid' => random_text(10).uniqid().mt_rand(1000,9999),
                    'transaction_date' => date('Y-m-d'),
                    'amount' => $paidAmountRazorPay,
                    'transaction_type' =>  constants('transaction_type.Credit'),
                    'description' => 'Razorpay Payment Wallet',
                    'anybillno' => NULL,
                    'anybillno_document' => NULL,
                    'admin_id' => 0,
                    'is_active' => constants('is_active_yes'),
                    'payment_method' => constants('payment_method_for_accounting.P.short'),
                    'vendor_id' => NULL,
                    'is_reviewed' => 0,
                    'notes' => "Razorpay Payment Wallet",
                    'accountid_from' => constants('DefaultRazorPayAccountBankId'),
                    'accountid_transferredto' => NULL,
                    'is_editable'=> constants('is_editable_no'),
                    'transaction_subcategory_id' => constants('Income_Invoice_Payment_Subcategory_Id'),
                    'order_id' => NULL,
                    'user_id' => $request->get('user_id'),
                    'invoice_id' => NULL,
                ];

                Transaction::create($dataAccountManageRazorPay);
                /*---transaction create- ends----*/

                return response()->json([
                    'success' => constants('validResponse.success'),
                    'message' => 'Amount Added in Wallet Successfully.',
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

        } catch (Exception $e) {
            Log::error($e);
            return response()->json([
            'success' => constants('invalidResponse.success'),
            'message' => "Error! Something Went Wrong With This Payment.",
            'data'    => constants('emptyData'),
            ], constants('invalidResponse.statusCode'));
        } 
   }   


     /*---- will check if it is valid or not for furthur---*/
   public function preCheckWalletPlaceOrderValidation(Request $request)
   {    
        try {

        $validator = Validator::make($request->all(), [ 
            'order_id' => 'required|integer|min:1',
            'payment_type' => 'required',
            'currentdatetime' => 'required',
        ]);   

        if($validator->fails() || $request->payment_type!=constants('payment_type.Wallet')) {  
            return response()->json([
            'success' => constants('invalidResponse.success'),
            'message' => "Validation Failed! Please Refresh Page & Try Again.",
            'data'    => constants('emptyData'),
            ], constants('invalidResponse.statusCode'));    
        }

        DeleteAccessToken();

        if(!isset($request->currentdatetime) || !is_numeric($request->currentdatetime) || ($request->currentdatetime + 4*3600 < time()) ){
            return response()->json([
                'success' => constants('invalidResponse.success'),
                'message' => "Time Out, Plese Refresh & Try Again.",
                'data'    => ['order_id' => $request->order_id, ],
            ], constants('invalidResponse.statusCode'));
        }

        $orderData = Order::where('is_active', constants('is_active_yes'))->whereIn('status', constants('order_status.approved_orders'))->where('user_id', $request->get('user_id'))->where('payment_status', constants('payment_status.Pending'))->where('id', $request->order_id)->first(['total_payable_amount']);


        if(empty($orderData)) {
            return response()->json([
                'success' => constants('invalidResponse.success'),
                'message' => "Oops, No Order Found.",
                'data'    => constants('emptyData'),
            ], constants('invalidResponse.statusCode')); 
        }


        $dataCustomer = Customer::where('id',$request->get('user_id'))->first(['wallet_credit']);

        if($dataCustomer->wallet_credit < $orderData->total_payable_amount){
            return response()->json([
                'success' => constants('invalidResponse.success'),
                'message' => "Sorry, Your Wallet Does Not Have Enough Credit, Please Add Amount to Your Wallet Or Select Another Payment Method.",
                'data'    => ['order_id' => $request->order_id, ],
            ], constants('invalidResponse.statusCode')); 
        }


        return response()->json([
            'success' => constants('validResponse.success'),
            'message' => "",
            'data'    => [
              'order_id' => $request->order_id, 
              'payment_type' => constants('payment_type.Wallet'),
              'currentdatetime' => time(),
            ],
            'temptoken' => CreateTempToken(constants('usertype.customer'),$request->get('devicetype'),$request->get('user_id')),
        ], constants('validResponse.statusCode'));        

        } catch (Exception $e) {
            Log::error($e);
            return response()->json([
            'success' => constants('invalidResponse.success'),
            'message' => "Error! Something Went Wrong.",
            'data'    => constants('emptyData'),
            ], constants('invalidResponse.statusCode'));
        } 
   }


    /*---- will place & confirm bigdaddy order successful with wallet---*/
   public function placeOrderWithWallet(Request $request)
   {
      try{
        
        if(!isset($request->payment_type) || !isset($request->order_id) || $request->payment_type!=constants('payment_type.Wallet') || valid_id($request->order_id)==false  || $request->header('temptoken')=='' ){
            return response()->json(constants('bad_request'), constants('invalidResponse.statusCode'));
        }

        if(!isset($request->currentdatetime) || !is_numeric($request->currentdatetime) || ($request->currentdatetime + 3600 < time()) ){
                return response()->json([
                    'success' => constants('invalidResponse.success'),
                    'message' => "Time Out, Plese Refresh & Try Again.",
                    'data'    => ['order_id' => $request->order_id ],
                ], constants('invalidResponse.statusCode'));
        }

        if(CheckTempToken(constants('usertype.customer'),$request->get('devicetype'),$request->get('user_id'),$request->header('temptoken'))!=true) {
            return response()->json([
            'success' => constants('invalidResponse.success'),
            'message' => "Time Out, Please Refresh & Try Again.",
            'data'    => ['order_id' => $request->order_id ],
            ], constants('invalidResponse.statusCode')); 
        }
      
        $orderData = Order::with(['order_status', 'order_payment_status'])->where('is_active', constants('is_active_yes'))->whereIn('status', constants('order_status.approved_orders'))->where('user_id', $request->get('user_id'))->where('payment_status', constants('payment_status.Pending'))->where('id', $request->order_id)->first();

        if(empty($orderData)) {
            return response()->json([
              'success' => constants('invalidResponse.success'),
              'message' => "Oops, No Order Found.",
              'data'    => constants('emptyData'),
            ], constants('invalidResponse.statusCode')); 
        }

        $dataCustomer = Customer::where('id',$request->get('user_id'))->first(['wallet_credit']);

        if($dataCustomer->wallet_credit < $orderData->total_payable_amount){
            return response()->json([
                'success' => constants('invalidResponse.success'),
                'message' => "Sorry, Your Wallet Does Not Have Enough Credit.",
                'data'    => ['order_id' => $request->order_id, ],
            ], constants('invalidResponse.statusCode')); 
        }


        $updateDataOrderPlaced = [
            'payment_type' => constants('payment_type.Wallet'),
            'status' => "PU",
            'bigdaddy_lr_number' => createBigDaddyLrNumber(),
            'wallet_amount_used' => $orderData->total_payable_amount, 
            'prepaid_amount_used' => 0, 
            'cod_amount_used' =>  0, 
            'payment_status' => constants('payment_status.Paid'),
            'payment_type_manual' => constants('payment_type_manual.W.short'),
            'payment_datetime' => date('Y-m-d H:i:s'),
        ]; 


        $addDataWalletTransaction = [
            'transaction_amount' => 0,
            'transaction_credit' => $updateDataOrderPlaced['wallet_amount_used'],
            'transaction_type' => constants('transaction_type.Debit'),
            'user_id' => $request->get('user_id'),
            'transaction_datetime' => $updateDataOrderPlaced['payment_datetime'],
            'transaction_number' => "ORDERID_".$request->order_id,
            'notes' => "Used With Wallet Order",
            'order_id' => $request->order_id,
            'admin_id' => 0,
            'is_active' => constants('is_active_yes'),
            'is_manually_added' => 0,
        ];

        WalletTransaction::where('order_id', $request->order_id)->where('transaction_type', constants('transaction_type.Debit'))->where('user_id', $request->get('user_id'))->updateOrCreate($addDataWalletTransaction);        

        Order::where('id', $request->order_id)->update($updateDataOrderPlaced);

        Customer::where('id',$request->get('user_id'))->update(['wallet_credit' => $dataCustomer->wallet_credit - $updateDataOrderPlaced['wallet_amount_used'] ]);

        $updateDataRazorPayPayment = [
            'is_active' => 2,
        ];
        RazorPayPayment::where('order_id', $request->order_id)->whereNull('razorpay_signature')->where('is_active',constants('is_active_yes'))->update($updateDataRazorPayPayment);

        createOrderLogs("Order Placed With Wallet By Customer.", $request->order_id);
        createOrderLogs("Order Successfully Placed With Wallet.", $request->order_id, 1);
        pushNotificationToAdmin("Order Placed","Order Placed With Wallet By Customer.",'','', route('view-order')."/".$request->order_id);
        createAdminNotificationLogs("Order Placed","Order Placed With Wallet By Customer.", route('view-order')."/".$request->order_id);

        return response()->json([
            'success' => constants('validResponse.success'),
            'message' => 'Your Order Successfully Placed.',
            'data'    => ['bigdaddy_orderid' => $request->order_id ],
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
