<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
date_default_timezone_set('Asia/Kolkata');
use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Models\AccessToken;
use App\Models\ShortHelper;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use App\Models\AccTransactionSubCategory;
use App\Models\AccAccountBanks;
use App\Models\SubscriptionPurchase;
use App\Models\Subscription;
use Illuminate\Support\Facades\Log;

class CustomerController extends Controller
{
    public $tbl = "tbl_users";
    public $tbl2 = "tbl_address";
    public $tbl3 = "tbl_orders";
    public $tbl4 = "tbl_invoice";
    
    public function index(Request $request){
        $sql = 'SELECT id,name,short,details FROM tbl_short_helper where type="customer_type" and is_active="0" ';
        $customer_type = qry($sql);
    	$data = ['tbl' => $this->tbl, 'control' => 'Customer List', 'customer_type' => $customer_type ];
		return view('admin.customer.list')->with($data);
	}

    public function add_index(Request $request){
        $sql = 'SELECT id,name,short,details FROM tbl_short_helper where type="address_type" and is_active="0" ';
        $address_type = qry($sql);
        $data = ['tbl' => $this->tbl, 'control' => 'Customer Add' , 'address_type' => $address_type ];
        return view('admin.customer.add')->with($data);
    }

    public function view_edit_index($Id=0,Request $request){
        $transactionSubCategoryInDropDown = AccTransactionSubCategory::where('is_active', constants('is_active_yes'))->limit(250)->get();
        $accountBanksInDropDown = AccAccountBanks::where('is_active', constants('is_active_yes'))->limit(250)->get();
        $payment_statusTypeData = ShortHelper::where('is_active', constants('is_active_yes'))->where('type', 'payment_status')->limit(50)->get();
        $sql = 'SELECT id,name,short,details FROM tbl_short_helper where type="address_type" and is_active="0" ';
        $address_type = qry($sql);
        $Id = trim(intval($Id));
        $sql = 'SELECT u.* FROM '.$this->tbl.' u WHERE u.id='.$Id.' and u.is_active!="2" LIMIT 1';
        $one = qry($sql);
        $data = ['tbl' => $this->tbl, 'control' => 'Customer', 'one' => $one , 'address_type' => $address_type, 'payment_status' => $payment_statusTypeData , 'transactionSubCategoryInDropDown' => $transactionSubCategoryInDropDown, 'accountBanksInDropDown' => $accountBanksInDropDown ];
        return view('admin.customer.view')->with($data);
    }

    public function getedit(Request $request){
        $Id = trim(abs($request->id));
        $sql = 'SELECT u.* FROM '.$this->tbl.' u WHERE u.id='.$Id.' and u.is_active!="2" LIMIT 1';
        $response = qry($sql);
        return response()->json($response);
    }

	
    public function salesexecutive_index(Request $request){
        $sql = 'SELECT id,name,short,details FROM tbl_short_helper where type="customer_type" and is_active="0" ';
        $customer_type = qry($sql);
    	$data = ['tbl' => $this->tbl, 'control' => 'Customer List', 'customer_type' => $customer_type ];
		return view('admin.salesexecutive.customer_list')->with($data);
	}
	
	public function getdata_salesexecutive(Request $request){
        $columns = array(          
            0 => 'u.id',
            1 => 'u.fullname',
            2 => 'u.GST_number',
            3 => 'u.business_name',
            4 => 'u.is_active',
        );

        $filter_customer_type_Sql = '';
        if(isset($request->customer_type) && $request->customer_type!=''){
            $filter_customer_type_Sql = ' and u.customer_type LIKE "'.$request->customer_type.'" '; 
        }

        $order_status = "'" . implode ( "', '", constants('order_status.undelivered_orders') ) . "'";
        $order_status_sql =  " and o.status IN(".$order_status.") ";
		
		
		

        $sql = "select u.id,u.fullname,u.is_active,u.customer_type,u.email,u.business_name,u.GST_number,(select count(*) from tbl_orders o where o.user_id=u.id $order_status_sql) as undeliveredCount from ".$this->tbl." u INNER JOIN tbl_salesexecutive_cutomerlist ec ON ec.user_id=u.id and ec.admin_id='".Session::get("adminid")."' WHERE u.is_active!='2' ".$filter_customer_type_Sql." ";

        $query = qry($sql);
        $totalData = count($query);
        $totalFiltered = $totalData;

        if (!empty($request['search']['value'])) {   
            $searchString = "'%" . str_replace(",", "','", $request['search']['value']) . "%'"; 
            $sql .= " and ( u.id LIKE " . $searchString;
            $sql .= " OR u.email LIKE " . $searchString;
            $sql .= " OR u.business_name LIKE " . $searchString;
            $sql .= " OR u.mobile LIKE " . $searchString;
            $sql .= " OR u.GST_number LIKE " . $searchString;
            $sql .= " OR u.fullname LIKE " . $searchString . ")";
        }

        $query = qry($sql);
        $totalFiltered = count($query);

        $sql .= " ORDER BY " . $columns[$request->order[0]['column']] . "   " . $request->order[0]['dir'] . " LIMIT " . $request->start . " ," . $request->length . "   ";  
        $query = qry($sql);

        $data = array();

        $cnts = $request->start + 1;
        foreach ($query as $row) {
            $status = "";  $view = ""; 

            $customer_type = "<br><span class='shadow-none badge badge-info' >".$row->customer_type."</span>";
          
            $nestedData = array();
            $nestedData[] = $cnts;
            $nestedData[] = $row->fullname." ".$customer_type;
            $nestedData[] = $row->GST_number;
            $nestedData[] = $row->business_name;

            $view = "<a style='cursor: pointer;'  href='".route('view-customer').'/'.$row->id."' class='btn btn-primary btn-sm editit' data-id='".$row->id."'>View</a>";

            $nestedData[] = $view;
            $nestedData[] = $row->undeliveredCount;
            $nestedData['DT_RowId'] = "r" . $row->id;
            $data[] = $nestedData;
            $cnts++;
        }

        $json_data = array(
            "draw" => intval($request['draw']), 
            "recordsTotal" => intval($totalData), 
            "recordsFiltered" => intval($totalFiltered), 
            "data" => $data
        );

        echo json_encode($json_data);
	}

	public function getdata(Request $request){
        $columns = array(          
            0 => 'u.id',
            1 => 'u.fullname',
            2 => 'u.GST_number',
            3 => 'u.business_name',
            4 => 'u.is_active',

        );

        $filter_customer_type_Sql = '';
        if(isset($request->customer_type) && $request->customer_type!=''){
            $filter_customer_type_Sql = ' and u.customer_type LIKE "'.$request->customer_type.'" '; 
        }

        $order_status = "'" . implode ( "', '", constants('order_status.undelivered_orders') ) . "'";
        $order_status_sql =  " and o.status IN(".$order_status.") ";

        $sql = "select u.id,u.fullname,u.is_active,u.customer_type,u.email,u.business_name,u.GST_number,(select count(*) from tbl_orders o where o.user_id=u.id $order_status_sql) as undeliveredCount from ".$this->tbl." u WHERE u.is_active!='2' ".$filter_customer_type_Sql." ";

        $query = qry($sql);
        $totalData = count($query);
        $totalFiltered = $totalData;

        if (!empty($request['search']['value'])) {   
            $searchString = "'%" . str_replace(",", "','", $request['search']['value']) . "%'"; 
            $sql .= " and ( u.id LIKE " . $searchString;
            $sql .= " OR u.email LIKE " . $searchString;
            $sql .= " OR u.business_name LIKE " . $searchString;
            $sql .= " OR u.mobile LIKE " . $searchString;
            $sql .= " OR u.GST_number LIKE " . $searchString;
            $sql .= " OR u.fullname LIKE " . $searchString . ")";
        }

        $query = qry($sql);
        $totalFiltered = count($query);

        $sql .= " ORDER BY " . $columns[$request->order[0]['column']] . "   " . $request->order[0]['dir'] . " LIMIT " . $request->start . " ," . $request->length . "   ";  
        $query = qry($sql);

        $data = array();

        $cnts = $request->start + 1;
        foreach ($query as $row) {
            $status = "";  $view = ""; 

            $customer_type = "<br><span class='shadow-none badge badge-info' >".$row->customer_type."</span>";
          
            $nestedData = array();
            $nestedData[] = $cnts;
            $nestedData[] = $row->fullname." ".$customer_type;
            $nestedData[] = $row->GST_number;
            $nestedData[] = $row->business_name;

            if(is_allowedHtml('roleclass_view_btn_customer')==true){
                $view = "<a style='cursor: pointer;'  href='".route('view-customer').'/'.$row->id."' class='btn btn-primary btn-sm editit' data-id='".$row->id."'>View</a>";
            }

            if(is_allowedHtml('roleclass_status_btn_customer')==true){
                if ($row->is_active == 0) {
                $status = "<a style='cursor: pointer;' class='btn btn-success btn-sm change_status_confirm' data-id='".$row->id."' data-val='1'>ACTIVE</a>";
                } else {
                $status = "<a style='cursor: pointer;' class='btn btn-danger btn-sm change_status_confirm' data-id='".$row->id."' data-val='0'>DEACTIVE</a>";
                }
            }

            $nestedData[] = $status;
            $nestedData[] = $view;
            $nestedData[] = $row->undeliveredCount;
            $nestedData['DT_RowId'] = "r" . $row->id;
            $data[] = $nestedData;
            $cnts++;
        }

        $json_data = array(
            "draw" => intval($request['draw']), 
            "recordsTotal" => intval($totalData), 
            "recordsFiltered" => intval($totalFiltered), 
            "data" => $data
        );

        echo json_encode($json_data);
	}

	public function Add(Request $request) {
        $sqlGst = ""; $sqlPan = ""; $sqlEmail = ""; $sqlMobile = "";
       // ini_set('memory_limit', -1);

        if(!isset($request->password) OR !isset($request->customer_type) OR $request->customer_type=='' OR !isset($request->user_paymentbill_type) OR !in_array($request->user_paymentbill_type, constants('user_paymentbill_type'))  ) {
            Session::flash('msg', 'Please Try Again and Fill All Required Field !');
            Session::flash('cls', 'danger');
        }
        else {

            if(isset($request->email) && $request->email!='' && filter_var($request->email, FILTER_VALIDATE_EMAIL)){
                $sqlEmail = "OR (email='".$request->email."' and email!='') ";
            }

            if(isset($request->mobile) && $request->mobile!=''){
                $sqlMobile = "OR (mobile='".$request->mobile."' and mobile!='') ";
            }

            if(isset($request->GST_number) && $request->GST_number!=''){
                $sqlGst = "OR (GST_number='".$request->GST_number."' and GST_number!='') ";
            }
            if(isset($request->pan_no) && $request->pan_no!=''){
                $sqlPan = "OR (pan_no='".$request->pan_no."' and pan_no!='') ";
            }


            $sql = "select id from ".$this->tbl." WHERE 1=2 $sqlEmail $sqlMobile $sqlGst $sqlPan ";
            $count = qry($sql);


            if(!empty($count)){
            Session::flash('msg', 'Please Check Details Properly, This Email Or Mobile OR GST Number is Already Registered !');
            Session::flash('cls', 'danger');
            return redirect()->route('customer-add');
            }

            $fullname = isset($request->fullname) ? explode(' ', $request->fullname) : NULL;

            $data = [
                'firstname' => isset($fullname[0]) ? $fullname[0] : '',
                'lastname' => isset($fullname[1]) ? $fullname[1] : '',
                'fullname' => $request->fullname,
                'customer_gst_exempted_type' => isset($request->customer_gst_exempted_type) ? $request->customer_gst_exempted_type : NULL,
                'pan_no' => isset($request->pan_no) ? $request->pan_no : NULL,
                'business_type' => isset($request->business_type) ? $request->business_type : NULL,
                'customer_type' => $request->customer_type,
                'ownership' => isset($request->ownership) ? $request->ownership : NULL,
                'business_name' => isset($request->business_name) ? $request->business_name : NULL,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'GST_number' => isset($request->GST_number) ? $request->GST_number : NULL,
                'password' => bcrypt($request->password),
                'added_by' => Session::get("adminid"),
                'updated_by' => Session::get("adminid"),
                'user_paymentbill_type' => $request->user_paymentbill_type,
                'is_active' => ($request->status==0) ? 0 : 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ];

            $id = insert_data_id($this->tbl,$data);

            if($id > 0){
                $i =0;
            if(!empty($request->country)){
                foreach ($request->country as $key => $value) {
                    $addressdata = [
                'user_id' => $id,
                'country' => $request->country[$i],
                'state' => $request->state[$i],
                'city' => $request->city[$i],
                'pincode' => $request->pincode[$i],
                'address' => $request->address[$i],
                'landmark' => isset($request->landmark[$i]) ? $request->landmark[$i] : NULL,
                'address_type' => $request->address_type[$i],
                'is_default' => $request->is_default_check[$i],
                'longitude' => $request->longitude[$i],
                'latitude' => $request->latitude[$i],
                'added_by' => Session::get("adminid"),
                'updated_by' => Session::get("adminid"),
                'is_active' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'contact_person_name' => $request->contact_person_name[$i],
                'contact_person_number' => $request->contact_person_number[$i],
                'transporter_name' => $request->transporter_name[$i],
                ];
                
                $addressid = insert_data_id($this->tbl2,$addressdata);
                    $i++;
                }
            }
            Session::flash('msg', ' Added Successfully!');
            Session::flash('cls', 'success');
            }
            else
            {
            Session::flash('msg', 'Something Went Wrong. Please Try Again!!');
            Session::flash('cls', 'danger');
            }
        }
        return redirect()->route('customer-add');
    }

    public function Update(Request $request) {
        $sqlGst = ""; $sqlPan = ""; $sqlEmail = ""; $sqlMobile = "";

        $validator = Validator::make($request->all(), [ 
            'status' => 'required',
        ]);   

        if($validator->fails() OR !isset($request->user_paymentbill_type) OR !in_array($request->user_paymentbill_type, constants('user_paymentbill_type')) ) {          
            Session::flash('msg', 'Please Try Again and Fill All Required Field !');
            Session::flash('cls', 'danger'); 
            return redirect()->back();              
        }


        if(!isset($request->hid) OR $request->hid<1 OR !isset($request->customer_type) OR ($request->customer_type=='')) {
            Session::flash('msg', 'Please Try Again and Fill All Required Field !');
            Session::flash('cls', 'danger');
        }
        else 
        {
            if($request->customer_type=="Individual"){
                if(!isset($request->pan_no) OR ($request->pan_no=='') OR !isset($request->fullname) OR ($request->fullname=='' )) {
                    Session::flash('msg', 'Please Try Again and Fill All Required Field !');
                    Session::flash('cls', 'danger');
                }
            }
            else if($request->customer_type=="Transporter"){
                if(!isset($request->business_name) OR ($request->business_name=='') OR !isset($request->GST_number) OR strlen($request->GST_number)!=15 OR !isset($request->business_type) OR ($request->business_type=='') OR !isset($request->ownership) OR ($request->ownership=='' )) {
                    Session::flash('msg', 'Please Try Again and Fill All Required Field !');
                    Session::flash('cls', 'danger');
                }
            }
            else if($request->customer_type=="Business"){
                if(!isset($request->business_name) OR ($request->business_name=='') OR ($request->GST_number=='' && $request->pan_no=='') OR !isset($request->business_type) OR ($request->business_type=='') OR !isset($request->ownership) OR ($request->ownership=='' ) ) {
                    Session::flash('msg', 'Please Try Again and Fill All Required Field !');
                    Session::flash('cls', 'danger');
                }
            }

            if(isset($request->email) && $request->email!='' && filter_var($request->email, FILTER_VALIDATE_EMAIL)){
                $sqlEmail = "OR (email='".$request->email."' and email!='') ";
            }

            if(isset($request->mobile) && $request->mobile!=''){
                $sqlMobile = "OR (mobile='".$request->mobile."' and mobile!='') ";
            }

            if(isset($request->GST_number) && $request->GST_number!=''){
                $sqlGst = "OR (GST_number='".$request->GST_number."' and GST_number!='') ";
            }
            if(isset($request->pan_no) && $request->pan_no!=''){
                $sqlPan = "OR (pan_no='".$request->pan_no."' and pan_no!='') ";
            }

            
            $sql = "select id,mobile from ".$this->tbl." WHERE ( 1=2 $sqlEmail $sqlMobile $sqlGst $sqlPan ) and id!='".$request->hid."' ";
            $count = qry($sql);

            if(!empty($count)){
            Session::flash('msg', 'This Mobile Number,GST number or Email is Already Registered !');
            Session::flash('cls', 'danger');
            return redirect()->back();
            }

            if(isset($request->password)){
            $updateData['password'] = bcrypt($request->password);
            update_data($this->tbl, $updateData ,['id' => $request->hid ]);
            }

            $fullname = isset($request->fullname) ? explode(' ', $request->fullname) : NULL;

            $updateData = [
                'firstname' => isset($fullname[0]) ? $fullname[0] : '',
                'lastname' => isset($fullname[1]) ? $fullname[1] : '',
                'fullname' => $request->fullname,
                'pan_no' => isset($request->pan_no) ? $request->pan_no : NULL,
                'ownership' => $request->ownership,
                'business_type' => isset($request->business_type) ? $request->business_type : NULL,
                'ownership' => isset($request->ownership) ? $request->ownership : NULL,
                'business_name' => isset($request->business_name) ? $request->business_name : NULL,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'GST_number' => isset($request->GST_number) ? $request->GST_number : NULL,
                'updated_by' => Session::get("adminid"),
                'user_paymentbill_type' => $request->user_paymentbill_type,
                'is_active' => ($request->status==0) ? 0 : 1,
                'updated_at' => date('Y-m-d H:i:s'),
            ];


                if(isset($request->transporter_name) && $request->transporter_name!=''){
                    $updateData['transporter_name'] = $request->transporter_name;
                }

                
            update_data($this->tbl, $updateData ,['id' => $request->hid ]);

            if($request->status==constants('is_active_no')){
                AccessToken::where('usertype', constants('usertype.customer'))->where('user_id', $request->hid)->delete();
            }

            Session::flash('msg', 'Updated Successfully!');
            Session::flash('cls', 'success');
        }
        return redirect()->back();
    }



    public function fill_customer_data_api(Request $request){
        if(!isset($request->GST_number)){
            $response = ['success' => false, 'data' => NULL, 'msg' => 'InValid GST Number'];
        }
        else
        {
            $response = verifyGSTno($request->GST_number);
        }
        echo json_encode($response);
    }


    public function getdata_address(Request $request){
        $columns = array(          
            0 => 'u.id',
            1 => 'u.address',
            2 => 'u.pincode',
            3 => 'u.city',
            4 => 'u.is_active',

        );
        $sql = "select u.* from tbl_address u WHERE u.is_active!='2' and u.user_id=".$request->uid." ";

        $query = qry($sql);
        $totalData = count($query);
        $totalFiltered = $totalData;

        if (!empty($request['search']['value'])) {   
            $searchString = "'%" . str_replace(",", "','", $request['search']['value']) . "%'"; 
            $sql .= " and ( u.id LIKE " . $searchString;
            $sql .= " OR u.city LIKE " . $searchString;
            $sql .= " OR u.address LIKE " . $searchString;
            $sql .= " OR u.landmark LIKE " . $searchString;
            $sql .= " OR u.state LIKE " . $searchString;
            $sql .= " OR u.pincode LIKE " . $searchString . ")";
        }

        $query = qry($sql);
        $totalFiltered = count($query);

        $sql .= " ORDER BY " . $columns[$request->order[0]['column']] . "   " . $request->order[0]['dir'] . " LIMIT " . $request->start . " ," . $request->length . "   ";  
        $query = qry($sql);

        $data = array();

        $cnts = $request->start + 1;
        foreach ($query as $row) {
            if($row->is_active==constants('is_active_yes')){
                $status = '<span class="badge badge-success">ACTIVE</span>';
            }
            else
            {
                $status = '<span class="badge badge-danger">INACTIVE</span>';
            }
          
            $nestedData = array();
            $nestedData[] = $cnts;
            $nestedData[] = $row->address;
            $nestedData[] = $row->pincode;
            $nestedData[] = $row->city."<br>".$status;
            $edit = "<a style='cursor: pointer;' class='btn btn-primary btn-sm editit' data-id='".$row->id."'>View</a>";

            $nestedData[] = $edit;
            $nestedData['DT_RowId'] = "r" . $row->id;
            $data[] = $nestedData;
            $cnts++;
        }

        $json_data = array(
            "draw" => intval($request['draw']), 
            "recordsTotal" => intval($totalData), 
            "recordsFiltered" => intval($totalFiltered), 
            "data" => $data
        );

        echo json_encode($json_data);
    }

    public function geteditaddress(Request $request){
        $one = qry('SELECT * FROM '.$this->tbl2.' where id='.$request->id.' LIMIT 1');
        echo json_encode($one);
    }

    public function Add_OR_Updateaddress(Request $request) {
		$is_default = 0;
		if(isset($request->is_default) && $request->is_default==1){ 
            $is_default = 1;
			$data = [ 'is_default' => 0, ];
            $id = update_data($this->tbl2, $data ,[ 'user_id' => $request->user_id ]);
		}
		else
        {
            $countAddress = CustomerAddress::where('is_default',1)->where('is_active', constants('is_active_yes'))->where('user_id',$request->user_id)->count();
			if($countAddress==0){ 
                $is_default = 1;
    			$data = [ 'is_default' => 0, ];
                $id = update_data($this->tbl2, $data ,[ 'user_id' => $request->user_id ]);
			}
		}
		
		
        if($request->hid==0){
           $addressdata = [
                'user_id' => $request->user_id,
                'country' => $request->country,
                'state' => $request->state,
                'city' => $request->city,
                'pincode' => $request->pincode,
                'address' => $request->address,
                'landmark' => isset($request->landmark) ? $request->landmark : NULL,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'address_type' => $request->address_type,
                'is_default' => $is_default,
                'added_by' => Session::get("adminid"),
                'updated_by' => Session::get("adminid"),
                'is_active' => ($request->status==0) ? 0 : 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'contact_person_name' => $request->contact_person_name,
                'contact_person_number' => $request->contact_person_number,
                'transporter_name' => $request->transporter_name,
                ];
            $addressid = insert_data_id($this->tbl2,$addressdata);
        }
        else
        {
			$addressdata = [
                'country' => $request->country,
                'state' => $request->state,
                'city' => $request->city,
                'pincode' => $request->pincode,
                'address' => $request->address,
                'landmark' => isset($request->landmark) ? $request->landmark : NULL,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'address_type' => $request->address_type,
                'is_default' => $is_default,
                'updated_by' => Session::get("adminid"),
                'is_active' => ($request->status==0) ? 0 : 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'contact_person_name' => $request->contact_person_name,
                'contact_person_number' => $request->contact_person_number,
                'transporter_name' => $request->transporter_name,
                ];

            $addressid = update_data($this->tbl2,$addressdata,['id' => $request->hid]);
        }
        echo json_encode($addressid);
    }

    public function change_status_confirm(Request $request){
        $updateData = [
            'is_active' => $request->status,
        ];
        update_data($this->tbl, $updateData ,['id' => $request->id ]);
        AccessToken::where('usertype', constants('usertype.customer'))->where('user_id', $request->id)->delete();

        $logdata = [
                'user_id' => $request->id,
                'logs' => $request->sendLog." " .Session::get("fullname") ,
                'created_at' => date('Y-m-d H:i:s'),
            ];
        $returnid = insert_data_id("tbl_users_logs",$logdata);
        echo json_encode($returnid);
    }

    public function getdataCustomerLogs(Request $request){
        $columns = array(          
            0 => 'ol.id',
            1 => 'ol.logs',
            2 => 'ol.created_at',

        );
        $sql = "select * from tbl_users_logs ol WHERE ol.user_id=".$request->id." ";

        $query = qry($sql);
        $totalData = count($query);
        $totalFiltered = $totalData;

        if (!empty($request['search']['value'])) {   
            $searchString = "'%" . str_replace(",", "','", $request['search']['value']) . "%'"; 
            $sql .= " and ( ol.id LIKE " . $searchString;
            $sql .= " OR ol.logs LIKE " . $searchString;
            $sql .= " OR ol.created_at LIKE " . $searchString . ")";
        }

        $query = qry($sql);
        $totalFiltered = count($query);

        $sql .= " ORDER BY " . $columns[$request->order[0]['column']] . "   " . $request->order[0]['dir'] . " LIMIT " . $request->start . " ," . $request->length . "   ";  
        $query = qry($sql);

        $data = array();

        $cnts = $request->start + 1;
        foreach ($query as $row) {
          
            $nestedData = array();
            $nestedData[] = $cnts;
            $nestedData[] = $row->logs;
            $nestedData[] = $row->created_at;
            $nestedData['DT_RowId'] = "r" . $row->id;
            $data[] = $nestedData;
            $cnts++;
        }

        $json_data = array(
            "draw" => intval($request['draw']), 
            "recordsTotal" => intval($totalData), 
            "recordsFiltered" => intval($totalFiltered), 
            "data" => $data
        );
        echo json_encode($json_data);
    }


    public function fill_pincode_api(Request $request){
        if(!isset($request->pincode)){
            $response = ['success' => false, 'data' => NULL, 'msg' => 'InValid pincode Number'];
        }
        else
        {
            $response = verifyPincode($request->pincode);
        }
        return response()->json($response);
    }

    public function getdata_invoice_customerwise(Request $request){
        $orderStatusForInvoice = array_merge(constants('order_status.delivered_orders'),constants('order_status.transit_orders'));
        $order_status = "'" . implode ( "', '", $orderStatusForInvoice ) . "'";

        $order_status_sql =  " and ordertbl.status IN(".$order_status.") ";
        $order_payment_pending_sql =  " and ordertbl.payment_status=".constants('payment_status.Pending')." and ordertbl.invoice_id=inv.id  ";

        $filter_payment_status_Sql = '';
        if(isset($request->payment_status) && $request->payment_status!=''){
            $filter_payment_status_Sql = ' and o.payment_status LIKE "'.$request->payment_status.'" '; 
        }

        $columns = array(          
            0 => 'inv.invoice_number',
            1 => 'inv.created_at',
            2 => 'inv.created_at',
            3 => 'inv.updated_at',
        );

        $sql = "select inv.*, (select count(ordertbl1.id) from ".$this->tbl3." ordertbl1 where ordertbl1.is_active='".constants('is_active_yes')."' and ordertbl1.invoice_id=inv.id) as ordercount,(select count(ordertbl.id) from ".$this->tbl3." ordertbl where ordertbl.is_active='".constants('is_active_yes')."' ".$order_status_sql.$order_payment_pending_sql." ) as getpayment_status from ".$this->tbl4." inv INNER JOIN ".$this->tbl3." o ON o.invoice_id=inv.id WHERE inv.is_active=".constants('is_active_yes')." and o.user_id=".$request->uid." $filter_payment_status_Sql ";

        $query = qry($sql);
        $totalData = count($query);
        $totalFiltered = $totalData;

        if(!empty($request['search']['value'])) {   
            $searchString = "'%" . str_replace(",", "','", $request['search']['value']) . "%'"; 
            $sql .= " and ( inv.invoice_number LIKE " . $searchString;
            $sql .= " OR inv.invoice_file LIKE " . $searchString;
            $sql .= " OR o.contact_person_name_drop LIKE " . $searchString;
            $sql .= " OR o.contact_person_name LIKE " . $searchString;
            $sql .= " OR o.transporter_lr_number LIKE " . $searchString;
            $sql .= " OR o.bigdaddy_lr_number LIKE " . $searchString . ")";
           
        }

        $c = count($columns);
            for ($i = 0; $i < $c; $i++) {
                if (!empty($request['columns'][$i]['search']['value'])) {
                    $sql .= " AND " . $columns[$i] . " LIKE '%" . $request['columns'][$i]['search']['value'] . "%'  ";
                }
        }
        $sql .= " GROUP BY o.invoice_id ";  
        
        
        $query = qry($sql);
        $totalFiltered = count($query);

        $sql .= " ORDER BY " . $columns[$request->order[0]['column']] . "   " . $request->order[0]['dir'] . " LIMIT " . $request->start . " ," . $request->length . "   "; 
        $query = qry($sql);


        $data = array();

        $cnts = $request->start + 1;
        foreach ($query as $row) {
            $deleteInvoiceBtn = ''; $invoiceBtn = ''; $payment_statusBtn = ''; $vieworderBtn = '';

            
            if($row->invoice_number!=""){
                $invoiceBtn = "<a target='_blank' href='".sendPath().constants('dir_name.invoice').'/'.$row->invoice_file."'><span title='view invoice #".$row->invoice_number."' data-filename=".$row->invoice_file." data-folder=".constants('dir_name.invoice')." class='shadow-none badge badge-secondary spancursorcls filedownloaditno'> ".$row->invoice_number." </span></a>";

                if(is_allowedHtml('roleclass_delete_btn_invoice')==true){
                    $deleteInvoiceBtn = "<a title='delete invoice #".$row->invoice_number."' class='btn btn-danger btn-sm delete_invoice_btnidcls' data-id=".$row->id." data-inv=".$row->invoice_number.">Delete</a>";
                }
                $vieworderBtn = "<span class='shadow-none badge badge-warning spancursorcls vieworderit' data-id=".$row->id.">view</span>";

            }


            $payment_status = " <span class='shadow-none badge badge-".constants('payment_statusName.'.constants('payment_status.Paid').'.classhtml')."' >".constants('payment_statusName.'.constants('payment_status.Paid').'.name')."</span>";

            if(is_allowedHtml('roleclass_payment_btn_invoice')==true){
                if($row->getpayment_status > 0){
                    $payment_statusBtn = '<a href="javascript:void(0)" class="button_margin_bottom_5 btn btn-rounded btn-outline-danger invoicepaymentregisterit" data-id="'.$row->id.'" data-val="'.constants('payment_status.Paid').'" data-toggle="tooltip" data-placement="left" title="Mark As Paid"><i class="far fa-check-circle"></i></a>';

                    $payment_status = " <span class='shadow-none badge badge-".constants('payment_statusName.'.constants('payment_status.Pending').'.classhtml')."' >".constants('payment_statusName.'.constants('payment_status.Pending').'.name')."</span>";
                }
            }

        
            $nestedData = array();
            $nestedData[] = $invoiceBtn.$payment_status;
            $nestedData[] = Carbon::parse($row->invoice_date)->format('d-m-Y');
            $nestedData[] = $row->ordercount." Order(s)"."<br>".$vieworderBtn;
            $nestedData[] = $payment_statusBtn." ".$deleteInvoiceBtn;
            $nestedData['DT_RowId'] = "invtr" . $row->id;
            $data[] = $nestedData;
            $cnts++;
        }

        $json_data = array(
            "draw" => intval($request['draw']), 
            "recordsTotal" => intval($totalData), 
            "recordsFiltered" => intval($totalFiltered), 
            "data" => $data
        );

        echo json_encode($json_data);
    }


    public function getdataTransactionWise(Request $request){

        $columns = array(          
            0 => 'tr.id',
            1 => 'tr.amount',
            2 => 'tr.transaction_date',
            3 => 'tr.transaction_subcategory_id',
            4 => 'tr.transaction_type',
            5 => 'tr.anybillno',
            6 => 'tr.description',
            7 => 'tr.created_at',
        );


        $transaction_type_Sql = '';
        $accountbanks_ids_Sql = '';
        $transaction_subcategory_ids_Sql = '';
        $transaction_date_Sql = '';
        $customer_id_Sql = '';
        $is_reviewed_Sql = '';

        if(isset($request->is_reviewed) && is_array($request->is_reviewed) && !empty($request->is_reviewed)){
            $is_reviewed_Status = "'" . implode ( "', '", $request->is_reviewed ) . "'";
            $is_reviewed_Sql =  " and tr.is_reviewed IN(".$is_reviewed_Status.") ";
        }
        if(isset($request->transaction_date) && $request->transaction_date!=''){
            $date_start = explode(' - ', $request->transaction_date);
            $stop_date = date('Y-m-d', strtotime($date_start[1] . ' +0 day'));
            $transaction_date_Sql = " and tr.transaction_date BETWEEN '".$date_start[0]."' and '".$stop_date."' ";
        }
        if(isset($request->transaction_type) && is_array($request->transaction_type) && !empty($request->transaction_type)){
            $transaction_type_Status = "'" . implode ( "', '", $request->transaction_type ) . "'";
            $transaction_type_Sql =  " and tr.transaction_type IN(".$transaction_type_Status.") ";
        }
        if(isset($request->select_customer_id) && $request->select_customer_id!='' ){
            $customer_id_Sql =  " and tr.user_id=".$request->select_customer_id." ";
        }
        if(isset($request->accountid_from) && is_array($request->accountid_from) && !empty($request->accountid_from)){
            $accountid_from_Status = "'" . implode ( "', '", $request->accountid_from ) . "'";
            $accountbanks_ids_Sql =  " and tr.accountid_from IN(".$accountid_from_Status.") ";
        }
        if(isset($request->transaction_subcategory_id) && is_array($request->transaction_subcategory_id) && !empty($request->transaction_subcategory_id)){
            $transaction_subcategory_ids_Status = "'" . implode ( "', '", $request->transaction_subcategory_id ) . "'";
            $transaction_subcategory_ids_Sql =  " and tr.transaction_subcategory_id IN(".$transaction_subcategory_ids_Status.") ";
        }


        $sql = "select tr.*,tsb.name as tsbname,ab.name as abname,u.fullname as ufullname FROM acc_transactions tr INNER JOIN acc_accounts_or_banks ab ON ab.id=tr.accountid_from LEFT JOIN acc_transaction_subcategory tsb ON tsb.id=tr.transaction_subcategory_id LEFT JOIN ".$this->tbl." u ON u.id=tr.user_id WHERE tr.is_active!='2' ".$transaction_date_Sql.$transaction_type_Sql.$accountbanks_ids_Sql.$transaction_subcategory_ids_Sql.$is_reviewed_Sql.$customer_id_Sql." ";


        $query = qry($sql);
        $totalData = count($query);
        $totalFiltered = $totalData;

        if (!empty($request['search']['value'])) {   
            $searchString = "'%" . str_replace(",", "','", $request['search']['value']) . "%'"; 
            $sql .= " and ( tr.amount LIKE " . $searchString;
            $sql .= " OR tr.anybillno LIKE " . $searchString;
            $sql .= " OR tr.notes LIKE " . $searchString;
            $sql .= " OR tsb.name LIKE " . $searchString;
            $sql .= " OR u.fullname LIKE " . $searchString;
            $sql .= " OR tr.description  LIKE " . $searchString . ")";
        }

        $query = qry($sql);
        $totalFiltered = count($query);

        $sql .= " ORDER BY " . $columns[$request->order[0]['column']] . "   " . $request->order[0]['dir'] . " LIMIT " . $request->start . " ," . $request->length . "   ";  
        $query = qry($sql);

        $data = array();

        $cnts = $request->start + 1;
        foreach ($query as $row) {
            $view = ""; $delete = ""; $edit = "";  $viewFileBtn = '';
            if($row->transaction_type==constants('transaction_type.Credit')){
                $fontcolor = "green";
            }
            else
            {
                $fontcolor = "black";
            }

            $transaction_type = "<br><span class='shadow-none badge badge-".constants('transaction_type_list.'.$row->transaction_type.'.classhtml')."' >".constants('transaction_type_list.'.$row->transaction_type.'.name2')."</span>";

            $nestedData = array();
            $nestedData[] = $cnts;
            $nestedData[] = "<b style='color:".$fontcolor."'> &#x20B9; ".$row->amount."</b>";
            $nestedData[] = date('d-M-Y', strtotime($row->transaction_date));
            $nestedData[] = $row->tsbname."<br><b>".$row->abname."</b>"."<br>".$row->ufullname;
            $nestedData[] = $transaction_type;

            if($row->anybillno_document!=''){
                $viewFileBtn = "<br><a target='_blank' href='".sendPath().constants('dir_name.bill').'/'.$row->anybillno_document."'><span class='shadow-none badge badge-info' >file</span></a>";
            }

            $nestedData[] = $row->anybillno.$viewFileBtn;
            $nestedData[] = $row->description;

            if(is_allowedHtml('roleclass_view_btn_transactiondetails')==true){
                //$view = "<a data-anybillno_document='".$row->anybillno_document."' data-billno='".$row->anybillno."' class='btn btn-primary btn-sm viewit' data-editid='".$row->id."' data-transactionid='".$row->transaction_uuid."'><i class='fas fa-eye'></i></a>";
            }
            if(is_allowedHtml('roleclass_delete_btn_transactiondetails')==true){
                //$delete = "<a data-anybillno_document='".$row->anybillno_document."' class='btn btn-danger btn-rounded btn-sm deleteit' data-editid='".$row->id."' data-transactionid='".$row->transaction_uuid."'><i class='fas fa-trash'></i></a>";
            }
            if(is_allowedHtml('roleclass_edit_btn_transactiondetails')==true){
                 $edit = "<a href='".route('edit-transaction').'?editid='.$row->id."&transactionid=".$row->transaction_uuid."' class='btn btn-info btn-rounded btn-sm' data-editid='".$row->id."' data-transactionid='".$row->transaction_uuid."'><i class='fas fa-edit'></i></a>";
            }

            $nestedData[] = $view." ".$delete." ".$edit;
            $nestedData['DT_RowId'] = "r" . $row->id;
            $data[] = $nestedData;
            $cnts++;
        }

        $json_data = array(
            "draw" => intval($request['draw']), 
            "recordsTotal" => intval($totalData), 
            "recordsFiltered" => intval($totalFiltered), 
            "data" => $data
        );

        echo json_encode($json_data);
    }


    public function getCustomerLastSubscriptionPurchaseValid(Request $request){
        $Id = trim(abs($request->id));
        $sql = 'SELECT u.* FROM '.$this->tbl.' u WHERE u.id='.$Id.' and u.is_active!="2" LIMIT 1';
        $dataCustomer = qry($sql);

        $dataLastSubscriptionPurchaseValid = SubscriptionPurchase::whereColumn('amount_used','<', 'maximum_discount_amount')->where('expired_datetime', '>', date('Y-m-d H:i:s'))->where('user_id', $Id)->where('is_active', constants('is_active_yes'))->first();
        if(!empty($dataLastSubscriptionPurchaseValid)){
            $response = ["msg" => "Can Not Add Another Subscription As This Customer's Last Subscription Has Not Been Expired/Used.", 'data' => $dataLastSubscriptionPurchaseValid, 'dataCustomer' => $dataCustomer, "success" => 0];
            return $response;
        }
        else
        {
            $response = ["msg" => "", 'data' => [], 'dataCustomer' => $dataCustomer, "success" => 1];
            return $response;
        }
    }


    public function pan(Request $request){
        return 1;
    }

    






}
