<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'bigdaddy_lr_number',
        'transporter_lr_number',
        'user_id',
        'pickup_location',
        'drop_location',
        'pickup_latitude',
        'pickup_longitude',
        'drop_latitude',
        'drop_longitude',
        'contact_person_name',
        'contact_person_phone_number',
        'transporter_name',
        'contact_person_name_drop',
        'contact_person_phone_number_drop',
        'transporter_name_drop',
        'goods_type_id',
        'goods_weight',
        'no_of_parcel',
        'goods_height',
        'goods_width',
        'goods_length',
        'final_cost',
        'discount',
        'min_order_value_charge',
        'redeliver_charge',
        'driver_id',
        'driver_assigned_datetime',
        'pickedup_datetime',
        'cancelled_datetime',
        'if_cancelled_reason_text',
        'delivered_datetime',
        'payment_datetime',
        'undelivered_datetime',
        'if_undelivered_reason_id',
        'if_undelivered_reason_text',
        'order_created_by',
        'status',
        'lr_img',
        'pickup_img',
        'deliver_img',
        'is_active',
        'payment_type',
        'payment_status',
        'transport_cost',
        'tempo_charge',
        'service_charge',
        'total_no_of_parcel',
        'total_weight',
        'customer_estimation_asset_value',
        'other_field_pickup',
        'other_field_drop',
        'vehicle_id',
        'vehicle_no',
        'invoice_id',
        'if_cheque_number',
        'if_transaction_number',
        'payment_comment',
        'payment_discount',
        'order_driver_trip_type',
        'order_driver_trip_amount',
        'subscription_purchase_id',
        'subscription_benefit_amount',
        'coupon_code_applied',
        'coupon_code_id',
        'coupon_benefit_amount',
        'wallet_amount_used',
        'prepaid_amount_used',
        'cod_amount_used',
        'total_payable_amount',
        'payment_type_manual',
    ];

    protected $table = 'tbl_orders';


    public function customer(){
        return $this->hasOne(Customer::class, 'id', 'user_id');
    }

    public function orderParcel(){
        return $this->hasMany(OrderParcel::class, 'order_id', 'id');
    }

    public function driver(){
        return $this->hasOne(Driver::class, 'id', 'driver_id');
    }

    public function vehicle(){
        return $this->hasOne(Vehicle::class, 'id', 'vehicle_id');
    }

    public function order_status(){
        return $this->hasOne(ShortHelper::class, 'short', 'status')->where('is_active',constants('is_active_yes'))->where('type','order_status_type')->select('tbl_short_helper.short','tbl_short_helper.name','tbl_short_helper.classhtml','tbl_short_helper.details'); 
    }
    
    public function order_payment_status(){
        return $this->hasOne(ShortHelper::class, 'short', 'payment_status')->where('is_active',constants('is_active_yes'))->where('type','payment_status')->select('tbl_short_helper.short','tbl_short_helper.name','tbl_short_helper.classhtml'); 
    }

    public function order_payment_type(){
        return $this->hasOne(ShortHelper::class, 'short', 'payment_type')->where('is_active',constants('is_active_yes'))->where('type','payment_type')->select('tbl_short_helper.short','tbl_short_helper.name','tbl_short_helper.classhtml'); 
    }

    public function orderFile(){
        return $this->hasMany(OrderFile::class, 'order_id', 'id');
    }

    public function invoice(){
        return $this->hasOne(Invoice::class, 'id', 'invoice_id');
    }


    public function order_arrange(){
        return $this->hasMany(OrderArrange::class, 'order_id', 'id');
    }

    public function order_logs(){
        return $this->hasMany(OrderLog::class, 'order_id', 'id');
    }

    public function order_logs_user(){
        return $this->hasMany(OrderLog::class, 'order_id', 'id')->where('type', 1);
    }

    public function order_reviews(){
        return $this->hasOne(OrderReview::class, 'order_id', 'id');
    }

    public function razorpay_payment(){
        return $this->hasOne(RazorPayPayment::class, 'order_id', 'id');
    }

    public function order_driver_reports(){
        return $this->hasMany(DriverOrderReport::class, 'order_id', 'id');
    }





}
