<?php

namespace App\Http\Controllers\Client\Api\driver;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use App\Models\DriverNotificationLog;
use App\Models\Order;
use App\Models\Driver;
use App\Models\Company;
date_default_timezone_set('Asia/Kolkata');
use Illuminate\Support\Facades\Log;


class ProfileController extends Controller
{
    


   /*------get profile information of driver-------*/
   public function profileInfo(Request $request)
   {
      try {
  	 
     if(isset($request->current_latitude) && isset($request->current_longitude) && strlen($request->current_latitude)>4 && strlen($request->current_longitude)>4){
          Driver::where('id', $request->get('driver_id'))->update(['current_latitude' => $request->current_latitude, 'current_longitude' => $request->current_longitude]);
      }

   		$driverData = Driver::with(['driver_file','vehicle'])
                    ->where('is_active', constants('is_active_yes'))
                    ->where('id', $request->get('driver_id'))
                    ->first();

      $orderPendingCount = Order::where('driver_id',$request->get('driver_id'))->where('is_active',constants('is_active_yes'))->whereIn('status', ['A','PP'])->where('driver_assigned_datetime','LIKE', date('Y-m-d')."%")->count();

	   	return response()->json([
            'success' => constants('validResponse.success'),
            'message' => "Driver Profile Information",
            'data'    => $driverData,

            'data2'    => [ 'orderPendingCount' => $orderPendingCount,  ],

            'fileurl' => sendPath().constants('dir_name.driver'),
            'fileurl2' => sendPath().constants('dir_name.vehicle'),
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

   /*------get selected Driver notification lists limit 10-------*/
   public function getNotificationList(Request $request)
   {
      try {

      
      if(isset($request->current_latitude) && isset($request->current_longitude) && strlen($request->current_latitude)>4 && strlen($request->current_longitude)>4){
          Driver::where('id', $request->get('driver_id'))->update(['current_latitude' => $request->current_latitude, 'current_longitude' => $request->current_longitude]);
      }

      $searched = ''; $pageno = 0;

      $countNotification = DriverNotificationLog::where('driver_id', $request->get('driver_id'))->count();

      if(isset($request->search) && trim($request->search)!=''){
        $searched = $request->search;
      }

      if(isset($request->pageno)){
        $pageno = intval($request->pageno);
      }


      $notificationData = DriverNotificationLog::where('driver_id', $request->get('driver_id'))
        ->offset($pageno*constants('basic_limit'))
        ->limit(constants('basic_limit'))
        ->orderBy('id','DESC')
        ->get();

      return response()->json([
            'success' => constants('validResponse.success'),
            'message' => "Notification List",
            'data'    => $notificationData,
            'count'    => $countNotification,
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

   /*------get dashboard information of driver-------*/
   public function dashboardAmount(Request $request)
   {
      try {
      $today = date('Y-m-d')."%"; $amountData = [];

      if(isset($request->current_latitude) && isset($request->current_longitude) && strlen($request->current_latitude)>4 && strlen($request->current_longitude)>4){
          Driver::where('id', $request->get('driver_id'))->update(['current_latitude' => $request->current_latitude, 'current_longitude' => $request->current_longitude]);
      }

      $driverData = Driver::where('is_active', constants('is_active_yes'))->where('id', $request->get('driver_id'))->first();


      //$amountData['today']['collected'] = Order::where('is_active', constants('is_active_yes'))->where('driver_id', $request->get('driver_id'))->where('delivered_datetime', 'LIKE', $today)->where('payment_type', constants('payment_type.COD'))->where('payment_status', constants('payment_status.Paid'))->count();

      $sql = "SELECT (sum(final_cost)+sum(min_order_value_charge)+sum(redeliver_charge)-sum(discount)) as totalCost from tbl_orders WHERE is_active='".constants('is_active_yes')."' AND driver_id='".$request->get('driver_id')."' AND delivered_datetime LIKE '".$today."' AND payment_type='".constants('payment_type.COD')."' AND payment_status='".constants('payment_status.Paid')."' ";
      $amountData['today']['collected'] = floatval(qry($sql)[0]->totalCost);

      $sql = "SELECT (sum(final_cost)+sum(min_order_value_charge)+sum(redeliver_charge)-sum(discount)) as totalCost from tbl_orders WHERE is_active='".constants('is_active_yes')."' AND driver_id='".$request->get('driver_id')."' AND driver_assigned_datetime LIKE '".$today."' AND payment_type='".constants('payment_type.COD')."' AND payment_status='".constants('payment_status.Pending')."' ";
      $amountData['today']['pending'] = floatval(qry($sql)[0]->totalCost);

      $amountData['today']['tobecollected'] = floatval($amountData['today']['pending']) + floatval($amountData['today']['collected']);


      /*---------------transpoter---------------*/
      $order_status = "'" . implode ( "', '", constants('order_status.active_transit_orders')) . "'";
      $order_status_sql =  " and o.status IN(".$order_status.") ";
      $order_status_sql_not =  " and o.status NOT IN(".$order_status.") ";

      $sql = "SELECT (sum(o.transport_cost)) as totalCost from tbl_orders o WHERE o.is_active='".constants('is_active_yes')."' AND o.driver_id='".$request->get('driver_id')."' AND o.driver_assigned_datetime LIKE '".$today."' AND o.payment_type='".constants('payment_type.COD')."' AND o.payment_status='".constants('payment_status.Pending')."' $order_status_sql ";
      $amountData['today']['transpotercostall_pending'] = floatval(qry($sql)[0]->totalCost);


      $sql = "SELECT (sum(o.transport_cost)) as totalCost from tbl_orders o WHERE o.is_active='".constants('is_active_yes')."' AND o.driver_id='".$request->get('driver_id')."' AND o.delivered_datetime LIKE '".$today."' AND o.payment_type='".constants('payment_type.COD')."' $order_status_sql_not ";
      

      $amountData['today']['transpotercostall_collected'] = floatval(qry($sql)[0]->totalCost);


      $sql = "SELECT (sum(o.final_cost) + sum(o.min_order_value_charge)+sum(o.redeliver_charge)-sum(o.discount)) as totalCost from tbl_orders o WHERE o.is_active='".constants('is_active_yes')."' AND o.driver_id='".$request->get('driver_id')."' AND o.driver_assigned_datetime LIKE '".$today."' AND o.payment_type='".constants('payment_type.COD')."' AND o.payment_status='".constants('payment_status.Pending')."' $order_status_sql ";
      $amountData['today']['deliverycostall_pending'] = floatval(qry($sql)[0]->totalCost);


      $sql = "SELECT (sum(o.final_cost)+sum(o.min_order_value_charge)+sum(o.redeliver_charge)-sum(o.discount)) as totalCost from tbl_orders o WHERE o.is_active='".constants('is_active_yes')."' AND o.driver_id='".$request->get('driver_id')."' AND o.delivered_datetime LIKE '".$today."' AND o.payment_type='".constants('payment_type.COD')."' AND o.payment_status='".constants('payment_status.Paid')."' $order_status_sql_not ";
      $amountData['today']['deliverycostall_collected'] = floatval(qry($sql)[0]->totalCost);


      $amountData['today']['totalamountinwallet'] = floatval($amountData['today']['deliverycostall_collected']) + floatval($amountData['today']['transpotercostall_collected']);


      return response()->json([
            'success' => constants('validResponse.success'),
            'message' => "Dashboard Amount Information",
            'data'    => ["amount" => $amountData ],
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


   /*------send Emergency Notification to admin by driver------*/
   public function sendEmergencyNotification(Request $request)
   {  
      try {
      CreateApiLogs($request->all());

      $validator = Validator::make($request->all(), [ 
            'web_notification' => 'required',
            'sms_notification' => 'required',
      ]);   

      if($validator->fails()) {          
          return response()->json(constants('checkRequiredField'), constants('invalidResponse.statusCode'));
      }
	  
  	  
      if(isset($request->current_latitude) && isset($request->current_longitude) && strlen($request->current_latitude)>4 && strlen($request->current_longitude)>4){
          Driver::where('id', $request->get('driver_id'))->update(['current_latitude' => $request->current_latitude, 'current_longitude' => $request->current_longitude]);
      }
	  
      Driver::where('id', $request->get('driver_id'))->update(['status' => constants('driver_status.Breakdown') ]);

      $driverData = Driver::where('id', $request->get('driver_id'))->first();

      $returnId = createDriverNotificationLogs("Emergency", $driverData->fullname." has Sent an Emergency Notification.",$request->get('driver_id'),0);

      $dataCompany = Company::where('id', constants('company_configurations_id'))->orderBy('id','ASC')->first();


      createAdminNotificationLogs("Emergency for ".$driverData->fullname  , $driverData->fullname." Driver has Sent an Emergency Notification.", route('driver-list') );


      if($request->web_notification==1){
        pushNotificationToAdmin("Emergency for ".$driverData->fullname , $driverData->fullname." Driver has Sent an Emergency Notification.",$icon='',$image='',$linkurl='');
      }

      if($request->sms_notification==1){
        sendMsg("Emergency, ".$driverData->fullname." Driver has Sent an Emergency Notification.", $dataCompany->emergency_no_for_driver);
      }


      return response()->json([
            'success' => constants('validResponse.success'),
            'message' => "Emergency Notification Sent.",
            'data'    => ['id' => $returnId],
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



   /*------get profile information of driver-------*/
   public function companyProfileInfo(Request $request)
   {
      try {
      $dataCompany = Company::where('id', constants('company_configurations_id'))->orderBy('id','ASC')->first(['email','company_name','mobile','country','state','city','pincode','address','landmark','logo','emergency_no_for_driver']);

      return response()->json([
            'success' => constants('validResponse.success'),
            'message' => "Company Profile Information",
            'data'    => $dataCompany,
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


   /*------update Current location------*/
   public function updateCurrentLocation(Request $request)
   {
      try {
  	  
      if(isset($request->current_latitude) && isset($request->current_longitude) && strlen($request->current_latitude)>4 && strlen($request->current_longitude)>4){
          Driver::where('id', $request->get('driver_id'))->update(['current_latitude' => $request->current_latitude, 'current_longitude' => $request->current_longitude]);
      }

      return response()->json([
            'success' => constants('validResponse.success'),
            'message' => "",
            'data'    => [
              "id" => $request->get('driver_id'),
              "currentdatetime" => time() ,
              "realtime" => Carbon::parse(date('Y-m-d H:i:s'))->format('d-m-Y h:i:s A'),
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



   /*------update current Driver Status------*/
   public function updateDriverStatus(Request $request)
   {  
      try {
      CreateApiLogs($request->all());
      $validator = Validator::make($request->all(), [ 
            'status' => 'required',
      ]);   

      if($validator->fails() || !in_array($request->status, constants('driver_status'))) {          
          return response()->json(constants('checkRequiredField'), constants('invalidResponse.statusCode'));
      }
	  
  	 
     if(isset($request->current_latitude) && isset($request->current_longitude) && strlen($request->current_latitude)>4 && strlen($request->current_longitude)>4){
          Driver::where('id', $request->get('driver_id'))->update(['current_latitude' => $request->current_latitude, 'current_longitude' => $request->current_longitude]);
      }
      
      Driver::where('id', $request->get('driver_id'))->update([ 'status' => $request->status ]);


      return response()->json([
            'success' => constants('validResponse.success'),
            'message' => "Status Updated Successfully.",
            'data'    => [ 'status' => $request->status ],
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
