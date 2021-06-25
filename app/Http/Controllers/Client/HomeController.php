<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RazorPayPayment;
use Session;
use Razorpay\Api\Api;

class HomeController extends Controller
{

	public function index(){
        $sql = "SELECT * FROM `tbl_short_helper` WHERE is_active='0' order by rand() LIMIT 2 ";
        $test = qry($sql);
        $data = ['tbl' => $test];
		return view('client.home')->with($data);
	}


	public function test(){
		return view('client.test');
	}

	public function create_event(){
		$title = 'New Order';
		$icon = 'https://static.thenounproject.com/png/1475504-200.png';
		$image = 'https://static.thenounproject.com/png/1475504-200.png';
		$text1 = 'there is a new order from gautam bajrangi';
		$linkurl= 'https://bigdaddylogistics.com/adminside/order-list';
		pushNotificationToAdmin($title, $text1, $icon, $image,$linkurl);
		echo "event sent";
	}



	public function privacypolicy(){
		$data = ['dt' => 0];
		return view('client.privacypolicy')->with($data);
	}

	public function termscondition(){
		$data = ['dt' => 0];
		return view('client.termscondition')->with($data);
	}


	public function razorpaytestpage(Request $request){
		$orderId = isset($_GET['payorder']) ? trim(intval($_GET['payorder'])) : 0;
		$dataRazorPayPayment = RazorPayPayment::where('order_id', $orderId)->where('is_active', constants('is_active_yes'))->orderBy('id','DESC')->first();
        $data = ['tbl' => 0, 'order_id' => $orderId, 'dataRazorPayPayment' => $dataRazorPayPayment, "token" => "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.QmlnZGFkZHlfYXBp.XIWTOOYp6eiwwgci0Rin3oftAW6aAkCh7EpidAIMrYo",];
		return view('client.razorpaytest')->with($data);
	}

	public function razorpaytestpagewallet(Request $request){
        $data = ['tbl' => 0, "token" => "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.QmlnZGFkZHlfYXBp.XIWTOOYp6eiwwgci0Rin3oftAW6aAkCh7EpidAIMrYo", ];
		return view('client.razorpaytestwallet')->with($data);
	}

	





 



}
