<?php

namespace App\Http\Controllers\Client\Api\customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\AccessToken;
use Illuminate\Support\Facades\Validator;
use App\Models\Customer;
use App\Models\RazorPayPayment;
use App\Models\Order;
use App\Models\OrderArrange;
use App\Models\OrderParcel;
use App\Models\OrderReview;
use App\Models\GoodsType;
use App\Models\OrderFile;
use App\Models\SubscriptionPurchase;
use App\Models\CustomerUploadedFileOrder;
use Illuminate\Support\Facades\Log;
use App\Models\WalletTransaction;
use App\Models\ReasonFor;
use App\Models\Coupon;
date_default_timezone_set('Asia/Kolkata');
use Illuminate\Database\Eloquent\Builder;

class OrderController extends Controller
{

    public function __construct()
    {
        ini_set('max_execution_time', 300);
    }

   /*------get order listing with filters also -------*/
   public function getOrderListWithFilter(Request $request)
   {	
      try {
   		$searched = ''; $pageno = 0; $order_status = []; 


	   	if(isset($request->search) && trim($request->search)!=''){
	   		$searched = $request->search;
	   	}
	   	if(isset($request->order_status_type) && trim($request->order_status_type)!='' && array_key_exists($request->order_status_type, constants('order_status')) ){
	   		 $order_status = constants('order_status.'.$request->order_status_type);
	   	}

      if(isset($request->pageno)){
        $pageno = intval($request->pageno);
      }

      $perpage = 10;
      if(isset($request->perpage)){
        $perpage = (intval($request->perpage)>200) ? 200 : intval($request->perpage);
      }

      $orderCount = Order::where('user_id', $request->get('user_id'))->where('is_active', constants('is_active_yes'))->where(function($querySearch) use ($searched) {
          if($searched!=''){
            $querySearch->where('bigdaddy_lr_number', 'LIKE', $searched.'%');
            $querySearch->orWhere('drop_location', 'LIKE', '%'.$searched.'%');
            $querySearch->orWhere('pickup_location', 'LIKE', '%'.$searched.'%');
            $querySearch->orWhere('contact_person_name', 'LIKE', '%'.$searched.'%');
            $querySearch->orWhere('contact_person_phone_number', 'LIKE', '%'.$searched.'%');
            $querySearch->orWhere('transporter_name', 'LIKE', '%'.$searched.'%');
            $querySearch->orWhere('contact_person_name_drop', 'LIKE', '%'.$searched.'%');
            $querySearch->orWhere('contact_person_phone_number_drop', 'LIKE', '%'.$searched.'%');
            $querySearch->orWhere('transporter_name_drop', 'LIKE', '%'.$searched.'%');
            $querySearch->orWhere('final_cost', 'LIKE', $searched.'%');
            $querySearch->orWhere('transporter_lr_number', 'LIKE', $searched.'%');
            $querySearch->orWhere('total_payable_amount', 'LIKE', $searched.'%');
          }
          })
          ->where(function($queryOrder_status) use ($order_status) {
            if($order_status!=''){
              $queryOrder_status->whereIn('status', $order_status);
            }
          })->count();



	   		$orderData = Order::with(['order_status','order_payment_status','order_payment_type',
          'orderParcel' => function($qryOrderParcel) {
                $qryOrderParcel->with([
                    'goodsType' => function($qryGoodsType) {
                        $qryGoodsType->where('is_active','!=', 2);
                    },
                ]);
                $qryOrderParcel->where('is_active', constants('is_active_yes'));
            },
        ])
	   		->where(function($querySearch) use ($searched) {
	   			if($searched!=''){
	   				$querySearch->where('bigdaddy_lr_number', 'LIKE', $searched.'%');
            $querySearch->orWhere('drop_location', 'LIKE', '%'.$searched.'%');
            $querySearch->orWhere('pickup_location', 'LIKE', '%'.$searched.'%');
            $querySearch->orWhere('contact_person_name', 'LIKE', '%'.$searched.'%');
            $querySearch->orWhere('contact_person_phone_number', 'LIKE', '%'.$searched.'%');
            $querySearch->orWhere('transporter_name', 'LIKE', '%'.$searched.'%');
            $querySearch->orWhere('contact_person_name_drop', 'LIKE', '%'.$searched.'%');
            $querySearch->orWhere('contact_person_phone_number_drop', 'LIKE', '%'.$searched.'%');
            $querySearch->orWhere('transporter_name_drop', 'LIKE', '%'.$searched.'%');
            $querySearch->orWhere('final_cost', 'LIKE', $searched.'%');
            $querySearch->orWhere('transporter_lr_number', 'LIKE', $searched.'%');
            $querySearch->orWhere('total_payable_amount', 'LIKE', $searched.'%');
	   			}
        })
        ->where(function($queryOrder_status) use ($order_status) {
	   			if($order_status!=''){
	   				$queryOrder_status->whereIn('status', $order_status  );
	   			}
        })
	   		->where('is_active', constants('is_active_yes'))
	   		->where('user_id', $request->get('user_id'))
        ->offset($pageno*$perpage)
        ->limit($perpage)
	   		->orderBy('id','DESC')
        ->get();


        $orderList = []; 

	   		foreach ($orderData as $row) {
          
          $is_order_placeable = 0;
          if($row->payment_status==constants('payment_status.Pending') && in_array($row->status, constants('order_status.approved_orders'))){
              $is_order_placeable = 1;
          }
          $is_order_cancellable = 0;
          if($row->payment_status==constants('payment_status.Pending') && in_array($row->status, constants('order_status.temp_orders'))){
              $is_order_cancellable = 1;
          }
          $is_order_editable = 0;
          if($row->payment_status==constants('payment_status.Pending') && in_array($row->status, constants('order_status.requested_orders'))){
              $is_order_editable = 1;
          }

          /*--------------------------*/
          $orderParcelArray = []; 
          if(!empty($row->orderParcel)){
            foreach ($row->orderParcel as $key => $value) { 
              if(isset($value->goodsType->name)){ $orderParcelArray[] = $value->goodsType->name; }
            }
          }
          $orderParcelString = implode(', ', $orderParcelArray);
          /*---------------------------*/


	   			$orderList[] = [
	   				'id' => $row->id,
	   				'bigdaddy_lr_number' => $row->bigdaddy_lr_number,
            'transporter_lr_number' => $row->transporter_lr_number,
	   				'pickup_location' => $row->pickup_location,
	   				'drop_location' => $row->drop_location,
	   				'final_cost' => $row->final_cost,
	   				'total_no_of_parcel' => $row->total_no_of_parcel,
	   				'status' => $row->status,
            'statusName' => $row->order_status->name,
            'payment_status' => $row->payment_status,
            'payment_statusName' => $row->order_payment_status->name,
            'payment_type' => $row->payment_type,
            'payment_typeName' => $row->order_payment_type->name,
	   				'created_at' => Carbon::parse($row->created_at)->format('Y-m-d H:i:s'),
	   				'statusName' => $row->order_status->name,
	   				'payment_statusName' => $row->order_payment_status->name,
            'is_order_placeable' => $is_order_placeable,
            'is_order_cancellable' => $is_order_cancellable,
            'is_order_editable' => $is_order_editable,
            'total_payable_amount' => $row->total_payable_amount,
            'totalcost' => $row->total_payable_amount,
            'orderParcelString' => $orderParcelString,
	   			];
	   		}


	   		return response()->json([
                'success' => constants('validResponse.success'),
                'message' => "",
                'data'    => $orderList,
                'total_count'    => $orderCount,
                'per_page_count'    => $perpage,
                'searched'    => $searched,
                'order_status'    => $order_status,
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

   /*------get order listing with filters also -------*/
   public function getOrderListForTrackOrder(Request $request)
   { 
      try { 
      $searched = ''; $pageno = 0; $order_status = []; 
      $order_status = constants('order_status.active_orders_confirmed');

      if(isset($request->search) && trim($request->search)!=''){
        $searched = $request->search;
      }

      if(isset($request->pageno)){
        $pageno = intval($request->pageno);
      }

      $perpage = 10;
      if(isset($request->perpage)){
        $perpage = (intval($request->perpage)>200) ? 200 : intval($request->perpage);
      }

      $orderCount = Order::where('user_id', $request->get('user_id'))->where('is_active', constants('is_active_yes'))
        ->where(function($querySearch) use ($searched) {
          if($searched!=''){
            $querySearch->where('bigdaddy_lr_number', 'LIKE', $searched.'%');
            $querySearch->orWhere('drop_location', 'LIKE', '%'.$searched.'%');
            $querySearch->orWhere('pickup_location', 'LIKE', '%'.$searched.'%');
            $querySearch->orWhere('contact_person_name', 'LIKE', '%'.$searched.'%');
            $querySearch->orWhere('contact_person_phone_number', 'LIKE', '%'.$searched.'%');
            $querySearch->orWhere('transporter_name', 'LIKE', '%'.$searched.'%');
            $querySearch->orWhere('contact_person_name_drop', 'LIKE', '%'.$searched.'%');
            $querySearch->orWhere('contact_person_phone_number_drop', 'LIKE', '%'.$searched.'%');
            $querySearch->orWhere('transporter_name_drop', 'LIKE', '%'.$searched.'%');
            $querySearch->orWhere('final_cost', 'LIKE', $searched.'%');
            $querySearch->orWhere('transporter_lr_number', 'LIKE', $searched.'%');
            $querySearch->orWhere('total_payable_amount', 'LIKE', $searched.'%');
          }
          })
          ->where(function($queryOrder_status) use ($order_status) {
            if($order_status!=''){
              $queryOrder_status->whereIn('status', $order_status);
            }
          })->count();



        $orderData = Order::with([
          'order_status',
          'driver' =>  function($qryDriver) {
            },
            'orderParcel' => function($qryOrderParcel) {
                $qryOrderParcel->with([
                    'goodsType' => function($qryGoodsType) {
                        $qryGoodsType->where('is_active','!=', 2);
                    },
                ]);
                $qryOrderParcel->where('is_active', constants('is_active_yes'));
            },
         ])
        ->where(function($querySearch) use ($searched) {
          if($searched!=''){
            $querySearch->where('bigdaddy_lr_number', 'LIKE', $searched.'%');
            $querySearch->orWhere('drop_location', 'LIKE', '%'.$searched.'%');
            $querySearch->orWhere('pickup_location', 'LIKE', '%'.$searched.'%');
            $querySearch->orWhere('contact_person_name', 'LIKE', '%'.$searched.'%');
            $querySearch->orWhere('contact_person_phone_number', 'LIKE', '%'.$searched.'%');
            $querySearch->orWhere('transporter_name', 'LIKE', '%'.$searched.'%');
            $querySearch->orWhere('contact_person_name_drop', 'LIKE', '%'.$searched.'%');
            $querySearch->orWhere('contact_person_phone_number_drop', 'LIKE', '%'.$searched.'%');
            $querySearch->orWhere('transporter_name_drop', 'LIKE', '%'.$searched.'%');
            $querySearch->orWhere('final_cost', 'LIKE', $searched.'%');
            $querySearch->orWhere('transporter_lr_number', 'LIKE', $searched.'%');
            $querySearch->orWhere('total_payable_amount', 'LIKE', $searched.'%');
          }
        })
        ->where(function($queryOrder_status) use ($order_status) {
          if($order_status!=''){
            $queryOrder_status->whereIn('status', $order_status  );
          }
        })
        ->where('is_active', constants('is_active_yes'))
        ->where('user_id', $request->get('user_id'))
        ->offset($pageno*$perpage)
        ->limit($perpage)
        ->orderBy('id','DESC')
        ->get();


        $orderList = [];

        foreach ($orderData as $row) {

          /*--------------------------*/
          $orderParcelArray = []; 
          if(!empty($row->orderParcel)){
            foreach ($row->orderParcel as $key => $value) { 
              if(isset($value->goodsType->name)){ $orderParcelArray[] = $value->goodsType->name; }
            }
          }
          $orderParcelString = implode(', ', $orderParcelArray);
          /*---------------------------*/

          $orderList[] = [
            'id' => $row->id,
            'bigdaddy_lr_number' => $row->bigdaddy_lr_number,
            'transporter_lr_number' => $row->transporter_lr_number,
            'total_no_of_parcel' => $row->total_no_of_parcel,
            'created_at' => Carbon::parse($row->created_at)->format('d/m h:i A'),
            'driver_assigned_datetime' => isset($row->driver_assigned_datetime) ? Carbon::parse($row->driver_assigned_datetime)->format('d/m h:i A') : '',
            'pickedup_datetime' => isset($row->pickedup_datetime) ? Carbon::parse($row->pickedup_datetime)->format('d/m h:i A') : '',
            'delivered_datetime' => isset($row->delivered_datetime) ? Carbon::parse($row->delivered_datetime)->format('d/m h:i A') : '',
            'pickup_location' => $row->pickup_location,
            'drop_location' => $row->drop_location,
            'final_cost' => $row->final_cost,
            'total_payable_amount' => $row->total_payable_amount,
            'totalcost' => $row->total_payable_amount,
            'pickup_latitude' => $row->pickup_latitude,
            'pickup_longitude' => $row->pickup_longitude,
            'drop_latitude' => $row->drop_latitude,
            'drop_longitude' => $row->drop_longitude,
            'order_status' => $row->order_status,
            'orderParcelString' => $orderParcelString,
            'driver' => [
              "fullname" => isset($row->driver->fullname) ? $row->driver->fullname : '',
              "mobile" => isset($row->driver->mobile) ? $row->driver->mobile : '',
              "profile_pic" => isset($row->driver->profile_pic) ? $row->driver->profile_pic : '',
              "current_latitude" => isset($row->driver->current_latitude) ? $row->driver->current_latitude : '',
              "current_longitude" => isset($row->driver->current_longitude) ? $row->driver->current_longitude : '',
            ],
            'vehicle' => [
              "vehicle_no" => isset($row->vehicle_no) ? $row->vehicle_no : '',
            ],
            'fileurl' => sendPath().constants('dir_name.order'),
            'fileurl2' => sendPath().constants('dir_name.driver'),
          ];
        }

        return response()->json([
                'success' => constants('validResponse.success'),
                'message' => "",
                'data'  => $orderList,
                'total_count'  => $orderCount,
                'per_page_count'  => $perpage,
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

	   /*------getOrderDetail of an order-------*/
   public function getOrderDetail(Request $request)
   {
      try{
   		if(!isset($request->order_id) || valid_id($request->order_id)==false){
	   		  return response()->json(constants('somethingWentWrong'), constants('invalidResponse.statusCode'));
	   	}	

	   		$orderData = Order::with([ 'order_status', 'order_payment_status','order_logs_user', 'invoice',
          'orderParcel' => function($qryOrderParcel) {
                $qryOrderParcel->with([
                    'goodsType' => function($qryGoodsType) {
                        $qryGoodsType->where('is_active','!=', 2);
                    },
                ]);
                $qryOrderParcel->where('is_active', constants('is_active_yes'));
            },
            
                'driver' => function ($qryDriver) {
                	$qryDriver->select('tbl_drivers.id','tbl_drivers.fullname','tbl_drivers.mobile'); 
                },
                'orderFile' => function ($qryOrderFile) {
                  $qryOrderFile->with([
                    'filelabel' => function($qryfilelabel) {
                    },
                ]);
                },
                /*'razorpay_payment' => function ($qryRazorpay_payment) {
                    $qryRazorpay_payment->where('is_active', constants('is_active_yes'));
                },*/
	   		])
	   		->where('is_active', constants('is_active_yes'))
	   		->where('user_id', $request->get('user_id'))
	   		->where('id', $request->order_id)
	   		->first();

        if(!empty($orderData)){

          $is_order_placeable = 0; $invoicefileurl = '';
          if($orderData->payment_status==constants('payment_status.Pending') && in_array($orderData->status, constants('order_status.approved_orders'))){
              $is_order_placeable = 1;
          }
          $is_order_cancellable = 0;
          if($orderData->payment_status==constants('payment_status.Pending') && in_array($orderData->status, constants('order_status.temp_orders'))){
              $is_order_cancellable = 1;
          }
          $is_order_editable = 0;
          if($orderData->payment_status==constants('payment_status.Pending') && in_array($orderData->status, constants('order_status.requested_orders'))){
              $is_order_editable = 1;
          }


          if(isset($orderData->invoice->invoice_file)){
              $invoicefileurl = sendPath().constants('dir_name.invoice').'/'.$orderData->invoice->invoice_file;
          }

          return response()->json([
                'success' => constants('validResponse.success'),
                'message' => "",
                'data'    => $orderData,
                'order_details' => [ 
                  'is_order_placeable' => $is_order_placeable,
                  'is_order_cancellable' => $is_order_cancellable,
                  'is_order_editable' => $is_order_editable,
                  'invoicefileurl' => $invoicefileurl,
                ],
                'fileurl' => sendPath().constants('dir_name.order'),
          ], constants('validResponse.statusCode'));

        }
        else
        {
          return response()->json([
            'success' => constants('invalidResponse.success'),
            'message' => "Oops, No Order Found.",
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

     /*------get Last Order Detail of an order-------*/
   public function getLastOrderDetail(Request $request)
   {
      try{

        $orderData = Order::with([ 
          'orderParcel' => function($qryOrderParcel) {
                $qryOrderParcel->with([
                    'goodsType' => function($qryGoodsType) {
                        $qryGoodsType->where('is_active','!=', 2);
                    },
                ]);
                $qryOrderParcel->where('is_active', constants('is_active_yes'));
            },
         ])
          ->where('is_active', constants('is_active_yes'))
          ->where('user_id', $request->get('user_id'))
          ->orderBy('id','DESC')->first();

          return response()->json([
                'success' => constants('validResponse.success'),
                'message' => "",
                'data'    => $orderData,
                'fileurl' => sendPath().constants('dir_name.order'),
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



       /*------upload Lr File To Create Order file must be xls or image-------*/
   public function uploadLrFileToCreateOrder(Request $request)
   {	
      try{
   		$validator = Validator::make($request->all(), [ 
          'lrfile'=> 'required|mimes:pdf,jpg,jpeg,png,bmp,csv,xlt,xls,xlsx,xlsb,xlsm,xltx,xltm,txt,rtf|max:5120',
      ]);   

		  if($validator->fails()) {          
            return response()->json([
		                'success' => constants('invalidResponse.success'),
		                'message' => "Please Provide a Valid File Type Under 5MB.",
		                'data'    => constants('emptyData'),
		        ], constants('invalidResponse.statusCode'));                       
         }
         else 
         {

         	$uploadfile = ''; 
         	$uploadfile = UploadImage($request->file('lrfile'), constants('dir_name.customer'),'LR_');

           	$insertData = [
                'lrfile' => $uploadfile,
                'user_id' => $request->get('user_id'),
                'is_active' => constants('is_active_yes'),
                ];

            $dataLast = CustomerUploadedFileOrder::create($insertData);
            pushNotificationToAdmin("New LR Uploaded","New LR File Uploaded For New Order.",'','',route('lr-upload-list')); 
            createAdminNotificationLogs("New LR Uploaded","New LR File Uploaded For New Order.", route('lr-upload-list'));

           	return response()->json([
                'success' => constants('validResponse.success'),
                'message' => "You LR File has been Submitted Successfully, Please Wait For The Best Quotation.",
                'data'    => $dataLast->id,
                'fileurl' => sendPath().constants('dir_name.customer'),
            ], constants('validResponse.statusCode'));
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


   /*------rate n review to delivered orders-------*/
   public function reviewOrder(Request $request)
   {	
      try{
   		$validator = Validator::make($request->all(), [ 
            'driver_star' => 'required|integer|between:1,5',
            'bigdaddy_service_star' => 'required|integer|between:1,5',
            'order_id' => 'required|integer|min:1',
            'headline' => 'nullable|string|max:255',
            'review' => 'nullable|string|max:1000',
        ]);   

		    if($validator->fails())
		    {          
            return response()->json([
              'success' => constants('invalidResponse.success'),
              'message' => $validator->errors()->first(),
              'data'    => constants('emptyData'),
          ], constants('invalidResponse.statusCode'));                  
        }
        else 
        {
        	$count = Order::where('is_active', constants('is_active_yes'))->where('user_id', $request->get('user_id'))->where('id', $request->order_id)->count();

        	if($count>0){

        		$count1 = OrderReview::where('user_id', $request->get('user_id'))->where('order_id', $request->order_id)->count();

        		if($count1>0){
              	return response()->json([
      				    'success' => constants('invalidResponse.success'),
      				    'message' => "You have Already Reviewed This LR Number Order.",
      				    'data'    => ["order_id" => $request->order_id],
      				  ], constants('invalidResponse.statusCode'));
        		}

        		$orderReviewData = [
         				'user_id' => $request->get('user_id'),
         				'driver_star' => $request->driver_star,
                'bigdaddy_service_star' => $request->bigdaddy_service_star,
                'order_id' => $request->order_id,
                'subject' => $request->subject,
                'message' => $request->message,
   				 ];

   				$lastData =  OrderReview::create($orderReviewData);

        		return response()->json([
                'success' => constants('validResponse.success'),
                'message' => "You Review has been Submitted Successfully.",
                'data'    => ["order_id" => $request->order_id, "review_id" => $lastData->id],
            	], constants('validResponse.statusCode'));
        	}
        	else
        	{
        		return response()->json([
			    'success' => constants('invalidResponse.success'),
			    'message' => "No Order Found to Review.",
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


      /*------CreateNewOrder-------*/
    public function createNewOrderRequest(Request $request) 
    {
      try {
        $validator = Validator::make($request->all(), [ 
              //'fileLRP.*' => 'nullable|mimes:pdf,jpg,jpeg,png,bmp,csv,xlt,xls,xlsx,xlsb,xlsm,xltx,xltm,txt,rtf|max:5050',
              'transporter_lr_number' => 'nullable|string|max:55',
              'fileLRP' => 'nullable|string',
              'pickup_location' => 'required',
              'drop_location' => 'required',
              'pickup_latitude' => 'required',
              'pickup_longitude' => 'required',
              'drop_latitude' => 'required',
              'drop_longitude' => 'required',
              'pickup_contact_person_name' => 'required',
              'pickup_contact_person_phone_number' => 'required',
              'drop_contact_person_phone_number' => 'required',
              'drop_transporter_name' => 'required',
              'drop_contact_person_name' => 'required',
              'transport_cost' => 'required|between:0,99999999999999999.99',
              'customer_estimation_asset_value'=> 'nullable|between:0,99999999999999999.99',
              'pickup_transporter_name' => 'nullable|string',
              'goods_type_id.*' => 'required|integer|min:1',
              'no_of_parcel.*' => 'required|integer|min:1',
              'other_text' => 'required',
              'goods_weight.*' => 'required',
              'total_weight.*' => 'required',
        ]);   

        if($validator->fails())
        { 
          return response()->json([
              'success' => constants('invalidResponse.success'),
              'message' => $validator->errors()->first(),
              'data'    => constants('emptyData'),
          ], constants('invalidResponse.statusCode'));
        }
        else if( !is_array($request->goods_type_id) || !is_array($request->no_of_parcel) || !is_array($request->goods_weight) || !is_array($request->total_weight) || !is_array($request->other_text) || empty($request->goods_type_id) || empty($request->no_of_parcel) || empty($request->goods_weight) || empty($request->total_weight) || empty($request->other_text)) 
        {          
            return response()->json(constants('bad_request'), constants('invalidResponse.statusCode'));                  
        }
        else
        {

          $oneMinutueAgo = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . ' -1 minutes'));
          $oneMinutuePlus = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . ' +1 minutes'));

          $lastOrder = Order::whereBetween('created_at', [$oneMinutueAgo, $oneMinutuePlus])->where('is_active', constants('is_active_yes'))->where('user_id', $request->get('user_id'))->orderBy('id','DESC')->count();

          if($lastOrder>0){
              return response()->json([
              'success' => constants('invalidResponse.success'),
              'message' => "Please, Wait At Least 1 Minute For Next Order.",
              'data'    => constants('emptyData'),
              ], constants('invalidResponse.statusCode'));
          }

          

        	$orderData = [
            'bigdaddy_lr_number' => NULL,
            'transporter_lr_number' => isset($request->transporter_lr_number) ? trim($request->transporter_lr_number) : NULL,
            'user_id' => $request->get('user_id'),
            'pickup_location' => $request->pickup_location,
            'drop_location' => $request->drop_location,
            'pickup_location' => $request->pickup_location,
            'pickup_latitude' => floatval($request->pickup_latitude),
            'pickup_longitude' => floatval($request->pickup_longitude),
            'drop_latitude' => floatval($request->drop_latitude),
            'drop_longitude' => floatval($request->drop_longitude),
            'contact_person_name' => $request->pickup_contact_person_name,
            'contact_person_phone_number' => $request->pickup_contact_person_phone_number,
            'transporter_name' => isset($request->pickup_transporter_name) ? $request->pickup_transporter_name : '',
            'contact_person_name_drop' => $request->drop_transporter_name,
            'contact_person_phone_number_drop' => $request->drop_contact_person_phone_number,
            'transporter_name_drop' => isset($request->drop_transporter_name) ? $request->drop_transporter_name : '',
			      'customer_estimation_asset_value' => isset($request->customer_estimation_asset_value) ? floatval($request->customer_estimation_asset_value) : 0,
            'other_field_pickup' => isset($request->other_field_pickup) ? $request->other_field_pickup : NULL,
            'other_field_drop' => isset($request->other_field_drop) ? $request->other_field_drop : NULL,
            'transport_cost' => floatval($request->transport_cost),
            'total_weight' => 0,
            'total_no_of_parcel' => 0,
            'min_order_value_charge' => 0,
            'redeliver_charge' => 0,
            'discount' => 0,
            'final_cost' => 0,
            'status' => 'RO',
            'is_active' => constants('is_active_yes'),
            'payment_type_manual' => constants('payment_type_manual.CS.short'),
            'payment_status' => constants('payment_status.Pending'),
        	];


          CreateApiLogs($orderData);

          $lastOrderData = Order::create($orderData);

                $total_weightO = 0;
                $total_no_of_parcelO = 0;
                $tempo_chargeO = 0;
                $service_chargeO = 0;
                $delivery_chargeO = 0;
                $i = 0;


                /*----parcel create--------*/
                foreach ($request->goods_type_id as $key => $value) {

                $delivery_charge_i =  0;

                $total_weightO += floatval($request->total_weight[$i]);
                $total_no_of_parcelO += intval($request->no_of_parcel[$i]);
                $tempo_chargeO += 0;
                $service_chargeO += 0;
                $delivery_chargeO += $delivery_charge_i;


                $parcelData = [
                'order_id' => $lastOrderData->id,
                'no_of_parcel' => intval($request->no_of_parcel[$i]),
                'goods_type_id' => intval($request->goods_type_id[$i]),
                'goods_weight' => floatval($request->goods_weight[$i]),
                'total_weight' => floatval($request->total_weight[$i]),
                'estimation_value' => isset($request->estimation_value[$i]) ? floatval($request->estimation_value[$i]) : 0,
                'tempo_charge' => 0,
                'service_charge' => 0,
                'delivery_charge' => $delivery_charge_i,
                'is_active' => constants('is_active_yes'),
                ];

                if($request->goods_type_id[$i]==constants('goods_type_id_other')){
                    $parcelData['other_text'] = $request->other_text[$i];
                }
                else{
                    $parcelData['other_text'] = NULL;
                }


                OrderParcel::create($parcelData);
                    $i++;
                } /*----parcel create--------*/



                if(isset($request->fileLRP) && strlen($request->fileLRP)>50){
                    $filename_dx = UploadImageFromBase64String($request->fileLRP, constants('dir_name.order'),constants('order_file_type.lrpickup'));
                    if($filename_dx!=''){
                        $dt = [ 'order_id' => $lastOrderData->id, 'img' => $filename_dx, 'img_type' => constants('order_file_type.lrpickup'),];
                        OrderFile::create($dt);
                    }
                }
                /*$upload_fileLRP = []; $upload_fileLRP_count = 0;
                if(is_array($request->file('fileLRP')) && !empty($request->file('fileLRP'))){
                    foreach($request->file('fileLRP') as $fx) {
                        $upload_fileLRP_name = UploadImage($fx, constants('dir_name.order'),constants('order_file_type.lrpickup'));
                        $upload_fileLRP[] = $upload_fileLRP_name;
                        $upload_fileLRP_count++;
                    }
                }
                if($upload_fileLRP_count>0){
                    foreach($upload_fileLRP as $filename_dx) {
                        $dt = [ 'order_id' => $lastOrderData->id, 'img' => $filename_dx, 'img_type' => constants('order_file_type.lrpickup'),];
                        OrderFile::create($dt);
                    }
                }*/


                $orderDataUpdate = [
                'total_no_of_parcel' => $total_no_of_parcelO,
                'total_weight' => $total_weightO,
                'tempo_charge' => $tempo_chargeO,
                'service_charge' => $service_chargeO,
                'final_cost' => $delivery_chargeO,
                ];

                Order::where('user_id', $lastOrderData->user_id)->where('id', $lastOrderData->id)->where('is_active', constants('is_active_yes'))->update($orderDataUpdate);
                createOrderLogs("Order Requested.",$lastOrderData->id);
                createOrderLogs("Order Requested.", $lastOrderData->id, 1);
                createAdminNotificationLogs("New Order","New Order Requested By Customer.",route('tobeapproved-orders'));
                pushNotificationToAdmin("New Order","New Order Requested By Customer.",'','',route('tobeapproved-orders'));

                return response()->json([
                    'success' => constants('validResponse.success'),
                    'message' => "You Order has been Submitted Successfully, Please Wait For The Best Quotation.",
                    'data'    => [ 'order_id' => $lastOrderData->id ] ,
                    'fileurl' => sendPath().constants('dir_name.order'),
                ], constants('validResponse.statusCode'));
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

    /*------getGoodsType-------*/
   public function getGoodsType(Request $request) {
      try{
        $searched = '';  $goodsDataList = []; 
        if(isset($request->search) && trim($request->search)!=''){
          $searched = $request->search;
          $searched = str_replace("'", "", $searched );  
        }

        $goodsTypeData = GoodsType::where('is_active', constants('is_active_yes'))->where('name','LIKE', '%'.$searched.'%' )->orderBy('name','ASC')->limit(250)->get();

        if(!empty($goodsTypeData)){
          foreach ($goodsTypeData as $key => $value) {
            $goodsDataList[] = [
                "id" => $value->id,
                "text" => $value->name,
                ];
            }
        }

        return response()->json([
            'success' => constants('validResponse.success'),
            'message' => "",
            'data'    => $goodsDataList,
            'count'    => count($goodsDataList),
            'searched'    => $searched,
            'goods_type_id_other'   => constants('goods_type_id_other'),
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

    /*------get selected reason lists limit 250-------*/
   public function getReasonForList(Request $request)
   {
      try {

      $validator = Validator::make($request->all(), [ 
          'reasonfor' => 'required',
      ]);   

      if($validator->fails()) {          
          return response()->json(constants('checkRequiredField'), constants('invalidResponse.statusCode'));
      }

      $reasonforData = ReasonFor::where('type', $request->reasonfor)->where('is_active', constants('is_active_yes'))->limit(constants('extra_large_limit'))->orderBy('id','ASC')->get();

      return response()->json([
            'success' => constants('validResponse.success'),
            'message' => "",
            'data'    => [
              'reasonforData' => $reasonforData, 
              'countreasonforData'   => count($reasonforData), 
              'otherordercancelledreasonid' => constants('otherordercancelledreasonid'),
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

      /*------get payment status of an order-------*/
   public function getOrderDetailWithPaymentStatus_old(Request $request)
   {  
    
      try {
        
      if(!isset($request->order_id) || valid_id($request->order_id)==false){
          return response()->json(constants('bad_request'), constants('invalidResponse.statusCode'));
      }

      $orderDataCount = Order::where('is_active', constants('is_active_yes'))->where('user_id', $request->get('user_id'))->where('id', $request->order_id)->count();
      if($orderDataCount==0)
        {
          return response()->json([
            'success' => constants('invalidResponse.success'),
            'message' => "Oops, No Order Found.",
            'data'    => constants('emptyData'),
          ], constants('invalidResponse.statusCode')); 
        }

        $orderData = Order::with([ 'order_status', 'order_payment_status', 'order_logs_user','orderParcel','customer',
                /*'razorpay_payment' => function ($qryRazorpay_payment) {
                     $qryRazorpay_payment->where('is_active', constants('is_active_yes'))->whereNull('razorpay_signature');
                },*/
        ])
        ->where('is_active', constants('is_active_yes'))
        ->where('user_id', $request->get('user_id'))
        ->where('id', $request->order_id)
        ->first();

        $is_order_placeable = 0; $currentdatetime = 0;  $totalCost = 0; $dataLastSubscriptionPurchaseValid = []; 
        $is_walletcredit_usable_forthisorder_inCOD_ONLINE = 0;
        $is_walletorder_available = 0;

        if($orderData->payment_status==constants('payment_status.Pending') && in_array($orderData->status, constants('order_status.approved_orders'))){
            $is_order_placeable = 1;
            $currentdatetime = time();
            $dataLastSubscriptionPurchaseValid = SubscriptionPurchase::whereColumn('amount_used','<', 'maximum_discount_amount')->where('expired_datetime', '>', date('Y-m-d H:i:s'))->where('user_id', $request->get('user_id'))->orderBy('id','DESC')->where('is_active', constants('is_active_yes'))->limit(1)->get();

          if($orderData->total_payable_amount > $orderData->customer->wallet_credit){
              $is_walletcredit_usable_forthisorder_inCOD_ONLINE = $orderData->customer->wallet_credit;
          }
          else 
          {
              $is_walletorder_available = $orderData->total_payable_amount;
          }
        }

       
        return response()->json([
            'success' => constants('validResponse.success'),
            'is_order_placeable'  => $is_order_placeable,
            'currentdatetime'  => $currentdatetime,
            'fileurl' => sendPath().constants('dir_name.order'),
            'message' => "",
            'data'    => [ 
              "orderData" => $orderData, 
              'is_subscription_available' => count($dataLastSubscriptionPurchaseValid),
              'lastSubscriptionPurchase' => $dataLastSubscriptionPurchaseValid,
              'is_walletcredit_usable_forthisorder_inCOD_ONLINE' => $is_walletcredit_usable_forthisorder_inCOD_ONLINE,
              'is_walletorder_available' => $is_walletorder_available,
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


      /*------get payment status of an order-------*/
   public function getOrderDetailWithPaymentStatus(Request $request)
    { 

     try {
        if(!isset($request->order_id) || valid_id($request->order_id)==false){
            return response()->json(constants('bad_request'), constants('invalidResponse.statusCode'));
        }

        $dataReturned = $this->returnOrderDetailWithPaymentStatus($request->order_id, $request->get('user_id'));
        return response()->json($dataReturned['data'], $dataReturned['status_code']);

      } catch(\Exception $e) {
            Log::error($e);
            return response()->json([
            'success' => constants('invalidResponse.success'),
            'message' => "Error! Something Went Wrong.",
            'data'    => constants('emptyData'),
            ], constants('invalidResponse.statusCode'));
        } 
    }
      /*------get payment status of an order-------*/
   public function returnOrderDetailWithPaymentStatus($order_id=0, $user_id=0)
   {  
    
      try {
        
      if(valid_id($order_id)==false){
          return [ 'status_code' => constants('invalidResponse.statusCode'), 'data' => constants('bad_request') ];
      }

      $orderDataCount = Order::where('is_active', constants('is_active_yes'))->where('user_id', $user_id)->where('id', $order_id)->count();
      if($orderDataCount==0)
        {
          return [ 'status_code' => constants('invalidResponse.statusCode'), 'data' => [
            'success' => constants('invalidResponse.success'),
            'message' => "Oops, No Order Found.",
            'data'    => constants('emptyData'),
          ] ];

        }

        $orderData = Order::with([ 'order_status', 'order_payment_status', 'order_logs_user','orderParcel','customer',
                /*'razorpay_payment' => function ($qryRazorpay_payment) {
                     $qryRazorpay_payment->where('is_active', constants('is_active_yes'))->whereNull('razorpay_signature');
                },*/
        ])
        ->where('is_active', constants('is_active_yes'))
        ->where('user_id', $user_id)
        ->where('id', $order_id)
        ->first();

        $is_order_placeable = 0; $currentdatetime = 0;  $totalCost = 0; $dataLastSubscriptionPurchaseValid = []; 
        $is_walletcredit_usable_forthisorder_inCOD_ONLINE = 0;
        $is_walletorder_available = 0;

        if($orderData->payment_status==constants('payment_status.Pending') && in_array($orderData->status, constants('order_status.approved_orders'))){
            $is_order_placeable = 1;
            $currentdatetime = time();
            $dataLastSubscriptionPurchaseValid = SubscriptionPurchase::whereColumn('amount_used','<', 'maximum_discount_amount')->where('expired_datetime', '>', date('Y-m-d H:i:s'))->where('user_id', $user_id)->orderBy('id','DESC')->where('is_active', constants('is_active_yes'))->limit(1)->get();

          if($orderData->total_payable_amount > $orderData->customer->wallet_credit){
              $is_walletcredit_usable_forthisorder_inCOD_ONLINE = $orderData->customer->wallet_credit;
          }
          else 
          {
              $is_walletorder_available = $orderData->total_payable_amount;
          }
        }

        $is_order_cancellable = 0;
        if($orderData->payment_status==constants('payment_status.Pending') && in_array($orderData->status, constants('order_status.temp_orders'))){
              $is_order_cancellable = 1;
        }
        $is_order_editable = 0;
        if($orderData->payment_status==constants('payment_status.Pending') && in_array($orderData->status, constants('order_status.requested_orders'))){
              $is_order_editable = 1;
        }


        return [ 'status_code' => constants('validResponse.statusCode'), 'data' => [
            'success' => constants('validResponse.success'),
            'is_order_placeable'  => $is_order_placeable,
            'is_order_cancellable'  => $is_order_cancellable,
            'is_order_editable'  => $is_order_editable,
            'currentdatetime'  => $currentdatetime,
            'fileurl' => sendPath().constants('dir_name.order'),
            'message' => "",
            'data'    => [ 
              "orderData" => $orderData, 
              'is_subscription_available' => count($dataLastSubscriptionPurchaseValid),
              'lastSubscriptionPurchase' => $dataLastSubscriptionPurchaseValid,
              'is_walletcredit_usable_forthisorder_inCOD_ONLINE' => $is_walletcredit_usable_forthisorder_inCOD_ONLINE,
              'is_walletorder_available' => $is_walletorder_available,
            ],
        ] ];

        } catch(\Exception $e) {
          Log::error($e);
          return [ 'status_code' => constants('invalidResponse.statusCode'), 'data' => [
            'success' => constants('invalidResponse.success'),
            'message' => "Error! Something Went Wrong.",
            'data'    => constants('emptyData'),
            ] ];
        } 
   }
 

    /*------place order with cash on delivery-------*/
   public function placeOrderWithCOD(Request $request)
   {

      try{
        
        if(!isset($request->payment_type) || !isset($request->order_id) || $request->payment_type!=constants('payment_type.COD') || valid_id($request->order_id)==false  || $request->header('temptoken')=='' || !isset($request->is_wallet_amount) || !isset($request->use_wallet_amount) || !in_array($request->is_wallet_amount, constants('confirmation'))){
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

        if($request->is_wallet_amount==constants('confirmation.yes') && $request->use_wallet_amount>0 && $dataCustomer->wallet_credit>0 && $dataCustomer->wallet_credit >= $dataCustomer->use_wallet_amount && $orderData->total_payable_amount > $request->use_wallet_amount) {
            $updateDataOrderPlaced = [
                'payment_type' => constants('payment_type.COD'),
                'status' => "PU",
                'bigdaddy_lr_number' => createBigDaddyLrNumber(),
                'wallet_amount_used' => $request->use_wallet_amount, 
                'prepaid_amount_used' => 0, 
                'cod_amount_used' =>  $orderData->total_payable_amount - $request->use_wallet_amount, 
                'payment_status' => constants('payment_status.Pending'),
                'payment_type_manual' => constants('payment_type_manual.CS.short'),
            ]; 

            
            $addDataWalletTransaction = [
                    'transaction_amount' => 0,
                    'transaction_credit' => $updateDataOrderPlaced['wallet_amount_used'],
                    'transaction_type' => constants('transaction_type.Debit'),
                    'user_id' => $request->get('user_id'),
                    'transaction_datetime' => date('Y-m-d H:i:s'),
                    'transaction_number' => "ORDERID_".$request->order_id,
                    'notes' => "Used With Wallet Order",
                    'order_id' => $request->order_id,
                    'admin_id' => 0,
                    'is_active' => constants('is_active_yes'),
                    'is_manually_added' => 0,
                ];

            WalletTransaction::where('order_id', $request->order_id)->where('transaction_type', constants('transaction_type.Debit'))->where('user_id', $request->get('user_id'))->updateOrCreate($addDataWalletTransaction);        
        }
        else
        {
            $updateDataOrderPlaced = [
                'payment_type' => constants('payment_type.COD'),
                'status' => "PU",
                'bigdaddy_lr_number' => createBigDaddyLrNumber(),
                'wallet_amount_used' => 0, 
                'prepaid_amount_used' => 0, 
                'cod_amount_used' =>  $orderData->total_payable_amount, 
                'payment_status' => constants('payment_status.Pending'),
                'payment_type_manual' => constants('payment_type_manual.CS.short'),
            ];
        }
           
        Order::where('id', $request->order_id)->update($updateDataOrderPlaced);

        Customer::where('id',$request->get('user_id'))->update(['wallet_credit' => $dataCustomer->wallet_credit - $updateDataOrderPlaced['wallet_amount_used'] ]);

        $updateDataRazorPayPayment = [
            'is_active' => 2,
        ];
        RazorPayPayment::where('order_id', $request->order_id)->whereNull('razorpay_signature')->where('is_active',constants('is_active_yes'))->update($updateDataRazorPayPayment);

        createOrderLogs("Order Placed With COD By Customer.", $request->order_id);
        createOrderLogs("Order Successfully Placed With COD.", $request->order_id, 1);
        pushNotificationToAdmin("Order Placed","Order Placed With COD By Customer.",'','', route('view-order')."/".$request->order_id);
        createAdminNotificationLogs("Order Placed","Order Placed With COD By Customer.", route('view-order')."/".$request->order_id);

        return response()->json([
            'success' => constants('validResponse.success'),
            'message' => 'Order Successfully Placed.',
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


   public function preCheckCODValidation(Request $request)
   {    
        try {

        $validator = Validator::make($request->all(), [ 
            'order_id' => 'required|integer|min:1',
            'payment_type' => 'required',
            'is_wallet_amount' => 'required',
            'use_wallet_amount' => 'required',
            'currentdatetime' => 'required',
        ]);   

        if($validator->fails() || !in_array($request->is_wallet_amount, constants('confirmation')) || $request->payment_type!=constants('payment_type.COD')) {  
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


        if($request->is_wallet_amount==constants('confirmation.yes') && $request->use_wallet_amount > 0){
          $dataCustomer = Customer::where('id',$request->get('user_id'))->first(['wallet_credit']);

          if($dataCustomer->wallet_credit < $request->use_wallet_amount ){
            return response()->json([
                'success' => constants('invalidResponse.success'),
                'message' => "Sorry, Your Wallet Does Not Have Enough Credit.",
                'data'    => constants('emptyData'),
            ], constants('invalidResponse.statusCode')); 
          }

          if($dataCustomer->wallet_credit > 0 && $orderData->total_payable_amount <= $request->use_wallet_amount ){
            return response()->json([
                'success' => constants('invalidResponse.success'),
                'message' => "Please Use, Wallet or Prepaid Method to Confirm This Order.",
                'data'    => constants('emptyData'),
            ], constants('invalidResponse.statusCode')); 
          }
        }
        else
        {
            $request->use_wallet_amount = 0;
        }


        return response()->json([
            'success' => constants('validResponse.success'),
            'message' => "",
            'data'    => [
              'order_id' => $request->order_id, 
              'payment_type' => constants('payment_type.COD'),
              'is_wallet_amount' => $request->is_wallet_amount,
              'use_wallet_amount' => $request->use_wallet_amount,
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
 

     /*------getDownloadOrderInvoiceFile of an order-------*/
   public function getDownloadOrderInvoiceFile(Request $request)
   {
      try {

      if(!isset($request->order_id) || valid_id($request->order_id)==false){
          return response()->json(constants('somethingWentWrong'), constants('invalidResponse.statusCode'));
      } 

        $orderData = Order::with(['invoice',])
        ->where('is_active', constants('is_active_yes'))
        ->where('user_id', $request->get('user_id'))
        ->where('id', $request->order_id)
        ->first();

        if(!empty($orderData)){

          if(isset($orderData->invoice->invoice_file)){
              $path = sendPath().constants('dir_name.invoice').'/'.$orderData->invoice->invoice_file;
              return response()->json([
                'success' => constants('validResponse.success'),
                'message' => "",
                'data'    => [ 'url' => $path, ],
              ], constants('validResponse.statusCode'));
          }
          else
          {
              return response()->json([
              'success' => constants('invalidResponse.success'),
              'message' => "Oops, No Order Invoice Found Or Not Created Yet, Please Check After Order is Delivered.",
              'data'    => constants('emptyData'),
              ], constants('invalidResponse.statusCode'));

          }
        }
        else
        {
          return response()->json([
            'success' => constants('invalidResponse.success'),
            'message' => "Oops, No Order Found.",
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

    /*------checks & validates if order is cancellable or not-------*/
   public function preCheckOrderCancelValidation(Request $request)
   {   
        //header("Access-Control-Allow-Origin: *");

        try {

        $validator = Validator::make($request->all(), [ 
            'order_id' => 'required|integer|min:1',
            'currentdatetime' => 'required',
        ]);   

        if($validator->fails()) {  
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

        $orderData = Order::where('is_active', constants('is_active_yes'))->whereIn('status', constants('order_status.temp_orders'))->where('user_id', $request->get('user_id'))->where('payment_status', constants('payment_status.Pending'))->where('id', $request->order_id)->first(['id']);

        if(empty($orderData)) {
            return response()->json([
                'success' => constants('invalidResponse.success'),
                'message' => "Oops, No Valid Order Found.",
                'data'    => constants('emptyData'),
            ], constants('invalidResponse.statusCode')); 
        }

        $reasonforData = ReasonFor::where('type', 'ordercancelled_reason')->where('is_active', constants('is_active_yes'))->limit(100)->orderBy('id','ASC')->get();

        $temptoken = CreateTempToken(constants('usertype.customer'),$request->get('devicetype'),$request->get('user_id'));

        return response()->json([
            'success' => constants('validResponse.success'),
            'message' => "",
            'data'    => [
              'order_id' => $orderData->id, 
              'currentdatetime' => time(),
              'reasonforData' => $reasonforData, 
              'countreasonforData'   => count($reasonforData), 
              'otherordercancelledreasonid' => constants('otherordercancelledreasonid'),
            ],
            'temptoken' => $temptoken,
        ], constants('validResponse.statusCode'))
        ->withHeaders([
            'temptoken' => $temptoken,
        ]); 

        } catch(\Exception $e) {
            Log::error($e);
            return response()->json([
            'success' => constants('invalidResponse.success'),
            'message' => "Error! Something Went Wrong.",
            'data'    => constants('emptyData'),
            ], constants('invalidResponse.statusCode'));
        } 
   }


    /*------cancel an Order by customer-------*/
   public function cancelThisOrderBeforePlaceOrder(Request $request)
   {

      try {

        $validator = Validator::make($request->all(), [ 
            'order_id' => 'required|integer|min:1',
            'currentdatetime' => 'required|integer|min:1',
            'if_cancelled_reason_text' => 'required|string|max:255',
        ]);   

        if($validator->fails()) {  
            return response()->json([
            'success' => constants('invalidResponse.success'),
            'message' => "Validation Failed! Please Refresh Page & Try Again.",
            'data'    => constants('emptyData'),
            ], constants('invalidResponse.statusCode'));    
        }
        
        if($request->header('temptoken')=='' || ($request->currentdatetime + 3600 < time()) || CheckTempToken(constants('usertype.customer'),$request->get('devicetype'),$request->get('user_id'),$request->header('temptoken'))!=true) {
            return response()->json([
            'success' => constants('invalidResponse.success'),
            'message' => "Time Out, Please Refresh & Try Again.",
            'data'    => ['order_id' => $request->order_id ],
            ], constants('invalidResponse.statusCode')); 
        }
      
        $orderData = Order::with(['order_status', 'order_payment_status'])->where('is_active', constants('is_active_yes'))->whereIn('status', constants('order_status.temp_orders'))->where('user_id', $request->get('user_id'))->where('payment_status', constants('payment_status.Pending'))->where('id', $request->order_id)->first();

        if(empty($orderData)) {
            return response()->json([
              'success' => constants('invalidResponse.success'),
              'message' => "Oops, No Valid Order Found.",
              'data'    => constants('emptyData'),
            ], constants('invalidResponse.statusCode')); 
        }


        $updateDataOrderCancelled = [
            'status' => "CU",
            'payment_status' => constants('payment_status.Pending'),
            'payment_type_manual' => constants('payment_type_manual.CS.short'),
            'cancelled_datetime' => date('Y-m-d H:i:s'),
            'if_cancelled_reason_text' => $request->if_cancelled_reason_text,
        ];
                   
        Order::where('id', $orderData->id)->update($updateDataOrderCancelled);
        OrderArrange::where('order_id', $orderData->id)->delete();

        $updateDataRazorPayPayment = [
            'is_active' => 2,
        ];
        RazorPayPayment::where('order_id', $orderData->id)->whereNull('razorpay_signature')->where('is_active',constants('is_active_yes'))->update($updateDataRazorPayPayment);

        /*---- removing coupon if applied -----*/

        if($orderData->coupon_code_id>0){
        $dataCouponApplied = Coupon::where('id', $orderData->coupon_code_id)->first(['used_count']);
        
        if(!empty($dataCouponApplied)) {
          $orderUpdate = [
            "coupon_code_applied" => NULL,
            "coupon_code_id" => NULL,
            "coupon_benefit_amount" => 0,
            "total_payable_amount" => $orderData->total_payable_amount + $orderData->coupon_benefit_amount,
            "discount" => $orderData->discount - $orderData->coupon_benefit_amount,
          ];
          Order::where('id', $orderData->id)->update($orderUpdate);
            $couponUpdate = [            
            "used_count" => $dataCouponApplied->used_count - 1,
            ];
            Coupon::where('id', $orderData->coupon_code_id)->update($couponUpdate);
          }
        }
        /*---- removing coupon if applied ends -----*/


        createOrderLogs("Order Cancelled By Customer.", $orderData->id);
        createOrderLogs("Order Cancelled.", $orderData->id, 1);
        pushNotificationToAdmin("Order Cancelled","Order Cancelled By Customer.",'','', route('view-order')."/".$orderData->id);
        createAdminNotificationLogs("Order Cancelled","Order Cancelled By Customer.", route('view-order')."/".$orderData->id);
        createCustomerNotificationLogs("Your Order has been Cancelled By You.", $orderData->user_id,$orderData->id, constants('notification_type.danger'));

        return response()->json([
            'success' => constants('validResponse.success'),
            'message' => 'Order Cancelled Successfully.',
            'data'    => [ 'order_id' => $orderData->id ],
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




   /*------get order listing with filters also -------*/
   public function getDeliveredOrderListForFeedBack(Request $request)
   {  
      try {
        $pageno = 0;  $order_status = constants('order_status.delivered_orders');
        
        if(isset($request->pageno)){
          $pageno = intval($request->pageno);
        }

        $perpage = 10;
        if(isset($request->perpage)){
            $perpage = (intval($request->perpage)>200) ? 200 : intval($request->perpage);
        }

        $orderCount = Order::with(['order_status','order_payment_status','order_payment_type','order_reviews'])
          ->doesntHave('order_reviews')
          ->whereIn('status', $order_status)
          ->where('user_id', $request->get('user_id'))
          ->where('is_active', constants('is_active_yes'))
          ->count();



        $orderData = Order::with(['order_status',
            'order_payment_status',
            'order_payment_type',
            'order_reviews',
            'orderParcel' => function($qryOrderParcel) {
                $qryOrderParcel->with([
                    'goodsType' => function($qryGoodsType) {
                        $qryGoodsType->where('is_active','!=', 2);
                    },
                ]);
                $qryOrderParcel->where('is_active', constants('is_active_yes'));
            },
            ])
          ->doesntHave('order_reviews')
          ->whereIn('status', $order_status)
          ->where('is_active', constants('is_active_yes'))
          ->where('user_id', $request->get('user_id'))
          ->offset($pageno*$perpage)
          ->limit($perpage)
          ->orderBy('id','DESC')
          ->get();


        $orderList = [];

        foreach ($orderData as $row) {
          $is_order_reviewed = 0;

          if(isset($row->order_reviews->id)){
              $is_order_reviewed = 1;
          }

          /*--------------------------*/
          $orderParcelArray = []; 
          if(!empty($row->orderParcel)){
            foreach ($row->orderParcel as $key => $value) { 
              if(isset($value->goodsType->name)){ $orderParcelArray[] = $value->goodsType->name; }
            }
          }
          $orderParcelString = implode(', ', $orderParcelArray);
          /*---------------------------*/


          $orderList[] = [
            'id' => $row->id,
            'bigdaddy_lr_number' => $row->bigdaddy_lr_number,
            'pickup_location' => $row->pickup_location,
            'drop_location' => $row->drop_location,
            'final_cost' => $row->final_cost,
            'transporter_lr_number' => $row->transporter_lr_number,
            'total_no_of_parcel' => $row->total_no_of_parcel,
            'status' => $row->status,
            'statusName' => $row->order_status->name,
            'payment_status' => $row->payment_status,
            'payment_statusName' => $row->order_payment_status->name,
            'payment_type' => $row->payment_type,
            'payment_typeName' => $row->order_payment_type->name,
            'created_at' => Carbon::parse($row->created_at)->format('Y-m-d H:i:s'),
            'statusName' => $row->order_status->name,
            'payment_statusName' => $row->order_payment_status->name,
            'is_order_reviewed' => $is_order_reviewed,
            'total_payable_amount' => $row->total_payable_amount,
            'orderParcelString' => $orderParcelString,
          ];
        }

        return response()->json([
                'success' => constants('validResponse.success'),
                'message' => "",
                'data'    => $orderList,
                'total_count'    => $orderCount,
                'per_page_count'    => $perpage,
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



    /*------get getLR_onlyList-------*/
   public function getLR_onlyList(Request $request)
   {
      try {

      $listData = CustomerUploadedFileOrder::where('is_active', constants('is_active_yes'))->where('user_id', $request->get('user_id'))->limit(100)->orderBy('id','DESC')->get();

      return response()->json([
            'success' => constants('validResponse.success'),
            'message' => "",
            'data'    => [
              'list' => $listData, 
              'count'   => count($listData), 
            ],
            'fileurl' => sendPath().constants('dir_name.customer'),
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




    /*-----edit---&---update--Order--Details-------*/
   public function updateOrderDetails(Request $request)
   {
      try {

        $validator = Validator::make($request->all(), [ 
              'order_id' => 'required|integer|min:1',
              'transporter_lr_number' => 'nullable|string|max:55',
              'fileLRP' => 'nullable|string',
              'pickup_location' => 'required',
              'drop_location' => 'required',
              'pickup_latitude' => 'required',
              'pickup_longitude' => 'required',
              'drop_latitude' => 'required',
              'drop_longitude' => 'required',
              'pickup_contact_person_name' => 'required',
              'pickup_contact_person_phone_number' => 'required',
              'drop_contact_person_phone_number' => 'required',
              'drop_transporter_name' => 'nullable|string',
              'drop_contact_person_name' => 'required',
              'transport_cost' => 'required|between:0,99999999999999999.99',
              'customer_estimation_asset_value'=> 'nullable|between:0,99999999999999999.99',
              'pickup_transporter_name' => 'nullable|string',
              'goods_type_id.*' => 'required|integer|min:1',
              'no_of_parcel.*' => 'required|integer|min:1',
              'other_text' => 'required',
              'goods_weight.*' => 'required',
              'total_weight.*' => 'required',
        ]);   

        if($validator->fails()) {  
            return response()->json([
              'success' => constants('invalidResponse.success'),
              'message' => $validator->errors()->first(),
              'data'    => constants('emptyData'),
          ], constants('invalidResponse.statusCode'));   
        }
        else if( !is_array($request->goods_type_id) || !is_array($request->no_of_parcel) || !is_array($request->goods_weight) || !is_array($request->total_weight) || !is_array($request->other_text) || empty($request->goods_type_id) || empty($request->no_of_parcel) || empty($request->goods_weight) || empty($request->total_weight) || empty($request->other_text)) 
        {          
            return response()->json(constants('bad_request'), constants('invalidResponse.statusCode'));
        }

        $orderData = Order::with(['order_status', 'order_payment_status'])->where('is_active', constants('is_active_yes'))->whereIn('status', constants('order_status.temp_orders'))->where('user_id', $request->get('user_id'))->where('payment_status', constants('payment_status.Pending'))->where('id', $request->order_id)->first();

        if(empty($orderData)) {
            return response()->json([
              'success' => constants('invalidResponse.success'),
              'message' => "Oops, No Valid Order Found to Update Order Detail.",
              'data'    => constants('emptyData'),
            ], constants('invalidResponse.statusCode')); 
        }

        $updateOrderData = [
            'transporter_lr_number' => isset($request->transporter_lr_number) ? trim($request->transporter_lr_number) : NULL,
            'pickup_location' => $request->pickup_location,
            'drop_location' => $request->drop_location,
            'pickup_location' => $request->pickup_location,
            'pickup_latitude' => floatval($request->pickup_latitude),
            'pickup_longitude' => floatval($request->pickup_longitude),
            'drop_latitude' => floatval($request->drop_latitude),
            'drop_longitude' => floatval($request->drop_longitude),
            'contact_person_name' => $request->pickup_contact_person_name,
            'contact_person_phone_number' => $request->pickup_contact_person_phone_number,
            'transporter_name' => isset($request->pickup_transporter_name) ? $request->pickup_transporter_name : '',
            'contact_person_name_drop' => $request->drop_transporter_name,
            'contact_person_phone_number_drop' => $request->drop_contact_person_phone_number,
            'transporter_name_drop' => isset($request->drop_transporter_name) ? $request->drop_transporter_name : '',
            'customer_estimation_asset_value' => isset($request->customer_estimation_asset_value) ? floatval($request->customer_estimation_asset_value) : 0,
            'other_field_pickup' => isset($request->other_field_pickup) ? $request->other_field_pickup : NULL,
            'other_field_drop' => isset($request->other_field_drop) ? $request->other_field_drop : NULL,
            'transport_cost' => floatval($request->transport_cost),
            'total_weight' => 0,
            'total_no_of_parcel' => 0,
            'min_order_value_charge' => 0,
            'redeliver_charge' => 0,
            'discount' => 0,
            'final_cost' => 0,
            'status' => 'RO',
            'is_active' => constants('is_active_yes'),
            'payment_type_manual' => constants('payment_type_manual.CS.short'),
            'payment_status' => constants('payment_status.Pending'),
          ];

          Order::where('id', $request->order_id)->where('user_id', $request->get('user_id'))->update($updateOrderData);

          OrderParcel::where('order_id', $request->order_id)->update(['is_active' => 2 ]);


                $total_weightO = 0;
                $total_no_of_parcelO = 0;
                $tempo_chargeO = 0;
                $service_chargeO = 0;
                $delivery_chargeO = 0;
                $i = 0;


        /*----parcel create--------*/
            foreach ($request->goods_type_id as $key => $value) {

                $delivery_charge_i =  0;
                $total_weightO += floatval($request->total_weight[$i]);
                $total_no_of_parcelO += intval($request->no_of_parcel[$i]);
                $tempo_chargeO += 0;
                $service_chargeO += 0;
                $delivery_chargeO += $delivery_charge_i;


                $parcelData = [
                'order_id' => $orderData->id,
                'no_of_parcel' => intval($request->no_of_parcel[$i]),
                'goods_type_id' => intval($request->goods_type_id[$i]),
                'goods_weight' => floatval($request->goods_weight[$i]),
                'total_weight' => floatval($request->total_weight[$i]),
                'estimation_value' => isset($request->estimation_value[$i]) ? floatval($request->estimation_value[$i]) : 0,
                'tempo_charge' => 0,
                'service_charge' => 0,
                'delivery_charge' => $delivery_charge_i,
                'is_active' => constants('is_active_yes'),
                ];

                if($request->goods_type_id[$i]==constants('goods_type_id_other')){
                    $parcelData['other_text'] = $request->other_text[$i];
                }
                else{
                    $parcelData['other_text'] = NULL;
                }

                OrderParcel::create($parcelData);
                    $i++;
            } 
        /*----parcel create--------*/

        if(isset($request->fileLRP) && strlen($request->fileLRP)>50){
            $filename_dx = UploadImageFromBase64String($request->fileLRP, constants('dir_name.order'),constants('order_file_type.lrpickup'));
              if($filename_dx!=''){
                $this_img = OrderFile::whereIn('order_id',[ $orderData->id ])->first(['img']);
                $this_img = ($this_img!='') ? $this_img : 0;
                DeleteFile($this_img, constants('dir_name.order'));
                OrderFile::whereIn('order_id',[ $orderData->id ])->delete();
                $dt = [ 'order_id' => $orderData->id, 'img' => $filename_dx, 'img_type' => constants('order_file_type.lrpickup'),];                        OrderFile::create($dt);
              }
        }

        $orderDataUpdate = [
            'total_no_of_parcel' => $total_no_of_parcelO,
            'total_weight' => $total_weightO,
            'tempo_charge' => $tempo_chargeO,
            'service_charge' => $service_chargeO,
            'final_cost' => $delivery_chargeO,
        ];
        Order::where('user_id', $request->get('user_id'))->where('id', $orderData->id)->where('is_active', constants('is_active_yes'))->update($orderDataUpdate);
        createOrderLogs("Order Requested With Update.",$orderData->id);
        createOrderLogs("Order Requested With Update.", $orderData->id, 1);
        createAdminNotificationLogs("Update Order","Order Requested By Customer With Update.",route('tobeapproved-orders'));
        pushNotificationToAdmin("Update Order","Existing Order Requested By Customer With Update.",'','',route('tobeapproved-orders'));

        return response()->json([
            'success' => constants('validResponse.success'),
            'message' => "You Order has been Updated Successfully, Please Wait For The Best Quotation.",
            'data'    => [ 'order_id' => $orderData->id ] ,
            'fileurl' => sendPath().constants('dir_name.order'),
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
