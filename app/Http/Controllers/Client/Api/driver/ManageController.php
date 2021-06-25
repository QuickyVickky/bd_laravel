<?php

namespace App\Http\Controllers\Client\Api\driver;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use App\Models\ShortHelper;
use App\Models\Driver;
use App\Models\Order;
use App\Models\OrderFile;
use App\Models\ApiLog;
use App\Models\ReasonFor;
use App\Models\OrderArrange;
date_default_timezone_set('Asia/Kolkata');
use Illuminate\Support\Facades\Log;
use App\Service\CustomService;


class ManageController extends Controller
{

    public function __construct()
    {
        ini_set('max_execution_time', 300);
    }
    
   /*------get assigned order list of a driver-------*/
   public function activeOrderListDriver(Request $request)
   {

      try { 

  	  if(isset($request->current_latitude) && isset($request->current_longitude) && strlen($request->current_latitude)>4 && strlen($request->current_longitude)>4){
          Driver::where('id', $request->get('driver_id'))->update(['current_latitude' => $request->current_latitude, 'current_longitude' => $request->current_longitude]);
      }

      $searched = ''; 
      if(isset($request->search) && trim($request->search)!=''){
        $searched = $request->search;
      }

      $pageno = 0;
      if(isset($request->pageno)){
        $pageno = intval($request->pageno);
      }

      $driver_id = $request->get('driver_id');

      $activeOrderStatus = array_merge(constants('order_status.assigned_orders'),constants('order_status.pickedup_orders'));


        $orderCount = OrderArrange::with([
            'order' => function($qryOrder) use ($searched , $activeOrderStatus, $driver_id) {
                $qryOrder->with([
                    'order_status','order_payment_status','order_payment_type'
                ])
                ->where(function($querySearch) use ($searched , $activeOrderStatus, $driver_id) {
                  if($searched!=''){
                    $querySearch->where('bigdaddy_lr_number', 'LIKE', '%'.$searched.'%');
                    $querySearch->orWhere('drop_location', 'LIKE', '%'.$searched.'%');
                    $querySearch->orWhere('pickup_location', 'LIKE', '%'.$searched.'%');
                    $querySearch->orWhere('contact_person_name', 'LIKE', '%'.$searched.'%');
                    $querySearch->orWhere('contact_person_phone_number', 'LIKE', '%'.$searched.'%');
                    $querySearch->orWhere('transporter_name', 'LIKE', '%'.$searched.'%');
                    $querySearch->orWhere('contact_person_name_drop', 'LIKE', '%'.$searched.'%');
                    $querySearch->orWhere('contact_person_phone_number_drop', 'LIKE', '%'.$searched.'%');
                    $querySearch->orWhere('transporter_name_drop', 'LIKE', '%'.$searched.'%');
                    $querySearch->orWhere('final_cost', 'LIKE', '%'.$searched.'%');
                  }
                 })
                ->whereIn('status', $activeOrderStatus)
                ->where('is_active', constants('is_active_yes'))
                ->where('driver_id', $driver_id);
            },
        ])
        ->where('is_active', constants('is_active_yes'))
        ->where('driver_id','>', 0)
        ->where('driver_id', $driver_id)
        ->where('is_completed' , constants('confirmation.no'))
        ->count();

 

        $orderData = OrderArrange::with([
            'order' => function($qryOrder) use ($searched , $activeOrderStatus, $driver_id) {
                $qryOrder->with([
                    'order_status','order_payment_status','order_payment_type'
                ])
                ->where(function($querySearch) use ($searched , $activeOrderStatus, $driver_id) {
                  if($searched!='') {
                    $querySearch->where('bigdaddy_lr_number', 'LIKE', '%'.$searched.'%');
                    $querySearch->orWhere('drop_location', 'LIKE', '%'.$searched.'%');
                    $querySearch->orWhere('pickup_location', 'LIKE', '%'.$searched.'%');
                    $querySearch->orWhere('contact_person_name', 'LIKE', '%'.$searched.'%');
                    $querySearch->orWhere('contact_person_phone_number', 'LIKE', '%'.$searched.'%');
                    $querySearch->orWhere('transporter_name', 'LIKE', '%'.$searched.'%');
                    $querySearch->orWhere('contact_person_name_drop', 'LIKE', '%'.$searched.'%');
                    $querySearch->orWhere('contact_person_phone_number_drop', 'LIKE', '%'.$searched.'%');
                    $querySearch->orWhere('transporter_name_drop', 'LIKE', '%'.$searched.'%');
                    $querySearch->orWhere('final_cost', 'LIKE', '%'.$searched.'%');
                   }
                 })
                ->whereIn('status', $activeOrderStatus)
                ->where('is_active', constants('is_active_yes'))
                ->where('driver_id', $driver_id);
            },
        ])
        ->where('is_active', constants('is_active_yes'))
        ->where('driver_id','>', 0)
        ->where('driver_id', $driver_id)
        ->where('is_completed' , constants('confirmation.no'))
        ->offset($pageno*constants('basic_limit'))
        ->limit(constants('basic_limit'))
        ->orderBy('arrangement_number','ASC')
        ->orderBy('order_id','DESC')
        ->get();



        $orderList = [];
        $i = 0;
        foreach ($orderData as $row) { $i++;

          if(isset($row->order->id)){

          $totalCost = $row->order->final_cost + $row->order->min_order_value_charge + $row->order->redeliver_charge - $row->order->discount;

           
          $orderList[] = [
            'id' => $row->order->id,
            'bigdaddy_lr_number' => $row->order->bigdaddy_lr_number,
            'transporter_lr_number' => $row->order->transporter_lr_number,
            'pickup_location' => $row->order->pickup_location,
            'drop_location' => $row->order->drop_location,
            'delivery_charge' => $row->order->final_cost,
            'total_no_of_parcel' => $row->order->total_no_of_parcel,
            'created_at' => Carbon::parse($row->order->created_at)->format('Y-m-d H:i:s'),
            'status' => $row->order->status,
            'statusName' => $row->order->order_status->name,
            'payment_status' => $row->order->payment_status,
            'payment_statusName' => $row->order->order_payment_status->name,
            'payment_type' => $row->order->payment_type,
            'payment_typeName' => $row->order->order_payment_type->name,
            'totalcost' => number_format($totalCost, 2),
            'driver_assigned_datetime' => Carbon::parse($row->order->driver_assigned_datetime)->format('Y-m-d H:i:s'),
            'pickup_latitude' => $row->order->pickup_latitude,
            'pickup_longitude' => $row->order->pickup_longitude,
            'drop_latitude' => $row->order->drop_latitude,
            'drop_longitude' => $row->order->drop_longitude,
            'order_arrangement_type' => $row->arrangement_type,
            'arrangement_number' => $row->arrangement_number,
          ];

          }
        }


      return response()->json([
                'success' => constants('validResponse.success'),
                'message' => "",
                'data'    => $orderList,
                'count'    => $orderCount,
                'per_page'    => constants('basic_limit'),
                'pageno'    => $pageno,
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


   /*------get assigned order list of a driver-------*/
   public function assinedOrderList(Request $request)
   {
      try {
  	  
      if(isset($request->current_latitude) && isset($request->current_longitude) && strlen($request->current_latitude)>4 && strlen($request->current_longitude)>4){
          Driver::where('id', $request->get('driver_id'))->update(['current_latitude' => $request->current_latitude, 'current_longitude' => $request->current_longitude]);
      }

      $searched = ''; $pageno = 0;
      

      if(isset($request->search) && trim($request->search)!=''){
        $searched = $request->search;
      }

      if(isset($request->pageno)){
        $pageno = intval($request->pageno);
      }

      $orderCount = Order::with(['order_status','order_payment_status','order_payment_type'])
        ->where(function($querySearch) use ($searched) {
          if($searched!=''){
            $querySearch->where('bigdaddy_lr_number', 'LIKE', '%'.$searched.'%');
            $querySearch->orWhere('drop_location', 'LIKE', '%'.$searched.'%');
            $querySearch->orWhere('pickup_location', 'LIKE', '%'.$searched.'%');
            $querySearch->orWhere('contact_person_name', 'LIKE', '%'.$searched.'%');
            $querySearch->orWhere('contact_person_phone_number', 'LIKE', '%'.$searched.'%');
            $querySearch->orWhere('transporter_name', 'LIKE', '%'.$searched.'%');
            $querySearch->orWhere('contact_person_name_drop', 'LIKE', '%'.$searched.'%');
            $querySearch->orWhere('contact_person_phone_number_drop', 'LIKE', '%'.$searched.'%');
            $querySearch->orWhere('transporter_name_drop', 'LIKE', '%'.$searched.'%');
            $querySearch->orWhere('final_cost', 'LIKE', '%'.$searched.'%');
          }
            })
        ->whereIn('status', constants('order_status.assigned_orders'))
        ->where('is_active', constants('is_active_yes'))
        ->where('driver_id', $request->get('driver_id'))
        ->count();


      $orderData = Order::with(['order_status','order_payment_status','order_payment_type'])
        ->where(function($querySearch) use ($searched) {
          if($searched!=''){
            $querySearch->where('bigdaddy_lr_number', 'LIKE', '%'.$searched.'%');
            $querySearch->orWhere('drop_location', 'LIKE', '%'.$searched.'%');
            $querySearch->orWhere('pickup_location', 'LIKE', '%'.$searched.'%');
            $querySearch->orWhere('contact_person_name', 'LIKE', '%'.$searched.'%');
            $querySearch->orWhere('contact_person_phone_number', 'LIKE', '%'.$searched.'%');
            $querySearch->orWhere('transporter_name', 'LIKE', '%'.$searched.'%');
            $querySearch->orWhere('contact_person_name_drop', 'LIKE', '%'.$searched.'%');
            $querySearch->orWhere('contact_person_phone_number_drop', 'LIKE', '%'.$searched.'%');
            $querySearch->orWhere('transporter_name_drop', 'LIKE', '%'.$searched.'%');
            $querySearch->orWhere('final_cost', 'LIKE', '%'.$searched.'%');
          }
            })
        ->whereIn('status', constants('order_status.assigned_orders'))
        ->where('is_active', constants('is_active_yes'))
        ->where('driver_id', $request->get('driver_id'))
        ->offset($pageno*constants('basic_limit'))
        ->limit(constants('basic_limit'))
        ->orderBy('id','DESC')
        ->get();


        $orderList = [];
        foreach ($orderData as $row) {
          $totalCost = $row->final_cost + $row->min_order_value_charge + $row->redeliver_charge - $row->discount;

          $orderList[] = [
            'id' => $row->id,
            'bigdaddy_lr_number' => $row->bigdaddy_lr_number,
            'transporter_lr_number' => $row->transporter_lr_number,
            'pickup_location' => $row->pickup_location,
            'drop_location' => $row->drop_location,
            'delivery_charge' => $row->final_cost,
            'total_no_of_parcel' => $row->total_no_of_parcel,
            'created_at' => Carbon::parse($row->created_at)->format('Y-m-d H:i:s'),
            'status' => $row->status,
            'statusName' => $row->order_status->name,
            'payment_status' => $row->payment_status,
            'payment_statusName' => $row->order_payment_status->name,
            'payment_type' => $row->payment_type,
            'payment_typeName' => $row->order_payment_type->name,
            'totalcost' => number_format($totalCost, 2),
            'driver_assigned_datetime' => Carbon::parse($row->driver_assigned_datetime)->format('Y-m-d H:i:s'),
            'pickup_latitude' => $row->pickup_latitude,
            'pickup_longitude' => $row->pickup_longitude,
            'drop_latitude' => $row->drop_latitude,
            'drop_longitude' => $row->drop_longitude,
          ];
        }


        return response()->json([
                'success' => constants('validResponse.success'),
                'message' => "",
                'data'    => $orderList,
                'count'    => $orderCount,
                'per_page'    => constants('basic_limit'),
                'pageno'    => $pageno,
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


   /*------get pickedUp order list of a driver-------*/
   public function pickedUpOrderList(Request $request)
   {
      try {
  	  
      if(isset($request->current_latitude) && isset($request->current_longitude) && strlen($request->current_latitude)>4 && strlen($request->current_longitude)>4){
          Driver::where('id', $request->get('driver_id'))->update(['current_latitude' => $request->current_latitude, 'current_longitude' => $request->current_longitude]);
      }

      $searched = ''; $pageno = 0;

      if(isset($request->search) && trim($request->search)!=''){
        $searched = $request->search;
      }

      if(isset($request->pageno)){
        $pageno = intval($request->pageno);
      }

      $orderCount = Order::with(['order_status','order_payment_status','order_payment_type'])
        ->where(function($querySearch) use ($searched) {
          if($searched!=''){
            $querySearch->where('bigdaddy_lr_number', 'LIKE', '%'.$searched.'%');
            $querySearch->orWhere('drop_location', 'LIKE', '%'.$searched.'%');
            $querySearch->orWhere('pickup_location', 'LIKE', '%'.$searched.'%');
            $querySearch->orWhere('contact_person_name', 'LIKE', '%'.$searched.'%');
            $querySearch->orWhere('contact_person_phone_number', 'LIKE', '%'.$searched.'%');
            $querySearch->orWhere('transporter_name', 'LIKE', '%'.$searched.'%');
            $querySearch->orWhere('contact_person_name_drop', 'LIKE', '%'.$searched.'%');
            $querySearch->orWhere('contact_person_phone_number_drop', 'LIKE', '%'.$searched.'%');
            $querySearch->orWhere('transporter_name_drop', 'LIKE', '%'.$searched.'%');
            $querySearch->orWhere('final_cost', 'LIKE', '%'.$searched.'%');
          }
            })
        ->whereIn('status', constants('order_status.pickedup_orders'))
        ->where('is_active', constants('is_active_yes'))
        ->where('driver_id', $request->get('driver_id'))
        ->count();



        $orderData = Order::with(['order_status','order_payment_status','order_payment_type'])
        ->where(function($querySearch) use ($searched) {
          if($searched!=''){
            $querySearch->where('bigdaddy_lr_number', 'LIKE', '%'.$searched.'%');
            $querySearch->orWhere('drop_location', 'LIKE', '%'.$searched.'%');
            $querySearch->orWhere('pickup_location', 'LIKE', '%'.$searched.'%');
            $querySearch->orWhere('contact_person_name', 'LIKE', '%'.$searched.'%');
            $querySearch->orWhere('contact_person_phone_number', 'LIKE', '%'.$searched.'%');
            $querySearch->orWhere('transporter_name', 'LIKE', '%'.$searched.'%');
            $querySearch->orWhere('contact_person_name_drop', 'LIKE', '%'.$searched.'%');
            $querySearch->orWhere('contact_person_phone_number_drop', 'LIKE', '%'.$searched.'%');
            $querySearch->orWhere('transporter_name_drop', 'LIKE', '%'.$searched.'%');
            $querySearch->orWhere('final_cost', 'LIKE', '%'.$searched.'%');
          }
            })
        ->whereIn('status', constants('order_status.pickedup_orders'))
        ->where('is_active', constants('is_active_yes'))
        ->where('driver_id', $request->get('driver_id'))
        ->offset($pageno*constants('basic_limit'))
        ->limit(constants('basic_limit'))
        ->orderBy('id','DESC')
        ->get();


        $orderList = [];
        foreach ($orderData as $row) {
          $totalCost = $row->final_cost + $row->min_order_value_charge + $row->redeliver_charge - $row->discount;

          $orderList[] = [
            'id' => $row->id,
            'bigdaddy_lr_number' => $row->bigdaddy_lr_number,
            'transporter_lr_number' => $row->transporter_lr_number,
            'pickup_location' => $row->pickup_location,
            'drop_location' => $row->drop_location,
            'delivery_charge' => $row->final_cost,
            'total_no_of_parcel' => $row->total_no_of_parcel,
            'created_at' => Carbon::parse($row->created_at)->format('Y-m-d H:i:s'),
            'status' => $row->status,
            'statusName' => $row->order_status->name,
            'payment_status' => $row->payment_status,
            'payment_statusName' => $row->order_payment_status->name,
            'payment_type' => $row->payment_type,
            'payment_typeName' => $row->order_payment_type->name,
            'totalcost' => number_format($totalCost, 2),
            'driver_assigned_datetime' => Carbon::parse($row->driver_assigned_datetime)->format('Y-m-d H:i:s'),
            'pickup_latitude' => $row->pickup_latitude,
            'pickup_longitude' => $row->pickup_longitude,
            'drop_latitude' => $row->drop_latitude,
            'drop_longitude' => $row->drop_longitude,
          ];
        }


        return response()->json([
                'success' => constants('validResponse.success'),
                'message' => "Order List PickUp",
                'data'    => $orderList,
                'count'    => $orderCount,
                'per_page'    => constants('basic_limit'),
                'pageno'    => $pageno,
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


   /*------get all order history list of a driver-------*/
   public function driverOrderHistoryList(Request $request)
   {
      try {
  	  
      if(isset($request->current_latitude) && isset($request->current_longitude) && strlen($request->current_latitude)>4 && strlen($request->current_longitude)>4){
          Driver::where('id', $request->get('driver_id'))->update(['current_latitude' => $request->current_latitude, 'current_longitude' => $request->current_longitude]);
      }

      $searched = ''; $pageno = 0; $order_payment_status = ''; $order_status = ''; $startdate = ''; $enddate = '';

      if(isset($request->search) && trim($request->search)!=''){
        $searched = $request->search;
      }

      if(isset($request->pageno)){
        $pageno = intval($request->pageno);
      }

      if(isset($request->payment_status) && trim($request->payment_status)!=''){
        $order_payment_status = $request->payment_status;
      }

      if(isset($request->status)  && trim($request->status)!='' && array_key_exists($request->status, constants('order_status'))){
        $order_status = $request->status;
      }

      if(isset($request->startdate) && isset($request->enddate) && trim($request->startdate)!='' && trim($request->enddate)!=''){
        $startdate = date('Y-m-d', strtotime($request->startdate . ' -0 day'));
        $enddate = date('Y-m-d', strtotime($request->enddate . ' +1 day'));
      }


      $orderCount = Order::with(['order_status','order_payment_status','order_payment_type'])
        ->where(function($querySearch) use ($searched) {
          if($searched!=''){
            $querySearch->where('bigdaddy_lr_number', 'LIKE', '%'.$searched.'%');
            $querySearch->orWhere('transporter_lr_number', 'LIKE', '%'.$searched.'%');
            $querySearch->orWhere('drop_location', 'LIKE', '%'.$searched.'%');
            $querySearch->orWhere('pickup_location', 'LIKE', '%'.$searched.'%');
            $querySearch->orWhere('contact_person_name', 'LIKE', '%'.$searched.'%');
            $querySearch->orWhere('contact_person_phone_number', 'LIKE', '%'.$searched.'%');
            $querySearch->orWhere('transporter_name', 'LIKE', '%'.$searched.'%');
            $querySearch->orWhere('contact_person_name_drop', 'LIKE', '%'.$searched.'%');
            $querySearch->orWhere('contact_person_phone_number_drop', 'LIKE', '%'.$searched.'%');
            $querySearch->orWhere('transporter_name_drop', 'LIKE', '%'.$searched.'%');
            $querySearch->orWhere('final_cost', 'LIKE', '%'.$searched.'%');
          }
        })
        ->where(function($queryorder_payment_status) use ($order_payment_status) {
          if($order_payment_status!=''){
            $queryorder_payment_status->where('payment_status', $order_payment_status);
          }
        })
        ->where(function($queryorder_status) use ($order_status) {
          if($order_status!=''){
            $queryorder_status->whereIn('status', constants('order_status.'.$order_status) );
          }
        })
        ->where(function($queryorder_createddate) use ($startdate, $enddate) {
          if($startdate!='' && $enddate!=''){
            $queryorder_createddate->whereBetween('created_at', [$startdate, $enddate]);
          }
        })
        ->whereNotIn('status', constants('order_status.temp_orders'))
        ->whereNotIn('status', constants('order_status.cancelled_orders'))
        ->where('is_active', constants('is_active_yes'))
        ->where('driver_id', $request->get('driver_id'))
        ->count();



        $orderData = Order::with(['order_status','order_payment_status','order_payment_type'])
        ->where(function($querySearch) use ($searched) {
          if($searched!=''){
            $querySearch->where('bigdaddy_lr_number', 'LIKE', '%'.$searched.'%');
            $querySearch->orWhere('transporter_lr_number', 'LIKE', '%'.$searched.'%');
            $querySearch->orWhere('drop_location', 'LIKE', '%'.$searched.'%');
            $querySearch->orWhere('pickup_location', 'LIKE', '%'.$searched.'%');
            $querySearch->orWhere('contact_person_name', 'LIKE', '%'.$searched.'%');
            $querySearch->orWhere('contact_person_phone_number', 'LIKE', '%'.$searched.'%');
            $querySearch->orWhere('transporter_name', 'LIKE', '%'.$searched.'%');
            $querySearch->orWhere('contact_person_name_drop', 'LIKE', '%'.$searched.'%');
            $querySearch->orWhere('contact_person_phone_number_drop', 'LIKE', '%'.$searched.'%');
            $querySearch->orWhere('transporter_name_drop', 'LIKE', '%'.$searched.'%');
            $querySearch->orWhere('final_cost', 'LIKE', '%'.$searched.'%');
          }
        })
        ->where(function($queryorder_payment_status) use ($order_payment_status) {
          if($order_payment_status!=''){
            $queryorder_payment_status->where('payment_status', $order_payment_status);
          }
        })
        ->where(function($queryorder_status) use ($order_status) {
          if($order_status!=''){
            $queryorder_status->whereIn('status', constants('order_status.'.$order_status) );
          }
        })
        ->where(function($queryorder_createddate) use ($startdate, $enddate) {
          if($startdate!='' && $enddate!=''){
            $queryorder_createddate->whereBetween('created_at', [$startdate, $enddate]);
          }
        })
        ->whereNotIn('status', constants('order_status.temp_orders'))
        ->whereNotIn('status', constants('order_status.cancelled_orders'))
        ->where('is_active', constants('is_active_yes'))
        ->where('driver_id', $request->get('driver_id'))
        ->offset($pageno*constants('basic_limit'))
        ->limit(constants('basic_limit'))
        ->orderBy('id','DESC')
        ->get();


        $orderList = [];
        foreach ($orderData as $row) {
          $totalCost = $row->final_cost + $row->min_order_value_charge + $row->redeliver_charge - $row->discount;

          $orderList[] = [
            'id' => $row->id,
            'bigdaddy_lr_number' => $row->bigdaddy_lr_number,
            'transporter_lr_number' => $row->transporter_lr_number,
            'pickup_location' => $row->pickup_location,
            'drop_location' => $row->drop_location,
            'delivery_charge' => $row->final_cost,
            'total_no_of_parcel' => $row->total_no_of_parcel,
            'created_at' => Carbon::parse($row->created_at)->format('Y-m-d H:i:s'),
            'status' => $row->status,
            'statusName' => $row->order_status->name,
            'payment_status' => $row->payment_status,
            'payment_statusName' => $row->order_payment_status->name,
            'payment_type' => $row->payment_type,
            'payment_typeName' => $row->order_payment_type->name,
            'totalcost' => number_format($totalCost, 2),
            'driver_assigned_datetime' => Carbon::parse($row->driver_assigned_datetime)->format('Y-m-d H:i:s'),
            'pickup_latitude' => $row->pickup_latitude,
            'pickup_longitude' => $row->pickup_longitude,
            'drop_latitude' => $row->drop_latitude,
            'drop_longitude' => $row->drop_longitude,
            'delivered_datetime' => $row->delivered_datetime,
          ];
        }


        return response()->json([
                'success' => constants('validResponse.success'),
                'message' => "",
                'data'    => $orderList,
                'count'    => $orderCount,
                'per_page'    => constants('basic_limit'),
                'pageno'    => $pageno,
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
      try {
      	  
      if(isset($request->current_latitude) && isset($request->current_longitude) && strlen($request->current_latitude)>4 && strlen($request->current_longitude)>4){
          Driver::where('id', $request->get('driver_id'))->update(['current_latitude' => $request->current_latitude, 'current_longitude' => $request->current_longitude]);
      }

      if(!isset($request->order_id) || valid_id($request->order_id)==false){
          return response()->json(constants('somethingWentWrong'), constants('invalidResponse.statusCode'));
      } 


      $orderData = Order::with(['order_status','order_payment_status','order_payment_type',
          'orderParcel' => function($qryOrderParcel) {
                $qryOrderParcel->with([
                    'goodsType' => function($qryGoodsType) {
                        $qryGoodsType->where('is_active','!=', 2);
                    },
                ]);
                $qryOrderParcel->where('is_active', 0);
            },
            'driver' => function ($qryDriver) {
                  $qryDriver->with(['vehicle']);
                  $qryDriver->select('tbl_drivers.id','tbl_drivers.fullname','tbl_drivers.mobile'); 
            },

          'orderFile' => function ($qryOrderFile) {
                  $qryOrderFile->with([
                    'filelabel' => function($qryfilelabel) {
                    },
                ]);
                },
          'customer' => function ($qryCustomer) {
                  $qryCustomer->select('tbl_users.id','tbl_users.fullname','tbl_users.mobile','tbl_users.user_paymentbill_type'); 
            },
        ])
        ->where('is_active', constants('is_active_yes'))
        ->where('id', $request->order_id)
        ->first();


        return response()->json([
                'success' => constants('validResponse.success'),
                'message' => "Order Detail",
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


    /*------pickup verify of an order-------*/
   public function pickupVerify(Request $request)
   {
      $getGoogleMapDistanceMatrixApiResponse = ['nodt' => 120];
      try {
      CreateApiLogs($request->all());

      $validator = Validator::make($request->all(), [ 
            'order_id' => 'required|integer|min:1',
      ]);   

      if($validator->fails()) {          
          return response()->json(constants('checkRequiredField'), constants('invalidResponse.statusCode'));
      }
	  
  	  if(isset($request->current_latitude) && isset($request->current_longitude) && strlen($request->current_latitude)>4 && strlen($request->current_longitude)>4){
          Driver::where('id', $request->get('driver_id'))->update(['current_latitude' => $request->current_latitude, 'current_longitude' => $request->current_longitude]);
      }

      $dataOrder = Order::where('id', $request->order_id)->where('driver_id', $request->get('driver_id'))->where('is_active', constants('is_active_yes'))->whereIn('status', constants('order_status.assigned_orders'))->first();

      $driverData = Driver::where('is_active', constants('is_active_yes'))->where('id', $request->get('driver_id'))->first(['fullname']);

      if(empty($dataOrder)){
        return response()->json([
          'success' => constants('invalidResponse.success'),
          'message' => "No Order Found.",
          'data'    => constants('emptyData'),
         ], constants('invalidResponse.statusCode'));
      }


      $upload_fileSGP = []; $upload_fileSGP_count = 0;
        if(is_array($request->file('fileSGP')) && !empty($request->file('fileSGP'))){
            foreach($request->file('fileSGP') as $fx) {
                $upload_fileSGP_name = UploadImage($fx, constants('dir_name.order'),constants('order_file_type.signaturepickup'));
                $upload_fileSGP[] = $upload_fileSGP_name;
                $upload_fileSGP_count++;
            }
        }

      $upload_fileGP = []; $upload_fileGP_count = 0;
        if(is_array($request->file('fileGP')) && !empty($request->file('fileGP'))){
            foreach($request->file('fileGP') as $fx) {
                $upload_fileGP_name = UploadImage($fx, constants('dir_name.order'),constants('order_file_type.goodspickup'));
                $upload_fileGP[] = $upload_fileGP_name;
                $upload_fileGP_count++;
            }
        }

      if($upload_fileGP_count>0){
            foreach($upload_fileGP as $filename_dx) {
                $dt = [ 'order_id' => $request->order_id, 'img' => $filename_dx, 'img_type' => constants('order_file_type.goodspickup'),];
                OrderFile::create($dt);
            }
      }

      if($upload_fileSGP_count>0){
            foreach($upload_fileSGP as $filename_dx) {
                $dt = [ 'order_id' => $request->order_id, 'img' => $filename_dx, 'img_type' => constants('order_file_type.signaturepickup'),];
                OrderFile::create($dt);
            }
      }

      $dataPickup = [
          'status' => 'PP',
          'pickedup_datetime' => date('Y-m-d H:i:s'), 
      ];

      Order::where('id', $request->order_id)->update($dataPickup);


    /*-----google distance call if possible starts------*/

      $pickup_latitude = (isset($request->current_latitude) && strlen($request->current_latitude)>4) ? trim($request->current_latitude) : $dataOrder->pickup_latitude;
      $pickup_longitude = (isset($request->current_longitude) && strlen($request->current_longitude)>4) ? trim($request->current_longitude) :  $dataOrder->pickup_longitude;

      $getCurrentOrderArrangeData = OrderArrange::where('order_id', $request->order_id)->where('is_active' , constants('is_active_yes'))->where('is_completed' , constants('confirmation.no'))->where('arrangement_type' , constants('arrangement_type.pickup'))->where('driver_id', $request->get('driver_id'))->first(['id','orderaction_datetime','origins_latitude']);


      if(isset($getCurrentOrderArrangeData->id)){ 
          $getNextOrderArrangeData = OrderArrange::where('id', '!=', $getCurrentOrderArrangeData->id)->where('driver_id', $request->get('driver_id'))->where('is_active' , constants('is_active_yes'))->where('is_completed' , constants('confirmation.no'))->orderBy('arrangement_number' , 'ASC')->first();
          if(!empty($getNextOrderArrangeData)){  
              $dataNextOrder = Order::where('id', $getNextOrderArrangeData->order_id)->where('driver_id', $request->get('driver_id'))->where('is_active', constants('is_active_yes'))->first(['pickup_latitude','pickup_longitude','drop_latitude','drop_longitude','id']);
              if(!empty($dataNextOrder)){ 
                  if(strlen($pickup_latitude)>5 && strlen($pickup_longitude)>5 && strlen($dataNextOrder->pickup_latitude)>5 && strlen($dataNextOrder->pickup_longitude)>5 && strlen($dataNextOrder->drop_latitude)>5 && strlen($dataNextOrder->drop_longitude)>5){

                    if($getNextOrderArrangeData->arrangement_type==constants('arrangement_type.pickup')){

                      $latlngArray = [
                        'origins_latitude' => $pickup_latitude,
                        'origins_longitude' => $pickup_longitude,
                        'destinations_latitude' => $dataNextOrder->pickup_latitude,
                        'destinations_longitude' => $dataNextOrder->pickup_longitude,
                      ];
                      $getGoogleMapDistanceMatrixApiResponse = (new CustomService())->getGoogleMapDistanceMatrixApi($latlngArray);

                    }
                    elseif($getNextOrderArrangeData->arrangement_type==constants('arrangement_type.deliver')){

                      $latlngArray = [
                        'origins_latitude' => $pickup_latitude,
                        'origins_longitude' => $pickup_longitude,
                        'destinations_latitude' => $dataNextOrder->drop_latitude,
                        'destinations_longitude' => $dataNextOrder->drop_longitude,
                      ];
                      $getGoogleMapDistanceMatrixApiResponse = (new CustomService())->getGoogleMapDistanceMatrixApi($latlngArray);
                    }


                    CreateApiLogs($getGoogleMapDistanceMatrixApiResponse);


                    if(isset($getGoogleMapDistanceMatrixApiResponse['data']['rows'][0]['elements'][0]['duration']['value'])){

                      $valueSeconds = abs(intval($getGoogleMapDistanceMatrixApiResponse['data']['rows'][0]['elements'][0]['duration']['value']));
                      $valueSeconds = abs(intval($valueSeconds*1.11)) + 1;

                      $toNextDateTime = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . ' +'.$valueSeconds.' seconds'));
                      $between_meters = abs(intval($getGoogleMapDistanceMatrixApiResponse['data']['rows'][0]['elements'][0]['distance']['value']));


                      $updateDataNextOrderArrange = [
                        'orderaction_datetime' => $toNextDateTime,
                        'driveraction_datetime' => NULL,
                        'between_meters' => $between_meters,
                        'between_seconds' => $valueSeconds,
                        'origins_latitude' => $latlngArray['origins_latitude'],
                        'origins_longitude' => $latlngArray['origins_longitude'],
                      ];

                      OrderArrange::where('order_id', $dataNextOrder->id)->where('is_active' , constants('is_active_yes'))->where('is_completed' , constants('confirmation.no'))->where('arrangement_type' , $getNextOrderArrangeData->arrangement_type)->where('driver_id', $request->get('driver_id'))->update($updateDataNextOrderArrange);

                    }

                  }
              }
          }
      }

    /*-----google distance call if possible ends------*/

      createOrderLogs("Order #".$dataOrder->bigdaddy_lr_number." Picked Up By ".@$driverData->fullname, $request->order_id);
      createOrderLogs("Order Picked Up By Driver.", $request->order_id, 1);
      createCustomerNotificationLogs("Your Order #".$dataOrder->bigdaddy_lr_number." is Out For Delivery.", $dataOrder->user_id,$dataOrder->id,constants('notification_type.success'));
      pushNotificationToUser("Order ".constants('user_notification_type.on_pickup_order.name'), "Dear Customer, Your Order #".$dataOrder->bigdaddy_lr_number." is Out For Delivery.",'','','',$dataOrder->user_id);
      pushNotificationToUserApp("Order ".constants('user_notification_type.on_pickup_order.name'), "Dear Customer, Your Order #".$dataOrder->bigdaddy_lr_number." is Out For Delivery.", $dataOrder->user_id , ['order_id' => $dataOrder->id, 'user_notification_type' => constants('user_notification_type.on_pickup_order.name') ]);

      //sendMsg("Dear Customer, Your Order #".$dataOrder->bigdaddy_lr_number." is Out For Delivery.", $dataOrder->contact_person_phone_number_drop);


      /*-----google distance driver reports starts------*/
      $orderaction_datetime = isset($getCurrentOrderArrangeData->orderaction_datetime) ? $getCurrentOrderArrangeData->orderaction_datetime : date('Y-m-d H:i:s');
      $timeFirst  = strtotime($orderaction_datetime);
      $timeSecond = strtotime(date('Y-m-d H:i:s'));
      $differenceInSeconds = $timeSecond - $timeFirst;
      $is_early_fulfilled = 1;

      if($differenceInSeconds>0){
          $is_early_fulfilled = 0;
      }


      $updateDataCurrentOrderArrange = [
          'arrangement_number' => NULL,
          'is_completed' => constants('confirmation.yes'),
          'driveraction_datetime' => date('Y-m-d H:i:s'),
          'difference_seconds' => abs(intval($differenceInSeconds)),
          'is_early_fulfilled' => $is_early_fulfilled,
      ];
      OrderArrange::where('order_id', $request->order_id)->where('is_active' , constants('is_active_yes'))->where('is_completed' , constants('confirmation.no'))->where('arrangement_type', constants('arrangement_type.pickup'))->update($updateDataCurrentOrderArrange);

      /*-----google distance driver reports ends------*/



          return response()->json([
                'success' => constants('validResponse.success'),
                'message' => "Successful",
                'data'    => ["id" => $dataOrder->id ],
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



    /*------set pending or paid payment_status of an order-------*/
   public function deliverThisOrder(Request $request)
   {
    $getGoogleMapDistanceMatrixApiResponse = ['nodt' => 120];
      try {
      

      $validator = Validator::make($request->all(), [ 
            'order_id' => 'required|integer|min:1',
            //'payment_status' => 'required',
      ]);   

      if($validator->fails()) {          
          return response()->json(constants('checkRequiredField'), constants('invalidResponse.statusCode'));
      }

  	  if(isset($request->current_latitude) && isset($request->current_longitude) && strlen($request->current_latitude)>4 && strlen($request->current_longitude)>4){
  		    Driver::where('id', $request->get('driver_id'))->update(['current_latitude' => $request->current_latitude, 'current_longitude' => $request->current_longitude]);
  	  }

      if(isset($request->payment_status) && !in_array($request->payment_status, constants('payment_status'))){
          return response()->json(constants('checkRequiredField'), constants('invalidResponse.statusCode'));
      }


      $dataOrder = Order::where('id', $request->order_id)->where('driver_id', $request->get('driver_id'))->where('is_active', constants('is_active_yes'))->whereNotIn('status', constants('order_status.delivered_orders'))->first();

      $driverData = Driver::where('is_active', constants('is_active_yes'))->where('id', $request->get('driver_id'))->first(['fullname']);

      if(empty($dataOrder)){
        return response()->json([
          'success' => constants('invalidResponse.success'),
          'message' => "No Order Found.",
          'data'    => constants('emptyData'),
         ], constants('invalidResponse.statusCode'));
      }


      $upload_fileSGD = []; $upload_fileSGD_count = 0;
        if(is_array($request->file('fileSGD')) && !empty($request->file('fileSGD'))){
            foreach($request->file('fileSGD') as $fx) {
                $upload_fileSGD_name = UploadImage($fx, constants('dir_name.order'),constants('order_file_type.signaturedrop'));
                $upload_fileSGD[] = $upload_fileSGD_name;
                $upload_fileSGD_count++;
            }
        }

      $upload_fileGD = []; $upload_fileGD_count = 0;
        if(is_array($request->file('fileGD')) && !empty($request->file('fileGD'))){
            foreach($request->file('fileGD') as $fx) {
                $upload_fileGD_name = UploadImage($fx, constants('dir_name.order'),constants('order_file_type.goodsdrop'));
                $upload_fileGD[] = $upload_fileGD_name;
                $upload_fileGD_count++;
            }
        }

      if($upload_fileGD_count>0){
            foreach($upload_fileGD as $filename_dx) {
                $dt = [ 'order_id' => $request->order_id, 'img' => $filename_dx, 'img_type' => constants('order_file_type.goodsdrop'),];
                OrderFile::create($dt);
            }
      }

      if($upload_fileSGD_count>0){
            foreach($upload_fileSGD as $filename_dx) {
                $dt = [ 'order_id' => $request->order_id, 'img' => $filename_dx, 'img_type' => constants('order_file_type.signaturedrop'),];
                OrderFile::create($dt);
            }
      }


      $dataUpdate = [
          'status' => 'D',
          'delivered_datetime' => date('Y-m-d H:i:s'),
      ];

      if(isset($request->payment_status)){
        $dataUpdate['payment_status'] = $request->payment_status;
      }

      if(isset($request->payment_status) && $request->payment_status==constants('payment_status.Paid') && $dataOrder->payment_datetime==''){
        $dataUpdate['payment_datetime'] = date('Y-m-d H:i:s');
      }

      Order::where('id', $request->order_id)->update($dataUpdate);





  /*-----google distance call if possible starts------*/



      $drop_latitude = (isset($request->current_latitude) && strlen($request->current_latitude)>4) ? trim($request->current_latitude) : $dataOrder->drop_latitude;
      $drop_longitude = (isset($request->current_longitude) && strlen($request->current_longitude)>4) ? trim($request->current_longitude) :  $dataOrder->drop_longitude;


      $getCurrentOrderArrangeData = OrderArrange::where('order_id', $request->order_id)->where('is_active' , constants('is_active_yes'))->where('is_completed' , constants('confirmation.no'))->where('arrangement_type' , constants('arrangement_type.deliver'))->where('driver_id', $request->get('driver_id'))->first(['id','orderaction_datetime','origins_latitude']);


      if(isset($getCurrentOrderArrangeData->id)){ 
          $getNextOrderArrangeData = OrderArrange::where('id', '!=', $getCurrentOrderArrangeData->id)->where('driver_id', $request->get('driver_id'))->where('is_active' , constants('is_active_yes'))->where('is_completed' , constants('confirmation.no'))->orderBy('arrangement_number' , 'ASC')->first();
          if(!empty($getNextOrderArrangeData)){  
              $dataNextOrder = Order::where('id', $getNextOrderArrangeData->order_id)->where('driver_id', $request->get('driver_id'))->where('is_active', constants('is_active_yes'))->first(['pickup_latitude','pickup_longitude','drop_latitude','drop_longitude','id']);
              if(!empty($dataNextOrder)){ 
                  if(strlen($drop_latitude)>5 && strlen($drop_longitude)>5 && strlen($dataNextOrder->pickup_latitude)>5 && strlen($dataNextOrder->pickup_longitude)>5 && strlen($dataNextOrder->drop_latitude)>5 && strlen($dataNextOrder->drop_longitude)>5){

                    if($getNextOrderArrangeData->arrangement_type==constants('arrangement_type.pickup')){

                      $latlngArray = [
                        'origins_latitude' => $drop_latitude,
                        'origins_longitude' => $drop_longitude,
                        'destinations_latitude' => $dataNextOrder->pickup_latitude,
                        'destinations_longitude' => $dataNextOrder->pickup_longitude,
                      ];
                      $getGoogleMapDistanceMatrixApiResponse = (new CustomService())->getGoogleMapDistanceMatrixApi($latlngArray);

                    }
                    elseif($getNextOrderArrangeData->arrangement_type==constants('arrangement_type.deliver')){

                      $latlngArray = [
                        'origins_latitude' => $drop_latitude,
                        'origins_longitude' => $drop_longitude,
                        'destinations_latitude' => $dataNextOrder->drop_latitude,
                        'destinations_longitude' => $dataNextOrder->drop_longitude,
                      ];
                      $getGoogleMapDistanceMatrixApiResponse = (new CustomService())->getGoogleMapDistanceMatrixApi($latlngArray);
                    }

                    CreateApiLogs($getGoogleMapDistanceMatrixApiResponse);


                    if(isset($getGoogleMapDistanceMatrixApiResponse['data']['rows'][0]['elements'][0]['duration']['value'])){

                      $valueSeconds = abs(intval($getGoogleMapDistanceMatrixApiResponse['data']['rows'][0]['elements'][0]['duration']['value']));
                      $valueSeconds = abs(intval($valueSeconds*1.11)) + 1;

                      $toNextDateTime = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . ' +'.$valueSeconds.' seconds'));
                      $between_meters = abs(intval($getGoogleMapDistanceMatrixApiResponse['data']['rows'][0]['elements'][0]['distance']['value']));


                      $updateDataNextOrderArrange = [
                        'orderaction_datetime' => $toNextDateTime,
                        'driveraction_datetime' => NULL,
                        'between_meters' => $between_meters,
                        'between_seconds' => $valueSeconds,
                        'origins_latitude' => $latlngArray['origins_latitude'],
                        'origins_longitude' => $latlngArray['origins_longitude'],
                      ];

                      OrderArrange::where('order_id', $dataNextOrder->id)->where('is_active' , constants('is_active_yes'))->where('is_completed' , constants('confirmation.no'))->where('arrangement_type' , $getNextOrderArrangeData->arrangement_type)->where('driver_id', $request->get('driver_id'))->update($updateDataNextOrderArrange);


                    }

                  }
              }
          }
      }

  /*-----google distance call if possible ends------*/

      createOrderLogs("Order #".$dataOrder->bigdaddy_lr_number." Delivered By ".$driverData->fullname, $dataOrder->id);
      createOrderLogs("Order Delivered By Driver.", $dataOrder->id, 1);
      createCustomerNotificationLogs("Your Order #".$dataOrder->bigdaddy_lr_number." has been Delivered By ".$driverData->fullname.".", $dataOrder->user_id,$dataOrder->id,constants('notification_type.success'));
      pushNotificationToUser("Order ".constants('user_notification_type.on_deliver_order.name'), "Dear Customer, Your Order #".$dataOrder->bigdaddy_lr_number." has been Delivered.",'','','',$dataOrder->user_id);
      pushNotificationToUserApp("Order ".constants('user_notification_type.on_deliver_order.name'), "Dear Customer, Your Order #".$dataOrder->bigdaddy_lr_number." has been Delivered.", $dataOrder->user_id , ['order_id' => $dataOrder->id, 'user_notification_type' => constants('user_notification_type.on_deliver_order.name') ]);
      
      //sendMsg("Dear Customer, Your Order #".$dataOrder->bigdaddy_lr_number." has been Delivered By ".$driverData->fullname.".", $dataOrder->contact_person_phone_number_drop);
      pushNotificationToAdmin("Order Delivered","Order #".$dataOrder->bigdaddy_lr_number." has been Delivered By ".$driverData->fullname.".",$icon='',$image='',route('delivered-orders'));
      createAdminNotificationLogs("Order Delivered","Order #".$dataOrder->bigdaddy_lr_number." has been Delivered By ".$driverData->fullname.".", route('delivered-orders'));

       /*-----google distance driver reports starts------*/
      $orderaction_datetime = isset($getCurrentOrderArrangeData->orderaction_datetime) ? $getCurrentOrderArrangeData->orderaction_datetime : date('Y-m-d H:i:s');
      $timeFirst  = strtotime($orderaction_datetime);
      $timeSecond = strtotime(date('Y-m-d H:i:s'));
      $differenceInSeconds = $timeSecond - $timeFirst;
      $is_early_fulfilled = 1;

      if($differenceInSeconds>0){
          $is_early_fulfilled = 0;
      }

      $updateDataCurrentOrderArrange = [
          'arrangement_number' => NULL,
          'is_completed' => constants('confirmation.yes'),
          'driveraction_datetime' => date('Y-m-d H:i:s'),
          'difference_seconds' => abs(intval($differenceInSeconds)),
          'is_early_fulfilled' => $is_early_fulfilled,
      ];
      OrderArrange::where('order_id', $request->order_id)->where('is_active' , constants('is_active_yes'))->where('is_completed' , constants('confirmation.no'))->where('arrangement_type', constants('arrangement_type.deliver'))->update($updateDataCurrentOrderArrange);

      OrderArrange::where('order_id', $request->order_id)->where('is_active' , constants('is_active_yes'))->where('is_completed' , constants('confirmation.no'))->whereIn('arrangement_type' , constants('arrangement_type'))->delete();

      /*-----google distance driver reports ends------*/

      return response()->json([
                'success' => constants('validResponse.success'),
                'message' => "Order Delivered Successful.",
                'data'    => [ "id" => $dataOrder->id ],
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


    /*------pickup verify of an order-------*/
   public function undeliverThisOrder(Request $request)
   {
    $getGoogleMapDistanceMatrixApiResponse = ['nodt' => 120]; 
      try {

      CreateApiLogs($request->all());

      $validator = Validator::make($request->all(), [ 
            'order_id' => 'required|numeric',
            'undelivered_reason_id' => 'required|numeric',
      ]);   

      if($validator->fails()) {          
          return response()->json(constants('checkRequiredField'), constants('invalidResponse.statusCode'));
      }

  	  if(isset($request->current_latitude) && isset($request->current_longitude) && strlen($request->current_latitude)>4 && strlen($request->current_longitude)>4){
          Driver::where('id', $request->get('driver_id'))->update(['current_latitude' => $request->current_latitude, 'current_longitude' => $request->current_longitude]);
      }


      $reasonForData = ReasonFor::where('id', $request->undelivered_reason_id)->first();

      $dataOrder = Order::where('id', $request->order_id)->where('driver_id', $request->get('driver_id'))->where('is_active', constants('is_active_yes'))->whereIn('status', constants('order_status.pickedup_orders'))->first();

      $driverData = Driver::where('is_active', constants('is_active_yes'))->where('id', $request->get('driver_id'))->first(['fullname']);

      if(empty($dataOrder)){
        return response()->json([
          'success' => constants('invalidResponse.success'),
          'message' => "No Order Found.",
          'data'    => constants('emptyData'),
         ], constants('invalidResponse.statusCode'));
      }


      $dataUpdate = [
          'status' => 'U',
          'if_undelivered_reason_id' => $request->undelivered_reason_id,
          'if_undelivered_reason_text' => $reasonForData->name,
          'undelivered_datetime' => date('Y-m-d H:i:s'),
      ];

      Order::where('id', $request->order_id)->update($dataUpdate);


  /*-----google distance call if possible starts------*/

      $drop_latitude = (isset($request->current_latitude) && strlen($request->current_latitude)>4) ? trim($request->current_latitude) : $dataOrder->drop_latitude;
      $drop_longitude = (isset($request->current_longitude) && strlen($request->current_longitude)>4) ? trim($request->current_longitude) :  $dataOrder->drop_longitude;

      $getCurrentOrderArrangeData = OrderArrange::where('order_id', $request->order_id)->where('is_active' , constants('is_active_yes'))->where('arrangement_type' , constants('arrangement_type.deliver'))->where('driver_id', $request->get('driver_id'))->first(['id','orderaction_datetime','origins_latitude']);


      if(isset($getCurrentOrderArrangeData->id)){ 
          $getNextOrderArrangeData = OrderArrange::where('id', '!=', $getCurrentOrderArrangeData->id)->where('driver_id', $request->get('driver_id'))->where('is_active' , constants('is_active_yes'))->orderBy('arrangement_number' , 'ASC')->first();
          if(!empty($getNextOrderArrangeData)){  
              $dataNextOrder = Order::where('id', $getNextOrderArrangeData->order_id)->where('driver_id', $request->get('driver_id'))->where('is_active', constants('is_active_yes'))->first(['pickup_latitude','pickup_longitude','drop_latitude','drop_longitude','id']);
              if(!empty($dataNextOrder)){ 
                  if(strlen($drop_latitude)>5 && strlen($drop_longitude)>5 && strlen($dataNextOrder->pickup_latitude)>5 && strlen($dataNextOrder->pickup_longitude)>5 && strlen($dataNextOrder->drop_latitude)>5 && strlen($dataNextOrder->drop_longitude)>5){

                    if($getNextOrderArrangeData->arrangement_type==constants('arrangement_type.pickup')){

                      $latlngArray = [
                        'origins_latitude' => $drop_latitude,
                        'origins_longitude' => $drop_longitude,
                        'destinations_latitude' => $dataNextOrder->pickup_latitude,
                        'destinations_longitude' => $dataNextOrder->pickup_longitude,
                      ];
                      $getGoogleMapDistanceMatrixApiResponse = (new CustomService())->getGoogleMapDistanceMatrixApi($latlngArray);

                    }
                    elseif($getNextOrderArrangeData->arrangement_type==constants('arrangement_type.deliver')){

                      $latlngArray = [
                        'origins_latitude' => $drop_latitude,
                        'origins_longitude' => $drop_longitude,
                        'destinations_latitude' => $dataNextOrder->drop_latitude,
                        'destinations_longitude' => $dataNextOrder->drop_longitude,
                      ];
                      $getGoogleMapDistanceMatrixApiResponse = (new CustomService())->getGoogleMapDistanceMatrixApi($latlngArray);
                    }

                    CreateApiLogs($getGoogleMapDistanceMatrixApiResponse);


                    if(isset($getGoogleMapDistanceMatrixApiResponse['data']['rows'][0]['elements'][0]['duration']['value'])){

                      $valueSeconds = abs(intval($getGoogleMapDistanceMatrixApiResponse['data']['rows'][0]['elements'][0]['duration']['value']));
                      $valueSeconds = abs(intval($valueSeconds*1.11)) + 1;

                      $toNextDateTime = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . ' +'.$valueSeconds.' seconds'));
                      $between_meters = abs(intval($getGoogleMapDistanceMatrixApiResponse['data']['rows'][0]['elements'][0]['distance']['value']));


                      $updateDataNextOrderArrange = [
                        'orderaction_datetime' => $toNextDateTime,
                        'driveraction_datetime' => NULL,
                        'between_meters' => $between_meters,
                        'between_seconds' => $valueSeconds,
                        'origins_latitude' => $latlngArray['origins_latitude'],
                        'origins_longitude' => $latlngArray['origins_longitude'],
                      ];

                      OrderArrange::where('order_id', $dataNextOrder->id)->where('is_active' , constants('is_active_yes'))->where('is_completed' , constants('confirmation.no'))->where('arrangement_type' , $getNextOrderArrangeData->arrangement_type)->where('driver_id', $request->get('driver_id'))->update($updateDataNextOrderArrange);
                      
                    }

                  }
              }
          }
      }

  /*-----google distance call if possible ends------*/

      createOrderLogs("Order #".$dataOrder->bigdaddy_lr_number." Undelivered By ".$driverData->fullname." [" .$reasonForData->name."]", $dataOrder->id);
      createOrderLogs("Order Undelivered Due to Some Reason.", $dataOrder->id, 1);
      createCustomerNotificationLogs("Your Order #".$dataOrder->bigdaddy_lr_number." has been Undelivered.", $dataOrder->user_id,$dataOrder->id,constants('notification_type.success'));
      pushNotificationToUser("Order ".constants('user_notification_type.on_undeliver_order.name'), "Dear Customer, Your Order #".$dataOrder->bigdaddy_lr_number." has been Undelivered.",'','','',$dataOrder->user_id);

      pushNotificationToUserApp("Order ".constants('user_notification_type.on_undeliver_order.name'), "Dear Customer, Your Order #".$dataOrder->bigdaddy_lr_number." has been Undelivered.", $dataOrder->user_id , ['order_id' => $dataOrder->id, 'user_notification_type' => constants('user_notification_type.on_undeliver_order.name') ]);


      pushNotificationToAdmin("Order UnDelivered","Order #".$dataOrder->bigdaddy_lr_number." has been Undelivered By ".$driverData->fullname.".",$icon='',$image='',route('undelivered-orders'));
      //sendMsg("Dear Customer, Your Order #".$dataOrder->bigdaddy_lr_number." has been Undelivered.", $dataOrder->contact_person_phone_number_drop);
      

       /*-----google distance driver reports starts------*/
      $orderaction_datetime = isset($getCurrentOrderArrangeData->orderaction_datetime) ? $getCurrentOrderArrangeData->orderaction_datetime : date('Y-m-d H:i:s');
      $timeFirst  = strtotime($orderaction_datetime);
      $timeSecond = strtotime(date('Y-m-d H:i:s'));
      $differenceInSeconds = $timeSecond - $timeFirst;
      $is_early_fulfilled = 1;

      if($differenceInSeconds>0){
          $is_early_fulfilled = 0;
      }

      $updateDataCurrentOrderArrange = [
          'arrangement_number' => NULL,
          'is_completed' => constants('confirmation.yes'),
          'driveraction_datetime' => date('Y-m-d H:i:s'),
          'difference_seconds' => abs(intval($differenceInSeconds)),
          'is_early_fulfilled' => $is_early_fulfilled,
          'arrangement_type' => constants('arrangement_type.undeliver'),
      ];
      OrderArrange::where('order_id', $request->order_id)->where('is_active' , constants('is_active_yes'))->where('is_completed' , constants('confirmation.no'))->where('arrangement_type', constants('arrangement_type.deliver'))->update($updateDataCurrentOrderArrange);

      OrderArrange::where('order_id', $request->order_id)->where('is_active' , constants('is_active_yes'))->where('is_completed' , constants('confirmation.no'))->whereIn('arrangement_type' , constants('arrangement_type'))->delete();

      /*-----google distance driver reports ends------*/


          return response()->json([
                'success' => constants('validResponse.success'),
                'message' => "Order Undelivered.",
                'data'    => [ "id" => $dataOrder->id ],
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


    /*------sending images when order details mismatching and not changing order status-------*/
   public function sendOrderMismatchDataThisOrder(Request $request)
   {
      try {  
      CreateApiLogs($request->all());

      $validator = Validator::make($request->all(), [ 
            'order_id' => 'required|integer|min:1',
            'fileGP.*' => 'required',
      ]);   

      if($validator->fails()) {          
          return response()->json(constants('checkRequiredField'), constants('invalidResponse.statusCode'));
      }
  	  
      if(isset($request->current_latitude) && isset($request->current_longitude) && strlen($request->current_latitude)>4 && strlen($request->current_longitude)>4){
          Driver::where('id', $request->get('driver_id'))->update(['current_latitude' => $request->current_latitude, 'current_longitude' => $request->current_longitude]);
      }

      $dataOrder = Order::where('id', $request->order_id)->where('driver_id', $request->get('driver_id'))->where('is_active', constants('is_active_yes'))->whereIn('status', constants('order_status.assigned_orders'))->first();

      $driverData = Driver::where('is_active', constants('is_active_yes'))->where('id', $request->get('driver_id'))->first(['fullname']);

      if(empty($dataOrder)){
        return response()->json([
          'success' => constants('invalidResponse.success'),
          'message' => "No Order Found.",
          'data'    => constants('emptyData'),
         ], constants('invalidResponse.statusCode'));
      }


      $upload_fileGP = []; $upload_fileGP_count = 0;
        if(is_array($request->file('fileGP')) && !empty($request->file('fileGP'))){
            foreach($request->file('fileGP') as $fx) {
                $upload_fileGP_name = UploadImage($fx, constants('dir_name.order'),constants('order_file_type.goodspickup'));
                $upload_fileGP[] = $upload_fileGP_name;
                $upload_fileGP_count++;
            }
        }

      if($upload_fileGP_count>0){
            foreach($upload_fileGP as $filename_dx) {
                $dt = [ 'order_id' => $request->order_id, 'img' => $filename_dx, 'img_type' => constants('order_file_type.goodspickup'),];
                OrderFile::create($dt);
            }
      }

      createOrderLogs("Order #".$dataOrder->bigdaddy_lr_number." Detail Mismatched By ".$driverData->fullname, $dataOrder->id);

      pushNotificationToAdmin("Order Mismatched","Order #".$dataOrder->bigdaddy_lr_number." Detail Mismatched By ".$driverData->fullname.".",$icon='',$image='',route('view-order').'/'.$dataOrder->id);

      createAdminNotificationLogs("Order Mismatched","Order #".$dataOrder->bigdaddy_lr_number." Detail Mismatched By ".$driverData->fullname.".", route('view-order')."/".$dataOrder->id);

      return response()->json([
                'success' => constants('validResponse.success'),
                'message' => "Order Mismatched Data Sent.",
                'data'    => [ "id" => $dataOrder->id ] ,
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
   public function getApiLogs(Request $request)
   {
     
      $dataApi = ApiLog::limit(constants('extra_large_limit'))
        ->orderBy('id','DESC')
        ->get();

        return response()->json([
            'success' => constants('validResponse.success'),
            'message' => "dataApi List",
            'data'    => $dataApi,
        ], constants('validResponse.statusCode'));
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
    

      $reasonforData = ReasonFor::where('type', $request->reasonfor)
        ->limit(constants('extra_large_limit'))
        ->orderBy('id','ASC')
        ->get();

      return response()->json([
            'success' => constants('validResponse.success'),
            'message' => "Reason List",
            'data'    => $reasonforData,
            'count'   => count($reasonforData),
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





   public function testGoogleMaps(Request $request)
   {

      $dataOrder = Order::where('id', $request->order_id)->where('driver_id', $request->get('driver_id'))->where('is_active', constants('is_active_yes'))->first();

  
      $getGoogleMapDistanceMatrixApiResponse = ['nodata' => 0];




      /*-----google distance call if possible starts------*/

      $pickup_latitude = $dataOrder->pickup_latitude;
      $pickup_longitude = $dataOrder->pickup_longitude;

      $getCurrentOrderArrangeData = OrderArrange::where('order_id', $request->order_id)->where('is_active' , constants('is_active_yes'))->where('arrangement_type' , constants('arrangement_type.pickup'))->where('driver_id', $request->get('driver_id'))->first(['id']);


      if(isset($getCurrentOrderArrangeData->id)){ 
          $getNextOrderArrangeData = OrderArrange::where('id', '!=', $getCurrentOrderArrangeData->id)->where('driver_id', $request->get('driver_id'))->where('is_active' , constants('is_active_yes'))->orderBy('arrangement_number' , 'ASC')->first();
          if(!empty($getNextOrderArrangeData)){  
              $dataNextOrder = Order::where('id', $getNextOrderArrangeData->order_id)->where('driver_id', $request->get('driver_id'))->where('is_active', constants('is_active_yes'))->first(['pickup_latitude','pickup_longitude','drop_latitude','drop_longitude','id']);
              if(!empty($dataNextOrder)){ 
                  if(strlen($pickup_latitude)>5 && strlen($pickup_longitude)>5 && strlen($dataNextOrder->pickup_latitude)>5 && strlen($dataNextOrder->pickup_longitude)>5 && strlen($dataNextOrder->drop_latitude)>5 && strlen($dataNextOrder->drop_longitude)>5){

                    if($getNextOrderArrangeData->arrangement_type==constants('arrangement_type.pickup')){

                      $latlngArray = [
                        'origins_latitude' => $pickup_latitude,
                        'origins_longitude' => $pickup_longitude,
                        'destinations_latitude' => $dataNextOrder->pickup_latitude,
                        'destinations_longitude' => $dataNextOrder->pickup_longitude,
                      ];
                      $getGoogleMapDistanceMatrixApiResponse = (new CustomService())->getGoogleMapDistanceMatrixApi($latlngArray);

                    }
                    elseif($getNextOrderArrangeData->arrangement_type==constants('arrangement_type.deliver')){

                      $latlngArray = [
                        'origins_latitude' => $pickup_latitude,
                        'origins_longitude' => $pickup_longitude,
                        'destinations_latitude' => $dataNextOrder->drop_latitude,
                        'destinations_longitude' => $dataNextOrder->drop_longitude,
                      ];
                      $getGoogleMapDistanceMatrixApiResponse = (new CustomService())->getGoogleMapDistanceMatrixApi($latlngArray);
                    }


                    if(isset($getGoogleMapDistanceMatrixApiResponse['data']['rows'][0]['elements'][0]['duration']['value'])){

                      $valueSeconds = abs(intval($getGoogleMapDistanceMatrixApiResponse['data']['rows'][0]['elements'][0]['duration']['value']));
                      $valueSeconds = abs(intval($valueSeconds*1.11));

                      $toNextDateTime = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . ' +'.$valueSeconds.' seconds'));

                      OrderArrange::where('order_id', $dataNextOrder->id)->where('is_active' , constants('is_active_yes'))->where('arrangement_type' , $getNextOrderArrangeData->arrangement_type)->where('driver_id', $request->get('driver_id'))->update(['orderaction_datetime' => $toNextDateTime]);



                    }

                  }
              }
          }
      }

      /*-----google distance call if possible ends------*/



      return response()->json([
            'success' => constants('validResponse.success'),
            'message' => "Google Maps Api",
            'data'    => $getGoogleMapDistanceMatrixApiResponse,
            'd' => $dataNextOrder,
            'dt' =>  90,
        ], constants('validResponse.statusCode'));




    }


    











}
