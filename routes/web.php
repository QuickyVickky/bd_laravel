<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\AdminManage;
use App\Http\Controllers\Admin\Dashboard;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DriverController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\AccountCategoryController;
use App\Http\Controllers\Admin\GoodsTypeController;
use App\Http\Controllers\Admin\PdfController;
use App\Http\Controllers\Admin\InquiryController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\SalesExecutiveController;
use App\Http\Controllers\Admin\FeedbackController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\VendorController;
use App\Http\Controllers\Admin\AccAccountController;
use App\Http\Controllers\Admin\AccCategoryController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\SubscriptionController;
use App\Http\Controllers\Admin\WalletController;








use App\Http\Controllers\Client\HomeController;







/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });


Route::group(array('middleware' => ['checkLogin']), function () {
	Route::prefix(env('ADMINBASE_NAME'))->group(function () {

		/*--------transporter----------*/
		Route::get('customer-add', [CustomerController::class, 'add_index'])->name('customer-add');
		Route::get('customer-list', [CustomerController::class, 'index'])->name('customer-list');
		Route::post('submit-customer-add', [CustomerController::class, 'Add'])->name('submit-customer-add');
		Route::post('submit-customer-update', [CustomerController::class, 'Update'])->name('submit-customer-update');
		Route::get('get-customer-list', [CustomerController::class, 'getdata'])->name('get-customer-list');
		Route::post('gst-customer-data-fill', [CustomerController::class, 'fill_customer_data_api'])->name('gst-customer-data-fill');
		Route::post('gst-pincode-data-fill', [CustomerController::class, 'fill_pincode_api'])->name('gst-pincode-data-fill');
		Route::get('view-customer/{any?}', [CustomerController::class, 'view_edit_index'])->name('view-customer');
		Route::get('get-order-list-customerwise', [OrderController::class, 'getdata_customerwise'])->name('get-order-list-customerwise');
		Route::get('get-address-list-customerwise', [CustomerController::class, 'getdata_address'])->name('get-address-list-customerwise');
		Route::get('edit-useraddress-data', [CustomerController::class, 'geteditaddress'])->name('edit-useraddress-data');
		Route::post('add-update-useraddress', [CustomerController::class, 'Add_OR_Updateaddress'])->name('add-update-useraddress');
		Route::post('change-customer-status', [CustomerController::class, 'change_status_confirm'])->name('change-customer-status');
		Route::get('get-customer-logs', [CustomerController::class, 'getdataCustomerLogs'])->name('get-customer-logs');
		Route::get('se-customer-list', [CustomerController::class, 'salesexecutive_index'])->name('se-customer-list');
		Route::get('se-get-customer-list', [CustomerController::class, 'getdata_salesexecutive'])->name('se-get-customer-list');
		Route::get('get-transaction-list-customerwise', [CustomerController::class, 'getdataTransactionWise'])->name('get-transaction-list-customerwise');


		Route::post('getCustomerDataOne', [CustomerController::class, 'getedit'])->name('getCustomerDataOne');
		Route::post('getCustomerLastSubscriptionPurchaseValid', [CustomerController::class, 'getCustomerLastSubscriptionPurchaseValid'])->name('getCustomerLastSubscriptionPurchaseValid');

		



		Route::get('sales-executive-list', [SalesExecutiveController::class, 'index'])->name('sales-executive-list');
		Route::get('get-sales-executive-list', [SalesExecutiveController::class, 'getdata'])->name('get-sales-executive-list');
		Route::post('add-update-salesexecutive', [SalesExecutiveController::class, 'Add_OR_Update'])->name('add-update-salesexecutive');
		Route::get('salesexecutive-getedit', [SalesExecutiveController::class, 'getedit'])->name('salesexecutive-getedit');




		Route::get('pan', [CustomerController::class, 'pan'])->name('pan');





		/*------------------*/
		/*--------driver----------*/
		Route::get('driver-add', [DriverController::class, 'add_index'])->name('driver-add');
		Route::get('driver-list', [DriverController::class, 'index'])->name('driver-list');
		Route::get('get-driver-list', [DriverController::class, 'getdata'])->name('get-driver-list');
		Route::post('submit-driver-add', [DriverController::class, 'Add'])->name('submit-driver-add');
		Route::post('submit-driver-update', [DriverController::class, 'Update'])->name('submit-driver-update');
		Route::get('view-driver/{any?}', [DriverController::class, 'view_edit_index'])->name('view-driver');
		Route::get('get-driver-files', [DriverController::class, 'getdata_files'])->name('get-driver-files');
		Route::post('update-driver-files', [DriverController::class, 'update_files'])->name('update-driver-files');
		Route::post('delete-driver-files', [DriverController::class, 'delete_files'])->name('delete-driver-files');
		Route::post('change-driver-status', [DriverController::class, 'change_status_confirm'])->name('change-driver-status');
		Route::get('get-driver-logs', [DriverController::class, 'getdataDriverLogs'])->name('get-driver-logs');

		Route::get('vehicle-list', [DriverController::class, 'vehicle_index'])->name('vehicle-list');
		Route::get('get-vehicle-list', [DriverController::class, 'getdata_vehicle'])->name('get-vehicle-list');
		Route::get('add-vehicle', [DriverController::class, 'vehicle_add_index'])->name('add-vehicle');
		Route::post('add-update-vehicle', [DriverController::class, 'Add_OR_Update_Vehicle'])->name('add-update-vehicle');
		Route::get('edit-vehicle', [DriverController::class, 'vehicle_add_index'])->name('edit-vehicle');
		Route::post('get_driver_with_vehicle_and_select', [DriverController::class, 'getDriverWithVehicleAndSelect'])->name('get_driver_with_vehicle_and_select');
		Route::post('assign-vehicle-to-driver', [DriverController::class, 'assign_vehicle_to_driver'])->name('assign-vehicle-to-driver');
		Route::post('remove-assigned-driver-from-vehicle', [DriverController::class, 'removeAssignedDriverFromVehicle'])->name('remove-assigned-driver-from-vehicle');
		Route::get('get-order-list-assigned-driverwise', [DriverController::class, 'getdataAssignedPickedupOrdersByDriverWise'])->name('get-order-list-assigned-driverwise');
		Route::get('get-order-list-delivered-driverwise', [DriverController::class, 'getdataDeliveredOrdersDriverWise'])->name('get-order-list-delivered-driverwise');
		Route::post('check-submit-order-arrangement', [DriverController::class, 'checkSubmitOrderArrangement'])->name('check-submit-order-arrangement');
		Route::post('exportExcelDriverPayrollOrders', [DriverController::class, 'exportExcelDriverPayrollOrders'])->name('exportExcelDriverPayrollOrders');
		Route::get('get-payroll-orders-list-driverwise', [DriverController::class, 'getdata_payroll_orders'])->name('get-payroll-orders-list-driverwise');

		Route::get('get-orderlist-timingreports-driverwise', [DriverController::class, 'getdataTimingreportsOrders'])->name('get-orderlist-timingreports-driverwise');
		Route::post('export-excel-driver-timingreportsorders', [DriverController::class, 'exportExcelDriverTimingReportsOrders'])->name('export-excel-driver-timingreportsorders');

		

		


		



		



		/*------------------*/

		/*--------order----------*/
		
		Route::post('delete-order-files', [OrderController::class, 'deleteOrderFiles'])->name('delete-order-files');

		Route::get('order-add', [OrderController::class, 'add_index'])->name('order-add');
		Route::post('create-new-order', [OrderController::class, 'CreateNewOrder'])->name('create-new-order');
		Route::post('update-existing-order', [OrderController::class, 'UpdateOrder'])->name('update-existing-order');
		Route::get('order-list', [OrderController::class, 'index'])->name('order-list');
		Route::get('delivered-orders', [OrderController::class, 'delivered_index'])->name('delivered-orders');
		Route::get('undelivered-orders', [OrderController::class, 'undelivered_index'])->name('undelivered-orders');
		Route::get('cancelled-orders', [OrderController::class, 'cancelled_index'])->name('cancelled-orders');
		Route::get('tobeassigned-orders', [OrderController::class, 'tobeassigned_index'])->name('tobeassigned-orders');
		Route::get('assigned-orders', [OrderController::class, 'assigned_index'])->name('assigned-orders');
		Route::get('lr-upload-list', [OrderController::class, 'lrupload_index'])->name('lr-upload-list');
		Route::get('tobeapproved-orders', [OrderController::class, 'tobeapproved_index'])->name('tobeapproved-orders');
		Route::get('orderlist-tracking', [OrderController::class, 'orderTrackingIndex'])->name('orderlist-tracking');

		




		Route::get('get-order-list', [OrderController::class, 'getdata'])->name('get-order-list');
		Route::get('get-order-list-tobeassigned', [OrderController::class, 'getdata_tobeassigned'])->name('get-order-list-tobeassigned');
		Route::get('get-order-list-tobeapproved', [OrderController::class, 'getdata_tobeapproved'])->name('get-order-list-tobeapproved');
		Route::get('get-order-list-requested_orders', [OrderController::class, 'getdata_requested_orders'])->name('get-order-list-requested_orders');
		Route::get('get-order-list-approved_orders', [OrderController::class, 'getdata_approved_orders'])->name('get-order-list-approved_orders');
		Route::post('change-lruploads-completed', [OrderController::class, 'change_status_lruploads_completed'])->name('change-lruploads-completed');
		Route::get('get-order-list-undelivered', [OrderController::class, 'getdata_undelivered'])->name('get-order-list-undelivered');
		Route::get('get-order-list-assigned', [OrderController::class, 'getdata_assigned'])->name('get-order-list-assigned');
		Route::get('get-order-list-delivered', [OrderController::class, 'getdata_delivered'])->name('get-order-list-delivered');
		Route::get('get-order-list-cancelled', [OrderController::class, 'getdata_cancelled'])->name('get-order-list-cancelled');
		Route::get('get-lr-upload-list', [OrderController::class, 'getdata_lrupload'])->name('get-lr-upload-list');
		Route::post('search-transporter-and-select', [OrderController::class, 'search_transporter_and_select'])->name('search-transporter-and-select');
		Route::post('fill-existing-data-from-transporter-details', [OrderController::class, 'fill_existing_data_from_transporter_details'])->name('fill-existing-data-from-transporter-details');
		Route::post('assign-order-to-driver', [OrderController::class, 'assign_order_to_driver'])->name('assign-order-to-driver');
		Route::post('search_driver_and_select', [OrderController::class, 'search_driver_and_select'])->name('search_driver_and_select');
		Route::post('change-order-status-delivered', [OrderController::class, 'change_order_status_delivered'])->name('change-order-status-delivered');
		Route::post('mark-as-payment-paid', [OrderController::class, 'markAsPaymentPaid'])->name('mark-as-payment-paid');
		Route::post('mark-as-payment-paid-invoice', [OrderController::class, 'markAsPaymentPaidInvoice'])->name('mark-as-payment-paid-invoice');
		Route::post('change-order-status-toneworder', [OrderController::class, 'changeOrderStatusToNewOrder'])->name('change-order-status-toneworder');
		Route::post('delete-this-order', [OrderController::class, 'deleteThisOrder'])->name('delete-this-order');
		Route::get('get-orderlist-tracking', [OrderController::class, 'getDataOrderTracking'])->name('get-orderlist-tracking');

		

		

		Route::post('change-order-status-cancelled', [OrderController::class, 'change_order_status_cancelled'])->name('change-order-status-cancelled');
		Route::post('fill-last-order-data-by-session', [OrderController::class, 'fill_last__order_data_by_session'])->name('fill-last-order-data-by-session');

		Route::get('get-order-with-lrnumber-only-tobeassigned', [OrderController::class, 'get_order_with_lrnumber_only_tobeassigned'])->name('get-order-with-lrnumber-only-tobeassigned');

		Route::get('get-order-logs', [OrderController::class, 'getdataOrderLogs'])->name('get-order-logs');
		Route::get('view-order/{any?}', [OrderController::class, 'view_order'])->name('view-order');
		Route::get('edit-order/{any?}', [OrderController::class, 'edit_order'])->name('edit-order');

		Route::get('get-addresses-byid-customer', [OrderController::class, 'getdata_address_bycustomerid'])->name('get-addresses-byid-customer');

		Route::get('order-invoice', [PdfController::class, 'index'])->name('order-invoice');
		Route::post('download-order-invoice', [PdfController::class, 'download_invoice'])->name('download-order-invoice');
		Route::post('delete-order-invoice', [PdfController::class, 'delete_invoice'])->name('delete-order-invoice');
		Route::get('getlatestinvoicenumber', [PdfController::class, 'getLatestInvoiceNumber'])->name('getlatestinvoicenumber');


		Route::get('invoice-list', [InvoiceController::class, 'index'])->name('invoice-list');
		Route::get('get-invoice-list', [InvoiceController::class, 'getdata'])->name('get-invoice-list');
		Route::get('get-invoice-list-customerwise', [CustomerController::class, 'getdata_invoice_customerwise'])->name('get-invoice-list-customerwise');

		Route::post('get-orderhtml-by-invoice', [InvoiceController::class, 'getOrderHtmlByInvoiceId'])->name('get-orderhtml-by-invoice');

		


		



		/*--------order----------*/

		/*--------account--management--------*/
		Route::get('account-add', [AccountController::class, 'add_index'])->name('account-add');
		Route::post('submit-account-add', [AccountController::class, 'Add'])->name('submit-account-add');
		Route::post('submit-account-update', [AccountController::class, 'Update'])->name('submit-account-update');
		Route::get('account-list', [AccountController::class, 'index'])->name('account-list');
		Route::get('get-account-list', [AccountController::class, 'getdata'])->name('get-account-list');
		Route::get('view-account', [AccountController::class, 'view_addedit_index'])->name('view-account');
		Route::post('delete-account-expenses', [AccountController::class, 'delete_expenses'])->name('delete-account-expenses');


		Route::get('account-category', [AccountCategoryController::class, 'index'])->name('account-category');
		Route::post('add-update-maincategory', [AccountCategoryController::class, 'addorupdatemaincategory'])->name('add-update-maincategory');
		Route::post('add-update-subcategory', [AccountCategoryController::class, 'addorupdatesubcategory'])->name('add-update-subcategory');
		Route::get('get-accountmaincategory-list', [AccountCategoryController::class, 'getdata'])->name('get-accountmaincategory-list');
		Route::get('get-accountsubcategory-list', [AccountCategoryController::class, 'getdatasubcategory'])->name('get-accountsubcategory-list');
		Route::get('edit-accountmaincategory-data', [AccountCategoryController::class, 'getedit'])->name('edit-accountmaincategory-data');
		Route::get('edit-accountsubcategory-data', [AccountCategoryController::class, 'geteditsub'])->name('edit-accountsubcategory-data');
		Route::post('get-allmaincategory-data', [AccountCategoryController::class, 'getAllMaincategory'])->name('get-allmaincategory-data');
		Route::post('get-allsubcategory-data', [AccountCategoryController::class, 'getAllSubcategory'])->name('get-allsubcategory-data');


		/*--------account--management--------*/
		/*--------account-new-management---------*/

		
		Route::get('accountsnbanks-list', [AccAccountController::class, 'index'])->name('accountsnbanks-list');
		Route::get('get-accountsnbanks-list', [AccAccountController::class, 'getdata'])->name('get-accountsnbanks-list');
		Route::post('add-update-accountsnbanks', [AccAccountController::class, 'Add_Or_Update'])->name('add-update-accountsnbanks');
		Route::get('edit-accountsnbanks', [AccAccountController::class, 'getedit'])->name('edit-accountsnbanks');
		Route::post('accountsOrBanksInDropdown', [AccAccountController::class, 'accountsOrBanksInDropdown'])->name('accountsOrBanksInDropdown');



		Route::get('transaction-category-list', [AccCategoryController::class, 'index'])->name('transaction-category-list');
		Route::get('get-transaction-subcategory-list', [AccCategoryController::class, 'getdata'])->name('get-transaction-subcategory-list');
		Route::post('change-transaction-subcategory-status', [AccCategoryController::class, 'changeTransactionSubcategoryStatus'])->name('change-transaction-subcategory-status');
		Route::get('edit-transaction-subcategory-data', [AccCategoryController::class, 'getedit'])->name('edit-transaction-subcategory-data');
		Route::post('add-update-transaction-subcategory', [AccCategoryController::class, 'Add_Or_Update'])->name('add-update-transaction-subcategory');
		Route::post('transactionSubCategoryInDropDown', [AccCategoryController::class, 'transactionSubCategoryInDropDown'])->name('transactionSubCategoryInDropDown');



		Route::get('vendor-list', [VendorController::class, 'index'])->name('vendor-list');
		Route::get('view-vendor', [VendorController::class, 'viewIndex'])->name('view-vendor');
		Route::get('get-vendor-list', [VendorController::class, 'getdata'])->name('get-vendor-list');
		Route::post('change-vendor-status', [VendorController::class, 'changeTransactionSubcategoryStatus'])->name('change-vendor-status');
		Route::get('getedit-vendor', [VendorController::class, 'getedit'])->name('getedit-vendor');
		Route::post('add-update-vendor', [VendorController::class, 'Add_Or_Update'])->name('add-update-vendor');
		Route::post('vendorInDropDown', [VendorController::class, 'vendorInDropDown'])->name('vendorInDropDown');
		Route::get('get-transaction-list-vendorwise', [VendorController::class, 'getdataTransactionWise'])->name('get-transaction-list-vendorwise');

		




		Route::get('transaction-list', [TransactionController::class, 'index'])->name('transaction-list');
		Route::get('get-transaction-list', [TransactionController::class, 'getdata'])->name('get-transaction-list');
		Route::post('add-transaction-submit', [TransactionController::class, 'Add'])->name('add-transaction-submit');
		Route::post('update-transaction-submit', [TransactionController::class, 'Update'])->name('update-transaction-submit');
		Route::get('add-transaction', [TransactionController::class, 'addIndex'])->name('add-transaction');
		Route::get('edit-transaction', [TransactionController::class, 'editIndex'])->name('edit-transaction');
		Route::post('delete-this-transaction', [TransactionController::class, 'deleteTransaction'])->name('delete-this-transaction');
		Route::post('transactions_export_excel', [TransactionController::class, 'exportExcelTransaction'])->name('transactions_export_excel');

		

		




		

		





		


		

		





		/*--------account-new-management--------*/
		/*--------offers & subscription--starts------*/
		Route::get('coupon-list', [CouponController::class, 'index'])->name('coupon-list');
		Route::get('get-coupon-list', [CouponController::class, 'getdata'])->name('get-coupon-list');
		Route::get('coupon-view', [CouponController::class, 'viewEditIndex'])->name('coupon-view');
		Route::get('coupon-add', [CouponController::class, 'addIndex'])->name('coupon-add');
		Route::post('change-coupon-status', [CouponController::class, 'changeStatus'])->name('change-coupon-status');
		Route::post('submit-coupon-add', [CouponController::class, 'Add'])->name('submit-coupon-add');
		Route::post('submit-coupon-update', [CouponController::class, 'Update'])->name('submit-coupon-update');



		Route::get('subscription-list', [SubscriptionController::class, 'index'])->name('subscription-list');
		Route::get('get-subscription-list', [SubscriptionController::class, 'getdata'])->name('get-subscription-list');
		Route::get('subscription-view', [SubscriptionController::class, 'viewEditIndex'])->name('subscription-view');
		Route::get('subscription-add', [SubscriptionController::class, 'addIndex'])->name('subscription-add');
		Route::post('change-subscription-status', [SubscriptionController::class, 'changeStatus'])->name('change-subscription-status');
		Route::post('submit-subscription-add', [SubscriptionController::class, 'Add'])->name('submit-subscription-add');
		Route::post('submit-subscription-update', [SubscriptionController::class, 'Update'])->name('submit-subscription-update');


		Route::get('subscription-purchase-list', [SubscriptionController::class, 'purchaseIndex'])->name('subscription-purchase-list');
		Route::get('subscription-feature-list', [SubscriptionController::class, 'subscriptionFeatureIndex'])->name('subscription-feature-list');
		Route::get('get-subscription-purchase-list', [SubscriptionController::class, 'getdata_purchase'])->name('get-subscription-purchase-list');
		Route::get('get-subscription-feature-list', [SubscriptionController::class, 'getdata_subscriptionFeature'])->name('get-subscription-feature-list');
		Route::post('add-update-subscription-feature', [SubscriptionController::class, 'addOrUpdateSubscriptionFeature'])->name('add-update-subscription-feature');
		Route::get('edit-subscription-feature-data', [SubscriptionController::class, 'getEditSubscriptionFeature'])->name('edit-subscription-feature-data');
		Route::get('subscription-purchase-add', [SubscriptionController::class, 'addSubscriptionPurchaseManuallyIndex'])->name('subscription-purchase-add');
		Route::post('subscription-purchase-add-manually', [SubscriptionController::class, 'addSubscriptionPurchaseManually'])->name('subscription-purchase-add-manually');

		



		Route::get('wallet-transaction-list', [WalletController::class, 'index'])->name('wallet-transaction-list');
		Route::get('get-wallet-transaction-list', [WalletController::class, 'getdata'])->name('get-wallet-transaction-list');
		Route::get('wallet-credit-add', [WalletController::class, 'addIndex'])->name('wallet-credit-add');
		Route::post('wallet-credit-add-manually', [WalletController::class, 'addWalletCreditManually'])->name('wallet-credit-add-manually');


		Route::get('walletcredit-list', [WalletController::class, 'walletCreditListIndex'])->name('walletcredit-list');
		Route::get('get-walletcredit-list', [WalletController::class, 'getdata_walletCredit'])->name('get-walletcredit-list');
		Route::get('edit-walletcredit-data', [WalletController::class, 'getEditWalletCredit'])->name('edit-walletcredit-data');
		Route::post('add-update-walletcredit', [WalletController::class, 'addOrUpdateWalletCredit'])->name('add-update-walletcredit');

		


		

		

		





		/*--------offers & subscription--ends---------*/

		Route::get('admins', [AdminManage::class, 'index'])->name('admins');
		Route::get('get_admins_data', [AdminManage::class, 'getdata'])->name('get_admins_data');
		Route::get('view-admin', [AdminManage::class, 'view_addedit_index'])->name('view-admin');
		Route::get('admins-add', [AdminManage::class, 'add_index'])->name('admins-add');
		Route::post('add-update-admins', [AdminManage::class, 'Add_OR_Update'])->name('add-update-admins');
		Route::get('edit-admins-data', [AdminManage::class, 'getedit'])->name('edit-admins-data');
		/*--------custom fields--management--------*/
		Route::get('goods-type', [GoodsTypeController::class, 'index'])->name('goods-type');
		Route::get('get_goods_type_data', [GoodsTypeController::class, 'getdata'])->name('get_goods_type_data');
		Route::post('add-update-goodstype', [GoodsTypeController::class, 'Add_OR_Update'])->name('add-update-goodstype');
		Route::get('edit-goodstype-data', [GoodsTypeController::class, 'getedit'])->name('edit-goodstype-data');

		Route::get('feedback-list', [FeedbackController::class, 'index'])->name('feedback-list');
		Route::get('get-feedback-data', [FeedbackController::class, 'getdata'])->name('get-feedback-data');

		Route::get('all-notifications', [NotificationController::class, 'index'])->name('all-notifications');
		Route::get('get-all-notifications-list', [NotificationController::class, 'getdata'])->name('get-all-notifications-list');
		Route::get('notification-append-admin', [NotificationController::class, 'getFirst10Notifications'])->name('notification-append-admin');

		/*--------custom fields--management--------*/

		Route::get('inquiry-list', [InquiryController::class, 'index'])->name('inquiry-list');
		Route::get('get-inquiry-data', [InquiryController::class, 'getdata'])->name('get-inquiry-data');
		Route::post('change-inquiry-completed', [InquiryController::class, 'change_status_inquiry_completed'])->name('change-inquiry-completed');



		/*-----------Normal used Globally functions -----------------*/
		Route::get('change_status', [Dashboard::class, 'change_status'])->name('change_status');
		Route::get('log-out', [Dashboard::class, 'log_out'])->name('log-out');
		Route::get('dashboard', [Dashboard::class, 'index'])->name('dashboard');
		Route::get('main', [Dashboard::class, 'main'])->name('main');
		Route::get('notfound', [Dashboard::class, 'notfound'])->name('notfound');


		/*---------company-configuration----starts-----------*/
		Route::get('company-detail', [CompanyController::class, 'index'])->name('company-detail');
		Route::post('update-company-details', [CompanyController::class, 'Add_Or_Update'])->name('update-company-details');
		/*---------company-configuration----ends-----------*/

		

		
		/*-----------Normal used Globally functions -----------------*/

		
	});
});



Route::post('/adminside/log-in', [LoginController::class, 'login'])->name('log-in');
Route::get('/adminside/loginpage', [LoginController::class, 'index'])->name('loginpage');

Route::get('/adminside', function () {
    if (Session::has('adminid')) {
        return redirect()->route('dashboard'); 
    } else {
    	return redirect()->route('loginpage');
       // return view('admin.login'); 
    }
})->name('loginscreen');





/*------------client ---------------*/
Route::get('/', [HomeController::class, 'index'])->name('');
Route::get('test', [HomeController::class, 'test'])->name('test');
Route::get('send-notifications', [HomeController::class, 'create_event'])->name('send-notifications');


Route::get('privacy-policy', [HomeController::class, 'privacypolicy'])->name('privacy-policy');
Route::get('terms-conditions', [HomeController::class, 'termscondition'])->name('terms-conditions');


Route::get('order-payment', [HomeController::class, 'razorpaytestpage'])->name('order-payment');
Route::get('wallet-payment-test', [HomeController::class, 'razorpaytestpagewallet'])->name('wallet-payment-test');





// Route::get('change_lang/{locale}', function ($locale) {
//     Session::put('locale', $locale);
//     return redirect()->back();
// });


Route::get('/change_lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'hi', 'gj'])) {
		Session::put('locale', $locale);
		App::setLocale($locale);
	}
	else{
		App::setLocale('en');
	}
	return redirect()->back();
});


 


/*------------client ---------------*/
































