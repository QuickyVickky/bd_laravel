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
use App\Models\Coupon;
use Illuminate\Support\Facades\Validator;
use App\Models\WalletTransaction;
use App\Http\Controllers\Client\Api\customer\RazorPayController;
use App\Http\Controllers\Client\Api\customer\OrderController;



class CouponController extends Controller
{

    public function __construct()
    {
        ini_set('max_execution_time', 300);
    }

    /*------apply coupon on order----*/
   public function applyCouponCode(Request $request)
   {    
        try {
        $validator = Validator::make($request->all(), [ 
            'applied_coupon_code'=> 'required',
            'order_id'=> 'required|integer|min:1',
        ]);   

        if($validator->fails()) {  
            return response()->json(constants('checkRequiredField'), constants('invalidResponse.statusCode'));    
        } 

        $applied_coupon_code = strtoupper($request->applied_coupon_code);

        $orderData = Order::where('is_active', constants('is_active_yes'))->whereIn('status', constants('order_status.approved_orders'))->where('user_id', $request->get('user_id'))->where('id', $request->order_id)->where('payment_status', constants('payment_status.Pending'))->first();

        if(empty($orderData)) {
            return response()->json([
            'success' => constants('invalidResponse.success'),
            'message' => "Oops, No Valid Order Found.",
            'data'    => [
                'order_id' => $request->order_id,
                'applied_coupon_code' => $applied_coupon_code,
                 ],
            ], constants('invalidResponse.statusCode')); 
        }


        if($orderData->coupon_code_id > 0){
            $orderNewData = Order::where('id', $request->order_id)->first();
            $dataCouponApplied = Coupon::where('id', $orderData->coupon_code_id)->first(['used_count']);

            $orderUpdate = [
                "coupon_code_applied" => NULL,
                "coupon_code_id" => NULL,
                "coupon_benefit_amount" => 0,
                "total_payable_amount" => $orderData->total_payable_amount + $orderData->coupon_benefit_amount,
                "discount" => $orderData->discount - $orderData->coupon_benefit_amount,
            ];
            Order::where('id', $request->order_id)->update($orderUpdate);

            if(!empty($dataCouponApplied)){
                $couponUpdate = [            
                "used_count" => $dataCouponApplied->used_count - 1,
                ];
                Coupon::where('id', $orderData->coupon_code_id)->update($couponUpdate);
            }            
        }

        $UserId = $request->get('user_id');
        $DeviceTypeForCouponOnly = ($request->get('devicetype')==constants('devicetype.ios')) ? 2 : $request->get('devicetype');
        

        $dataCoupon = Coupon::where('coupon_code', $applied_coupon_code)
            ->where(function($queryUserId) use ($UserId) {
                $queryUserId->where('user_id', 0);
                $queryUserId->orWhere('user_id', $UserId);
            })
            ->where(function($queryDeviceType) use ($DeviceTypeForCouponOnly) {
                $queryDeviceType->where('applied_for_platform', 0);
                $queryDeviceType->orWhere('applied_for_platform', $DeviceTypeForCouponOnly);
            })
            ->whereNotNull('coupon_code')
            ->where('is_active', constants('is_active_yes'))
            ->where('start_datetime', '<', date('Y-m-d H:i:s'))
            ->where(function($queryEnd_datetime) {
                $queryEnd_datetime->where('end_datetime', '>', date('Y-m-d H:i:s'));
                $queryEnd_datetime->orWhereNull('end_datetime');
            })
            
            ->whereColumn('maximum_use_count', '>', 'used_count')
            ->where('maximum_use_count_peruser', '>' , 0)
            ->first();


        if(empty($dataCoupon)) {
            return response()->json([
            'success' => constants('invalidResponse.success'),
            'message' => "Oops, ".$applied_coupon_code." is Not Valid Coupon.",
            'data'    => [
                'order_id' => $request->order_id,
                'applied_coupon_code' => $applied_coupon_code,
                 ],
            ], constants('invalidResponse.statusCode')); 
        }

        $orderDataCountCoupon = Order::where('user_id', $request->get('user_id'))->where('coupon_code_applied', $applied_coupon_code)->whereNotNull('coupon_code_applied')->count();

        if($orderDataCountCoupon > 0 && $dataCoupon->maximum_use_count_peruser <= $orderDataCountCoupon) {
            return response()->json([
            'success' => constants('invalidResponse.success'),
            'message' => "Sorry, You Can Not Use ".$applied_coupon_code." Coupon Code More Than ".$dataCoupon->maximum_use_count_peruser." Time.",
            'data'    => [
                'order_id' => $request->order_id,
                'applied_coupon_code' => $applied_coupon_code,
                 ],
            ], constants('invalidResponse.statusCode')); 
        }

        if($dataCoupon->min_order_value > $orderData->total_payable_amount) {
            return response()->json([
            'success' => constants('invalidResponse.success'),
            'message' => "To Use ".$applied_coupon_code." Coupon Code Your Order Payable Amount Must Be At Least ".$dataCoupon->min_order_value." Rs.",
            'data'    => [
                'order_id' => $request->order_id,
                'applied_coupon_code' => $applied_coupon_code,
                 ],
            ], constants('invalidResponse.statusCode')); 
        }


        $orderData = Order::where('id', $request->order_id)->first();

        $discountAmountCoupon = 0;
        if($dataCoupon->discount_type==constants('discount_type.P.key')){
            $discountAmountCoupon = $dataCoupon->discount_value*$orderData->total_payable_amount*0.01;
        }
        else if($dataCoupon->discount_type==constants('discount_type.F.key')){
            $discountAmountCoupon = $dataCoupon->discount_value;
        }

        if($discountAmountCoupon > $dataCoupon->maximum_discount){
            $discountAmountCoupon = $dataCoupon->maximum_discount;
        }

        $total_payable_amount = (($orderData->total_payable_amount - $discountAmountCoupon)>0) ? ($orderData->total_payable_amount - $discountAmountCoupon) : $orderData->total_payable_amount;
        $discountAmountCoupon = $orderData->total_payable_amount - $total_payable_amount;


        $orderUpdate = [
            "coupon_code_applied" => $applied_coupon_code,
            "coupon_code_id" => $dataCoupon->id,
            "coupon_benefit_amount" => $discountAmountCoupon,
            "total_payable_amount" => $total_payable_amount,
            "discount" => $orderData->discount + $discountAmountCoupon,
        ];
        Order::where('is_active', constants('is_active_yes'))->whereIn('status', constants('order_status.approved_orders'))->where('user_id', $request->get('user_id'))->where('id', $request->order_id)->where('payment_status', constants('payment_status.Pending'))->update($orderUpdate);

        
        //$orderNewData = Order::with([ 'order_status', 'order_payment_status', 'order_logs_user','orderParcel','customer',])->where('id', $request->order_id)->first();

        $orderNewData = (new OrderController())->returnOrderDetailWithPaymentStatus($request->order_id, $request->get('user_id'));


        $dataCouponApplied = Coupon::where('id', $dataCoupon->id)->first(['used_count']);
        if(!empty($dataCouponApplied)){
                $couponUpdate = [            
                "used_count" => $dataCouponApplied->used_count + 1,
                ];
                Coupon::where('id', $dataCoupon->id)->update($couponUpdate);
        }

        return response()->json([
            'success' => constants('validResponse.success'),
            'message' => "Congratulations, You have Got Rs. ".number_format($discountAmountCoupon,2)." Coupon Discount on This Order.",
            'data'    => [
                'order_detail' => $orderNewData,
                'order_id' => $request->order_id,
                ],
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

    /*------remove coupon from order----*/
   public function removeCouponCode(Request $request)
   {    
        try {
        $validator = Validator::make($request->all(), [ 
            'order_id'=> 'required|integer|min:1',
        ]);   

        if($validator->fails()) {  
            return response()->json(constants('checkRequiredField'), constants('invalidResponse.statusCode'));    
        }

        $orderData = Order::where('is_active', constants('is_active_yes'))->whereIn('status', constants('order_status.approved_orders'))->where('user_id', $request->get('user_id'))->where('id', $request->order_id)->where('payment_status', constants('payment_status.Pending'))->where('coupon_code_id', '>', 0)->where('coupon_code_applied', '!=', '')->first();

        if(empty($orderData)) {
            return response()->json([
            'success' => constants('invalidResponse.success'),
            'message' => "Oops, No Valid Order Found.",
            'data'    => [
                'order_id' => $request->order_id,
                 ],
            ], constants('invalidResponse.statusCode')); 
        }

        $dataCouponApplied = Coupon::where('id', $orderData->coupon_code_id)->first(['used_count']);

        $orderUpdate = [
            "coupon_code_applied" => NULL,
            "coupon_code_id" => NULL,
            "coupon_benefit_amount" => 0,
            "total_payable_amount" => $orderData->total_payable_amount + $orderData->coupon_benefit_amount,
            "discount" => $orderData->discount - $orderData->coupon_benefit_amount,
        ];
        Order::where('id', $request->order_id)->update($orderUpdate);


        if(!empty($dataCouponApplied)){
            $couponUpdate = [            
            "used_count" => $dataCouponApplied->used_count - 1,
            ];
            Coupon::where('id', $orderData->coupon_code_id)->update($couponUpdate);
        }
        
        //$orderNewData = Order::with([ 'order_status', 'order_payment_status', 'order_logs_user','orderParcel','customer',])->where('id', $request->order_id)->first();


        $orderNewData = (new OrderController())->returnOrderDetailWithPaymentStatus($request->order_id, $request->get('user_id'));

        return response()->json([
            'success' => constants('validResponse.success'),
            'message' => "Coupon Removed Successfully.",
            'data'    => [
                'order_detail' => $orderNewData,
                'order_id' => $request->order_id,
                ],
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
    









}
