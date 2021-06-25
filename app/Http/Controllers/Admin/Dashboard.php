<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
date_default_timezone_set('Asia/Kolkata');



class Dashboard extends Controller
{
    private $tbl = "admins";

	public function index(Request $request){
        is_allowedRoute('dashboard');
        $todaydate = date('Y-m-d').'%';
        $thisyear = date('Y-').'%';
        $onedayafter = date('Y-m-d', strtotime('+1 days'));
        $last7daybefore = date('Y-m-d', strtotime('-7 days'));

        $today = []; $chartdata = [];
        $sql = "SELECT  (SUM(o.final_cost)-SUM(o.discount)) as total_balance,count(*) as total_count FROM `tbl_orders` o where created_at LIKE '".$todaydate."' and is_active='0' ";
        $today['total'] = qry($sql);
        $sql = "SELECT count(*) as total_cancelled FROM `tbl_orders` o where created_at LIKE '".$todaydate."' and is_active='0' and status IN ('C','CU') ";
        $today['cancelorder'] = qry($sql);
        $sql = "SELECT count(*) as total_delivered FROM `tbl_orders` o where created_at LIKE '".$todaydate."' and is_active='0' and status IN ('D') ";
        $today['deliveredorder'] = qry($sql);
        $sql = "SELECT count(*) as neworder FROM `tbl_orders` o where created_at LIKE '".$todaydate."' and is_active='0' and status IN ('P','PU') ";
        $today['neworder'] = qry($sql);

        $sql = "SELECT count(*) as assigned FROM `tbl_orders` o where created_at LIKE '".$todaydate."' and is_active='0' and status IN ('A') ";
        $today['assigned'] = qry($sql);

        /*--------chart data starts---------------*/
        $sql = "SELECT count(*) as series,u.customer_type as labels FROM `tbl_users` u where u.is_active='0' group by u.customer_type ";
        $chartdata['customer'] = qry($sql);

        $sql = "SELECT  (sum(o.final_cost) + sum(o.min_order_value_charge)+sum(o.redeliver_charge)-sum(o.discount)) as total_balance,count(*) as total_count FROM `tbl_orders` o where is_active='0' and o.status='D' ";
        $chartdata['total_revenue'] = qry($sql);

        $sql = "SELECT SUM(am.amount) as total_balance,count(*) as total_count FROM `tbl_account_manage` am where am.is_active='0' and am.type='Dr' ";
        $chartdata['total_expenses'] = qry($sql);




        $sql = "SELECT (SUM(o.final_cost)-SUM(o.discount)) as total_revenue, MONTH(o.created_at) as month_number, count(*) as total_count FROM `tbl_orders` o where o.is_active='0' and o.created_at LIKE '".$thisyear."' and o.status='D'
        group by MONTH(o.created_at) ";
        $chartdata['thisyear_revenue_graph'] = qry($sql);




        $sql = "SELECT SUM(am.amount) as total, MONTH(am.account_datetime) as month_number, count(*) as total_count FROM `tbl_account_manage` am where am.is_active='0' and am.account_datetime LIKE '".$thisyear."' and am.type='Dr' group by MONTH(am.account_datetime) ";
        $chartdata['thisyear_expenses_graph'] = qry($sql);


        $sql = "SELECT (SUM(o.final_cost)-SUM(o.discount)) as total_revenue, DATE(o.created_at) as date_number, count(*) as total_count FROM `tbl_orders` o where o.is_active='0' and ( o.created_at Between '".$last7daybefore."' and '".$onedayafter."') and o.status='D'
        group by DATE(o.created_at) ";
        $chartdata['last7days_revenue_graph_delivered'] = qry($sql);


        $sql = "SELECT (SUM(o.final_cost)-SUM(o.discount)) as total_revenue, DATE(o.created_at) as date_number, count(*) as total_count 
        FROM `tbl_orders` o where o.is_active='0' and ( o.created_at Between '".$last7daybefore."' and '".$onedayafter."') and o.status IN ('C','CU')
        group by DATE(o.created_at) 
         ";
        $chartdata['last7days_revenue_graph_cancelled'] = qry($sql);



        /*-----------chart data ends-----------------*/

        $data = ['tbl' => '', 'today' => $today, 'chartdata' => $chartdata ];
		return view('admin.index')->with($data);
	}

    public function change_status(Request $request){
        $id = update_data($request->tbl,['is_active' => $request->value],['id' => $request->id ]);
        echo json_encode($id);
    }

    public function log_out(Request $request){
        $updateData = [ 'active_session' => NULL ];
        if(Session::has('adminid')){
            update_data($this->tbl, $updateData ,['id' => Session::get('adminid') ]);
        }
        $request->session()->flush();
        return redirect()->route('loginpage');
    }

    public function notfound(Request $request){
        return view('admin.notfound');
    }
    

    
    public function main(Request $request){
        return view('admin.login');
    }




}
