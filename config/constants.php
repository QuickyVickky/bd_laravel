<?php

return [

/*---------custom constant start here------------*/
    //'googleAPIKeyold' => 'AIzaSyCjiAJRUJWCNFO0n16l1KwxO-jWBI-wTR8',
    'googleAPIKey' => 'AIzaSyAIRHIQxj6FX2sRhhKyItqeW_5DDbYR6B8',
    'googleAPIKey0' => 'AIzaSyAIRHIQxj6FX2sRhhKyItqeW_5DDbYR6B8',
    //'googleAPIKey0old' => 'AIzaSyDdvVxVhOR77FDUyo2W4mkIG8jJ9YtifGg',

    'googleMapApiKey' => 'AIzaSyAyBlgPyx_ZHQOt1OtCrEXKMw5bDb-yPUs',


    'FCM_SERVER_KEY' => 'AAAAhG4bYLc:APA91bGeVlwIOCuj7q5PppY75vmlFdxrldpCiY12CPGeeNA1BIgoxdFEVxjxp23nFU1FQV3Lce8MKwabuYsh_DkzI21yY8vLrEOg5Kq1s9mEs6DfyxHw4Em5msc2GTgpOlseGGGNYKAE',



    'orderEditableStatus' => ['P','PU','A','OA','RO','PP'],

    'title'     => 'BigDaddy',

    'guest_user_id'    => 0,
    'company_configurations_id' => 1,

    'driver_account_main_category_id' => 38,

    'driver_account_sub_category_id_paypertrip' => 39,
    'driver_account_sub_category_id_payperparcel' => 40,

    'goods_type_id_other'    => 8,
    'otherordercancelledreasonid'    => 4,
    'limit_in_dropdown'    => 10,
    'limit_in_dropdown25'    => 25,
    'limit_in_dropdown_large'    => 50,

    


    'is_active_yes'    => 0,
    'is_active_no'     => 1,

    'is_active' => [0,1],

    'is_editable_yes'    => 1,
    'is_editable_no'     => 0,

    'max_otp_limit_count_reached'     => 100,
    'otp_time_out'     => 600, /*seconds*/


    'order_file_type'                  => [ /*-- order_file_type --*/
        'lrpickup'    =>  "LRP" , 
        'lrdrop' =>  "LRD", 
        'goodspickup'    => "GP", 
        'goodsdrop'    => "GD", 
        'signaturepickup'    => "SGP", 
        'signaturedrop'    => "SGD", 
    ],


    'emptyData'                     => new \stdClass(),

    'validResponse'                 => [
        'success'    => true,
        'statusCode' => 200,
        'message'    => 'success',
    ],

    'invalidResponse'               => [
        'success'    => false,
        'statusCode' => 400,
        'message'    => 'Bad Request',
    ],

    'invalidToken'                  => [
        'success'    => false,
        'statusCode' => 401,
        'message'    => 'Unauthorized',
    ],

    'is_enable_api_log'             => true,

    'per_page'                		=> 10,
    'per_page_large'                => 50,


    'devicetype'                   => [
        'web'    => 1,
        'android' => 2,
        'ios' => 3,
    ],


    'for_what'                  => [ /*-- OTP for --*/
        'login'    =>  'L',
        'register' =>  'R',
        'forgot'    => 'F',
    ],


    

    'arrangement_type'   => [/*-- Order arrangement_type --*/
        'undeliver' => 0,
        'pickup'    => 1,
        'deliver' => 2,
    ],

    'arrangement_typeName'   => [/*-- Order arrangement_type Name --*/
        '1' => [ "name" => "PickUp", "classhtml" => "success",],
        '2' => [ "name" => "Deliver", "classhtml" => "info",],
        '0' => [ "name" => "Undeliver", "classhtml" => "warning",],
    ],


    'usertype'                   => [
        'customer'    => 1,
        'driver' => 2,
    ],

    'user_paymentbill_type'  => [
        'ToPay'    => 0,
        'ToBeBilled' => 1,
    ],

    'bad_request'                   => [
        'success' => false,
        'message' => "Bad Request",
        'data'    => new \stdClass(),
    ],

    'somethingWentWrong'  => [
        'success' => false,
        'message' => "Something Went Wrong. Please Try Again.",
        'data'    => new \stdClass(),
    ],


    'checkRequiredField'                   => [
        'success' => false,
        'message' => "Please Check All Required Fields.",
        'data'    => new \stdClass(),
    ],

    'customer_type'                  => [ /*-- customer_type  --*/
        'Transporter'    =>  'Transporter', /*27  tbl_short_helper table id*/ 
        'Business' =>  'Business', /*28 tbl_short_helper table id*/
        'Individual'    => 'Individual', /*29 tbl_short_helper table id*/
    ],

    'customer_gst_exempted_type'   => [
        'yes'    => 1, /*---panNo---*/
        'no' => 0, /*---gstNo---*/
    ],

    'order_status_type' => ['OA','RO','P','PU','A','D','C','CU','U','PP' ],

    'order_status'                  => [ /*-- order_status_type  --*/
        'all_orders'    =>  ['OA','RO','P','PU','A','D','C','CU','U','PP'] , 
        'active_orders' =>  ['OA','RO','P','PU','A','U','PP'], 
        'new_orders'    => ['P','PU'],  
        'delivered_orders'    => ['D'], 
        'cancelled_orders'    => ['C','CU'],
        'assigned_orders' =>  ['A'], 
        'undelivered_orders'    => ['U'], 
        'requested_orders'    => ['RO'],  
        'approved_orders'    => ['OA'], 
        'pickedup_orders'    => ['PP'],
        'temp_orders'    => ['OA','RO'],
        'transit_orders'    => ['A','PP','U'],  
        'active_transit_orders'    => ['A','PP'], 
        'active_orders_confirmed' =>  ['P','PU','A','U','PP'], 
    ],

    'order_status_foradmin'                  => [ /*-- order_status_foradmin  --*/
        'all_orders'    => ["name" => "All Orders", "value" => ['OA','RO','P','PU','A','D','C','CU','U','PP']  ] , 
        'new_orders'    => ["name" => "New Orders", "value" => ['P','PU']  ] , 
        'assigned_orders'    => ["name" => "Assigned Orders", "value" => ['A']  ] , 
        'pickedup_orders'    => ["name" => "PickUp Orders", "value" => ['PP'] ] , 
        'delivered_orders'    => ["name" => "Delivered Orders", "value" => ['D'] ] ,
        'requested_orders'    => ["name" => "Requested Orders", "value" => ['RO'] ] ,
        'approved_orders'    => ["name" => "Approved Orders", "value" => ['OA'] ] ,  
        'undelivered_orders'    => ["name" => "Undelivered Orders", "value" => ['U'] ] , 
        'cancelled_orders'    => ["name" => "Cancelled Orders", "value" => ['C','CU'] ] ,   
    ],

    'min_order_value'     => 200, /*---in Rs----*/
    'max_user_address_limit'     => 10, /*---address_limit count maximum---*/
    'basic_limit'     => 10,
    'extra_large_limit'     => 500,


    'driver_status'                  => [ /*-- driver_status  --*/
        'Available'    =>  'A', 
        'Breakdown' =>  'B', 
        'OffWork'    => 'O', 
    ],

    'reasonfor'                  => [ /*-- reasonfor  --*/
        'to_undeliver'    =>  "undelivered_reason" , 
    
    ],

    'language'                  => [ /*-- language  --*/
        'en'    =>  [ "name" => "English" ], 
        'hi'    =>  [ "name" => "Hindi" ], 
        'gj'    =>  [ "name" => "Gujarati" ], 
    ],


    'payment_type'              => [ /*-- payment type for order order_method  --*/
        'COD'    =>  "C", 
        'Prepaid' =>  "P", 
        'Wallet'  => "W",
    ],

    'payment_type_userside'                  => [ /*-- payment_type_userside --*/
        'C'   =>  [ "name" => "Cash On Delivery", "key" => "COD", "short" => "C", "id" => 19], 
        'P'   =>  [ "name" => "Online Payment", "key" => "Prepaid","short" => "P", "id" => 20], 
        'W'   =>  [ "name" => "Wallet Payment", "key" => "Wallet", "short" => "W", "id" => 39], 
    ],


    'payment_type_manual'                  => [ /*-- payment_ method --*/
        'CHQ'    =>  [ "name" => "Cheque", "short" => "CHQ", "id" => 43], 
        'RTGS'    =>  [ "name" => "RTGS Payment", "short" => "RTGS", "id" => 45], 
        'CS'    =>  [ "name" => "Cash Payment", "short" => "CS", "id" => 44], 
        'NEFT'    =>  [ "name" => "NEFT Payment", "short" => "NEFT", "id" => 46], 
        'CARD'    =>  [ "name" => "CARD Payment", "short" => "CARD", "id" => 47], 
        'P'   =>  [ "name" => "Online Payment", "short" => "P", "id" => 56], 
        'W'   =>  [ "name" => "Wallet Payment", "short" => "W", "id" => 55], 
    ],



    'dir_name'                  => [ /*-- dir_name  --*/
        'order'    =>  "order_files" , 
        'driver' =>  "driver_files", 
        'customer'    => "customer_files", 
        'vehicle'    => "vehicle_files", 
        'temp'    => "temp_files", 
        'invoice'  => "invoice_files",
        'bill'  => "bill_files",
        'app'    => "app_files", 
    ],

    

    'admins_type'                  => [ /*-- admins_type --*/
        'Manager'    =>  "M" , 
        'Staff' =>  "S", 
        'SuperAdmin'    => "A", 
    ],

    'address_type'                  => [ /*-- customer address_type --*/
        'Home'    =>  "H" , 
        'Work' =>  "W", 
        'Other'    => "O", 
    ],

    'is_default'                  => [ /*-- customer address is_default--*/
        'yes' => 1, 
        'no' => 0, 
    ],

    'image_extension'     => ['jpg','jpeg','png','bmp','gif'],

    'login_device'                  => [
        'this_device'    =>  1,
        'all_device' =>  0,
    ], 


    'transaction_type'   => [
        'Debit'    => 'Dr',
        'Credit' => 'Cr',
    ],


    'transaction_type_list'   => [
        'Dr'    => [ "name" => "Debit" , "name2" => "Expense", "name3" => "Withdraw", "classhtml" => "danger", "key" => "Dr" ],
        'Cr' => [ "name" => "Credit" , "name2" => "Income", "name3" => "Deposit", "classhtml" => "success", "key" => "Cr" ],
    ],

    


    'payment_status'   => [
        'Paid'    => 1,
        'Pending' => 0,
    ],

    'payment_statusName'   => [/*--order payment status Name --*/
        '1' => [ "name" => "Paid", "classhtml" => "info",],
        '0' => [ "name" => "Pending", "classhtml" => "danger",],
    ],

    'notification_type'   => [
        'success' => 'success',
        'danger' => 'danger',
        'warning' => 'warning',
        'info' => 'info',
    ],

    'user_notification_type'    => [ /*-- user_notification_type  --*/
        'on_approve_order'    =>  [ "name" => "Approved", ] , 
        'on_assign_order'    =>  [ "name" => "Assigned", ] , 
        'on_pickup_order'    =>  [ "name" => "PickedUp", ] , 
        'on_deliver_order'    =>  [ "name" => "Delivered", ] , 
        'on_undeliver_order'    =>  [ "name" => "Undelivered", ] , 
    ],




    'driver_assign_type'                  => [ /*-- driver_assign_type --*/
        'paypertrip'  => "PPT" , 
        'payperparcel' =>  "PPP", 
        'payroll'    => "PRL", 
    ],


    'driver_assign_type_value'                  => [ /*-- driver_assign_type --*/
        'PPT'  => ["key" => "paypertrip" , "name" => "Pay Per Trip",  ] , 
        'PPP' =>  ["key" => "payperparcel" , "name" => "Pay Per Parcel",] , 
        'PRL' => ["key" => "payroll" , "name" => "PayRoll",] , 
    ],



    'jwt_key' => 'tgdfg76dhfghlhgVRTPffghfghfghbfjXjpC5renq3yJywpZ8rSZXBm9LCKnME4e8cUMV4K7SRCfSegLCF9aTvgEfghj54665vgh8g', /*-- never change it----*/


/*--------------accounts new -----------*/
    'payment_method_for_accounting'                  => [ /*-- payment_type  --*/
        'CHQ'  =>  [ "name" => "Cheque", "short" => "CHQ", "id" => 43], 
        'RTGS'    =>  [ "name" => "RTGS", "short" => "RTGS", "id" => 45], 
        'CS'    =>  [ "name" => "Cash", "short" => "CS", "id" => 44], 
        'NEFT'    =>  [ "name" => "NEFT", "short" => "NEFT", "id" => 46], 
        'CARD'    =>  [ "name" => "CARD", "short" => "CARD", "id" => 47],
        'P'   =>  [ "name" => "Online", "short" => "P", "id" => 56], 
        'W'   =>  [ "name" => "Wallet", "short" => "W", "id" => 55], 

    ],


     
/*--------------accounts new -----------*/

    'Expense_Bill_Payment_Subcategory_Id' => 23,
    'Income_Invoice_Payment_Subcategory_Id' => 22,
    'Transfer_From_Bank_Subcategory_Id' => 29,
    'Transfer_To_Bank_Subcategory_Id' => 30,

    'vendor_type'   => [
        'Vendor' => [ "name" => "Vendor" , "name2" => "Vendor", "key" => "Vendor" ],
        'Driver'    => [ "name" => "Driver" , "name2" => "Driver", "key" => "Driver" ],
        'Staff' => [ "name" => "Staff" , "name2" => "Staff", "key" => "Staff" ],
    ],

    'driver_transaction_sub_category_id_paypertrip' => 47,
    'driver_transaction_sub_category_id_payperparcel' => 46,
    'subscriptionTransactionSubCategoryId' => 50,
    'cashonhands_bankid' => 1,



    'OneBDWalletCreditIs' => 1, /*---one rupee INR---*/
    'DefaultRazorPayAccountBankId' => 15,


    'paymentTypeOnline'  => [ /*-- payment_type_online --*/
        'Wallet' =>  2, 
        'Order' =>  1, 
        'Subscription' => 3, 
    ],
 
/*---------custom constant start here------------*/



/*--------------offers-----------*/

    'confirmation'                  => [ /*--confirmation--*/
        'yes' => 1, 
        'no' => 0, 
    ],



'discount_type'   => [
     "F" => [ "name" => "Flat Discount" , "name2" => "Flat", "key" => "F", "sign" => "&#x20B9;"],
     "P" => [ "name" => "Percent Discount" , "name2" => "Percent", "key" => "P", "sign" => "%" ],
],




/*--------------offers-----------*/

];
