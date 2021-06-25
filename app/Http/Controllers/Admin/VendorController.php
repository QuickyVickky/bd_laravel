<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AccVendor;
date_default_timezone_set('Asia/Kolkata');
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Session;
use App\Models\AccTransactionSubCategory;
use App\Models\AccAccountBanks;

class VendorController extends Controller
{
    
    public $tbl = "acc_vendors";

    public function index(Request $request){
    	if_allowedRoute('vendor-list');
        $data = ['tbl' => $this->tbl, 'control' => 'Vendor'];
        return view('admin.account.vendor.list')->with($data);
    }

    public function viewIndex(Request $request){
        $id = isset($_GET['id']) ? trim(intval($_GET['id'])) : 0;
        $dataVendor = AccVendor::with('driver')->where('id', $request->id)->first();
        $transactionSubCategoryInDropDown = AccTransactionSubCategory::where('is_active', constants('is_active_yes'))->limit(250)->get();
        $accountBanksInDropDown = AccAccountBanks::where('is_active', constants('is_active_yes'))->limit(250)->get();
        $data = ['tbl' => $this->tbl, 'control' => 'View Vendor', 'one' => $dataVendor, 'transactionSubCategoryInDropDown' => $transactionSubCategoryInDropDown, 'accountBanksInDropDown' => $accountBanksInDropDown];
        return view('admin.account.vendor.view')->with($data);
    }

    public function getedit(Request $request){
        $response = AccVendor::where('id', $request->id)->first();
        return response()->json($response);
    }

    public function getdata(Request $request){
        $columns = array(          
            0 => 'vd.fullname',
            1 => 'vd.mobile',
            2 => 'vd.created_at',
        );

        $sql = "select vd.* from ".$this->tbl." vd WHERE vd.is_active!='2' ";

        $query = qry($sql);
        $totalData = count($query);
        $totalFiltered = $totalData;

        if (!empty($request['search']['value'])) {   
            $searchString = "'%" . str_replace(",", "','", $request['search']['value']) . "%'"; 
            $sql .= " and ( vd.fullname LIKE " . $searchString;
            $sql .= " OR vd.email LIKE " . $searchString;
            $sql .= " OR vd.vendor_about LIKE " . $searchString;
            $sql .= " OR vd.landmark LIKE " . $searchString;
            $sql .= " OR vd.address LIKE " . $searchString;
            $sql .= " OR vd.pincode LIKE " . $searchString;
            $sql .= " OR vd.city LIKE " . $searchString;
            $sql .= " OR vd.state LIKE " . $searchString;
            $sql .= " OR vd.lastname LIKE " . $searchString;
            $sql .= " OR vd.firstname LIKE " . $searchString;
            $sql .= " OR vd.mobile LIKE " . $searchString . ")";
        }

        $query = qry($sql);
        $totalFiltered = count($query);

        $sql .= " ORDER BY " . $columns[$request->order[0]['column']] . "   " . $request->order[0]['dir'] . " LIMIT " . $request->start . " ," . $request->length . "   ";  
        $query = qry($sql);

        $data = array();

        $cnts = $request->start + 1;

        foreach ($query as $row) {
        	$vendor_type_status = "<br><span class='shadow-none badge badge-info' >".constants('vendor_type.'.$row->vendor_type.'.name')."</span>";
            $view = "<a class='btn btn-info btn-rounded btn-sm' href='".route('view-vendor')."?id=".$row->id."' data-id='".$row->id."' title='view'><i class='fas fa-eye'></i></a>";
            $edit = '<a class="btn btn-rounded btn-outline-primary editit" data-id="'.$row->id.'" title="edit"><i class="fas fa-pen-square"></i></a>';

            $delete = ''; //'<a href="javascript:void(0)" class="btn btn-rounded btn-outline-danger deleteit" data-id="'.$row->id.'" title="delete this"><i class="far fa-trash-alt"></i></a>';

            $nestedData = array();
            $nestedData[] = $row->fullname.$vendor_type_status;
            $nestedData[] = $row->email."<br>". $row->mobile;
            $nestedData[] = $row->address."<br>".$row->city."-".$row->pincode;
            $nestedData[] = $edit." ".$view." ".$delete;
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
        $vendor_ids_Sql = '';
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
        if(isset($request->select_vendor_id) && $request->select_vendor_id!='' ){
            $vendor_ids_Sql =  " and tr.vendor_id IN(".$request->select_vendor_id.") ";
        }
        if(isset($request->accountid_from) && is_array($request->accountid_from) && !empty($request->accountid_from)){
            $accountid_from_Status = "'" . implode ( "', '", $request->accountid_from ) . "'";
            $accountbanks_ids_Sql =  " and tr.accountid_from IN(".$accountid_from_Status.") ";
        }
        if(isset($request->transaction_subcategory_id) && is_array($request->transaction_subcategory_id) && !empty($request->transaction_subcategory_id)){
            $transaction_subcategory_ids_Status = "'" . implode ( "', '", $request->transaction_subcategory_id ) . "'";
            $transaction_subcategory_ids_Sql =  " and tr.transaction_subcategory_id IN(".$transaction_subcategory_ids_Status.") ";
        }


        $sql = "select tr.*,tsb.name as tsbname,ab.name as abname,vd.fullname as vdfullname FROM acc_transactions tr INNER JOIN acc_accounts_or_banks ab ON ab.id=tr.accountid_from LEFT JOIN acc_transaction_subcategory tsb ON tsb.id=tr.transaction_subcategory_id LEFT JOIN acc_vendors vd ON vd.id=tr.vendor_id WHERE tr.is_active!='2' ".$transaction_date_Sql.$transaction_type_Sql.$accountbanks_ids_Sql.$transaction_subcategory_ids_Sql.$is_reviewed_Sql.$vendor_ids_Sql." ";


        $query = qry($sql);
        $totalData = count($query);
        $totalFiltered = $totalData;

        if (!empty($request['search']['value'])) {   
            $searchString = "'%" . str_replace(",", "','", $request['search']['value']) . "%'"; 
            $sql .= " and ( tr.amount LIKE " . $searchString;
            $sql .= " OR tr.anybillno LIKE " . $searchString;
            $sql .= " OR tr.notes LIKE " . $searchString;
            $sql .= " OR tsb.name LIKE " . $searchString;
            $sql .= " OR vd.fullname LIKE " . $searchString;
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
            $nestedData[] = $row->tsbname."<br><b>".$row->abname."</b>"."<br>".$row->vdfullname;
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

    public function Add_Or_Update(Request $request) {
        $sqlEmail = ""; $sqlMobile = "";

        $validator = Validator::make($request->all(), [ 
            'fullname' => 'required',
            'email' => 'nullable|email',
            'typehid' => 'required',
            'hid' => 'required',
            'is_active' => 'required',
            'vendor_type' => 'required',
        ]);

        if($validator->fails()) {  
            $response = ['msg' => 'Missing Value.', 'success' => 0,  ];
            return response()->json($response); 
        } 

         try {

            if(isset($request->email) && valid_email($request->email)==true){
                $sqlEmail = "OR (email='".$request->email."' and email!='') ";
            }

            if(isset($request->mobile) && $request->mobile!=''){
                $sqlMobile = "OR (mobile='".$request->mobile."' and mobile!='') ";
            }


            if($request->hid>0)
            {
            $sql = "select id from ".$this->tbl." WHERE (1=2 $sqlEmail $sqlMobile ) and id!='".$request->hid."' ";
            $count = qry($sql);

            if(!empty($count)){
            $response = ['msg' => 'This Email Or Mobile Number is Already Registered !', 'success' => 0 ];
            return response()->json($response);
            }


            $updateData = [
            //'vendor_type' => $request->vendor_type,
            'vendor_about' => isset($request->vendor_about) ? trim($request->vendor_about) : NULL,
            'fullname' => $request->fullname,
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => isset($request->email) ? trim($request->email) : NULL,
            'mobile' => isset($request->mobile) ? trim($request->mobile) : NULL,
            'updated_at' => date('Y-m-d H:i:s'),
            'address' => isset($request->address) ? trim($request->address) : NULL,
            'landmark' => isset($request->landmark) ? trim($request->landmark) : NULL,
            'country' => isset($request->country) ? trim($request->country) : '',
            'state' => isset($request->state) ? trim($request->state) : NULL,
            'city' => isset($request->city) ? trim($request->city) : NULL,
            'pincode' => isset($request->pincode) ? trim($request->pincode) : NULL,
            ];

            update_data($this->tbl, $updateData ,['id' => $request->hid ]);
            $response = ['msg' => $request->typehid.' Updated Successfully!', 'success' => 1 ];
            return response()->json($response);
            }
            else if($request->hid==0)
            {

            $sql = "select id from ".$this->tbl." WHERE 1=2 $sqlEmail $sqlMobile ";
            $count = qry($sql);

            if(!empty($count)){
            $response = ['msg' => 'This Email Or Mobile Number is Already Registered !', 'success' => 0 ];
            return response()->json($response);
            }

            $insertData = [
           	'vendor_type' => $request->vendor_type,
            'vendor_about' => isset($request->vendor_about) ? trim($request->vendor_about) : NULL,
            'fullname' => $request->fullname,
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => isset($request->email) ? trim($request->email) : NULL,
            'mobile' => isset($request->mobile) ? trim($request->mobile) : NULL,
            'admin_id' => Session::get('adminid'),
            'is_active'=> $request->is_active == 1 ? 1 : 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'address' => isset($request->address) ? trim($request->address) : NULL,
            'landmark' => isset($request->landmark) ? trim($request->landmark) : NULL,
            'country' => isset($request->country) ? trim($request->country) : 'India',
            'state' => isset($request->state) ? trim($request->state) : NULL,
            'city' => isset($request->city) ? trim($request->city) : NULL,
            'pincode' => isset($request->pincode) ? trim($request->pincode) : NULL,

            ];

            $lastinsertid = insert_data_id($this->tbl,$insertData);
                if($lastinsertid>0)
                {
                    $response = ['msg' => $request->typehid.' Added Successfully!', 'success' => 1 ];
                    return response()->json($response);
                }
                else
                {
                    $response = ['msg' => 'Something Went Wrong. Please Try Again!!', 'success' => 0 ];
                    return response()->json($response);
                }
            }
            else
            {
                $response = ['msg' => 'Something Went Wrong. Please Try Again!!', 'success' => 0 ];
                return response()->json($response);
            }
         } catch (\Exception $e) {
         	Log::error($e);
            $response = ['msg' => 'Please check Properly, Something Went Wrong. Please Try Again!!', 'success' => 0 ];
            return response()->json($e);
        }
    }

    public function vendorInDropDown(Request $request){
        $dataArray = [];
        try {
            $searchTerm = ''; $vendor_type = '';
            if(isset($request->searchTerm)) {
                $searchTerm = $request->searchTerm;
            }

            if(isset($request->vendor_type)) {
                $vendor_type = $request->vendor_type;
            }

            $dataVendor = AccVendor::where('is_active', constants('is_active_yes'))
            ->where(function($querySearch) use ($searchTerm) {
                    $querySearch->where('fullname','LIKE', '%'.$searchTerm.'%')
                            ->orwhere('email','LIKE', '%'.$searchTerm.'%')
                            ->orwhere('firstname','LIKE', '%'.$searchTerm.'%')
                            ->orwhere('lastname','LIKE', '%'.$searchTerm.'%')
                            ->orwhere('address','LIKE', '%'.$searchTerm.'%')
                            ->orwhere('landmark','LIKE', '%'.$searchTerm.'%')
                            ->orwhere('pincode','LIKE', '%'.$searchTerm.'%')
                            ->orwhere('mobile','LIKE', '%'.$searchTerm.'%');
            })
            ->where(function($queryVendorType) use ($vendor_type) {
                if($vendor_type!=''){
                    $queryVendorType->where('vendor_type', $vendor_type);
                }
            })
          	->orderBy('id','ASC')
          	->limit(constants('limit_in_dropdown25'))
          	->get();


            foreach ($dataVendor as $key => $value) {
                $dataArray[] = [
                "text" => $value->fullname." | ".$value->email." | ".$value->mobile." | ".$value->vendor_type,
                "id" => $value->id,
                ];
            }
            return response()->json($dataArray);
        } catch (\Exception $e) {
            return [];
        }
    }


    public function changeVendorDeleted(Request $request) {
        /*
		
        $request->validate([
            'status' => 'required|numeric',
            'id' => 'required|numeric',
        ]);
         try {

            $count1 = Bills::where('vendor_id', $request->id)->count();
            $count2 = 0;

            if(($count2+$count1)<1){
                $Vendor = Vendor::where('id', $request->id)->first();
                CreateLogsDeletedData($Vendor);
                AccVendor::whereIn('id',[ $request->id ])->delete();
                $response = ['msg' => 'Deleted Successfully !', 'success' => 1];
            }
            else
            {
                $response = ['msg' => 'Can Not Be Deleted!', 'success' => 0];
            }            return response()->json($response);
         } catch (\Exception $e) {
            $response = ['msg' => 'Something Went Wrong. Please Try Again!!', 'success' => 0 ];
            return response()->json($response);
        }*/
    }






















}
