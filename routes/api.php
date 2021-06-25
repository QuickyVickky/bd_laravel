<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Client\Api\customer\MainApiController;
use App\Http\Controllers\Client\Api\customer\LoginController;
use App\Http\Controllers\Client\Api\customer\OrderController;
use App\Http\Controllers\Client\Api\customer\PageController;
use App\Http\Controllers\Client\Api\customer\ProfileController;
use App\Http\Controllers\Client\Api\customer\RazorPayController;
use App\Http\Controllers\Client\Api\customer\WalletController;
use App\Http\Controllers\Client\Api\customer\SubscriptionController;
use App\Http\Controllers\Client\Api\customer\CouponController;




use App\Http\Controllers\Client\Api\driver\LoginController as DriverLoginController; 
use App\Http\Controllers\Client\Api\driver\ProfileController as DriverProfileController;
use App\Http\Controllers\Client\Api\driver\ManageController; 







/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


/*----XSS started------*/
Route::group(['middleware' => ['XSS']], function (){



/*------customer apis---starts--here--------------*/


Route::group(['prefix' => 'customer'], function () {

	Route::group(['middleware' => ['CheckAccessToken']], function (){
	/*------------------------------customer-----with login------starts--------------------------*/
	Route::post('get-orders-list', [OrderController::class, 'getOrderListWithFilter']);
	Route::post('get-orders-list-track', [OrderController::class, 'getOrderListForTrackOrder']);
	Route::post('get-orders-list-feedback', [OrderController::class, 'getDeliveredOrderListForFeedBack']);

	

	
	Route::post('get-order-detail', [OrderController::class, 'getOrderDetail']);
	Route::post('get-last-order-detail', [OrderController::class, 'getLastOrderDetail']);

	
	Route::post('upload-lrfile-create-order', [OrderController::class, 'uploadLrFileToCreateOrder']);
	Route::post('review-order-submit', [OrderController::class, 'reviewOrder']);
	
	Route::post('create-new-order-request', [OrderController::class, 'createNewOrderRequest']);
	Route::post('update-order-request', [OrderController::class, 'updateOrderDetails']);

	



	/*---------customer ---profile------------------*/
	Route::post('get-profile-info', [ProfileController::class, 'profileInfo']);
	Route::post('get-addresses-list', [ProfileController::class, 'addressList']);
	Route::post('get-address-edit', [ProfileController::class, 'getAddressEdit']);
	Route::post('update-address-edit', [ProfileController::class, 'updateAddressEdit']);
	Route::post('add-address-new', [ProfileController::class, 'addAddressNew']);
	Route::post('delete-this-address', [ProfileController::class, 'deleteThisAddress']);
	Route::post('set-address-default', [ProfileController::class, 'setAddressDefault']);
	Route::post('pincode-api', [ProfileController::class, 'pincodeVerifyApi']);
	Route::post('get-notification-list', [ProfileController::class, 'getNotificationList']);


	Route::post('test-notification-web', [ProfileController::class, 'testWebNotification']);




	Route::post('apply-coupon-code', [CouponController::class, 'applyCouponCode']);
	Route::post('remove-coupon-code', [CouponController::class, 'removeCouponCode']);


	
	Route::post('get-order-paymentstatus', [OrderController::class, 'getOrderDetailWithPaymentStatus']);
	Route::post('precheck-cod', [OrderController::class, 'preCheckCODValidation']);
	Route::post('place-order-cod', [OrderController::class, 'placeOrderWithCOD']);


	Route::post('precheck-order-cancel', [OrderController::class, 'preCheckOrderCancelValidation']);
	Route::post('cancel-this-order', [OrderController::class, 'cancelThisOrderBeforePlaceOrder']);
	
	Route::post('get-reasonfor-list', [OrderController::class, 'getReasonForList']);
	Route::post('get-lruploded-list', [OrderController::class, 'getLR_onlyList']);

	


	

	Route::post('get-download-order-invoicefile', [OrderController::class, 'getDownloadOrderInvoiceFile']);

	











	Route::post('pay-razorpay-payment-order', [RazorPayController::class, 'capturePaymentOrderRazorPay']);
	Route::post('precheck-prepaid-placeorder', [RazorPayController::class, 'preCheckPrepaidPlaceOrderValidation']);


	
	Route::post('get-wallet-transaction-list-withfilter', [WalletController::class, 'getWalletTransactionListWithFilter']);
	Route::post('download-wallet-transaction-list-excel', [WalletController::class, 'downloadExcelWalletTransactionListWithFilter']);
	Route::post('precheck-wallet', [WalletController::class, 'preCheckWalletValidation']);
	Route::post('get-wallet-creditlist', [WalletController::class, 'getWalletCreditList']);
	Route::post('add-razorpay-payment-wallet', [WalletController::class, 'capturePaymentAddWalletRazorPay']);






	Route::post('precheck-wallet-placeorder', [WalletController::class, 'preCheckWalletPlaceOrderValidation']);
	Route::post('place-order-wallet', [WalletController::class, 'placeOrderWithWallet']);





	







	Route::post('spin-lucky-wheel-subscription', [SubscriptionController::class, 'getLuckySpinWheelDiceSubscriptionDiscount']);
	Route::post('get-subscription-list', [SubscriptionController::class, 'getSubscriptionList']);
	Route::post('precheck-subscription-purchase', [SubscriptionController::class, 'preCheckSubscriptionPurchaseValidation']);
	Route::post('pay-razorpay-payment-subscription', [SubscriptionController::class, 'capturePaymentAddSubscriptionPurchaseRazorPay']);


	

	


	
	/*---------customer ---profile------------------*/
	/*----log--out-----*/
	Route::post('log-out', [MainApiController::class, 'logOut']);
	/*----log--out-----*/
   /*---------------------------------customer------with login---------ends-----------------------*/
	});





	Route::post('send-otp-for-login-mobile', [LoginController::class, 'sendOTPforLoginMobile']);
	Route::post('check-otp-for-login-mobile', [LoginController::class, 'checkOTPforLoginMobile']);
	Route::post('login-with-email', [LoginController::class, 'loginWithEmail']);
	Route::post('send-otp-for-signup-mobile', [LoginController::class, 'sendOTPforSignUpMobile']);
	Route::post('check-otp-for-signup-mobile', [LoginController::class, 'checkOTPforSignUpMobile']);
	Route::post('signup-with-mobile', [LoginController::class, 'signUpWithMobile']);
	Route::post('send-otp-for-forgot-password-email', [LoginController::class, 'sendOTPforForgotPasswordEmail']);
	Route::post('check-otp-for-forgot-password-email', [LoginController::class, 'checkOTPforForgotPasswordEmail']);
	Route::post('contact-us-submit', [PageController::class, 'contactUsSubmit']);
	Route::get('get-company-info', [PageController::class, 'getCompanyConfiguration']);
	/*------GET------------*/
	Route::post('get-goods-type', [OrderController::class, 'getGoodsType']);
	Route::get('get-address-type', [ProfileController::class, 'getAddressType']);





	

	/*------GET------------*/



});
/*------customer apis---ends--here---------*/
/*------driver apis---starts--here---------*/
Route::group(['prefix' => 'driver'], function () {
	Route::group(['middleware' => ['CheckAccessTokenDriver']], function (){

		Route::post('get-active-order-list', [ManageController::class, 'activeOrderListDriver']);



		Route::post('update-current-location', [DriverProfileController::class, 'updateCurrentLocation']);
		Route::post('get-profile-info', [DriverProfileController::class, 'profileInfo']);
		Route::post('get-notification-list', [DriverProfileController::class, 'getNotificationList']);
		Route::post('get-assigned-order-list', [ManageController::class, 'assinedOrderList']);
		Route::post('get-pickup-order-list', [ManageController::class, 'pickedUpOrderList']);
		Route::post('get-driver-order-history-list', [ManageController::class, 'driverOrderHistoryList']);

		
		Route::post('get-order-detail', [ManageController::class, 'getOrderDetail']);
		Route::post('pickup-verify-order', [ManageController::class, 'pickupVerify']);
		Route::post('deliver-this-order', [ManageController::class, 'deliverThisOrder']);
		Route::post('undeliver-this-order', [ManageController::class, 'undeliverThisOrder']);
		Route::post('get-reasonfor-list', [ManageController::class, 'getReasonForList']);
		Route::post('send-order-mismatch-order', [ManageController::class, 'sendOrderMismatchDataThisOrder']);
		


		Route::post('send-emergency-notification-toadmin', [DriverProfileController::class, 'sendEmergencyNotification']);
		Route::get('get-company-profile-info', [DriverProfileController::class, 'companyProfileInfo']);
		Route::post('get-dashboard-amount-data', [DriverProfileController::class, 'dashboardAmount']);

		Route::post('update-driver-status', [DriverProfileController::class, 'updateDriverStatus']);




		Route::post('testGoogleMaps', [ManageController::class, 'testGoogleMaps']);


		


		
	});


	Route::post('send-otp-for-login-mobile', [DriverLoginController::class, 'sendOTPforLoginMobile']);
	Route::post('check-otp-for-login-mobile', [DriverLoginController::class, 'checkOTPforLoginMobile']);
	Route::post('check-driver-app-request-initial', [DriverLoginController::class, 'checkDriveAppRequest']);


	Route::post('getApiLogs', [ManageController::class, 'getApiLogs']);

});
/*------driver apis---ends--here---------*/







});  /*----XSS closed------*/






