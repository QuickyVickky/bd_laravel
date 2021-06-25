<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Session;
date_default_timezone_set('Asia/Kolkata');
use Carbon\Carbon;
use App\Models\Coupon;


class CouponController extends Controller
{
    
    public $tbl = "tbl_coupons";

    public function index(Request $request){
    	if_allowedRoute('coupon-list');
        $data = ['tbl' => $this->tbl, 'control' => 'Coupon'];
        return view('admin.offer.coupon.list')->with($data);
    }

    public function addIndex(Request $request){
        $data = ['tbl' => $this->tbl, 'control' => 'Coupon',];
        return view('admin.offer.coupon.add')->with($data);
    }

    public function viewEditIndex(Request $request){
        $id = isset($_GET['id']) ? trim(intval($_GET['id'])) : 0;
        $dataCoupon = Coupon::with('customer')->where('id', $request->id)->first();
        $data = ['tbl' => $this->tbl, 'control' => 'Coupon', 'one' => $dataCoupon,];
        return view('admin.offer.coupon.edit')->with($data);
    }

    public function getedit(Request $request){
        $response = Coupon::where('id', $request->id)->first();
        return response()->json($response);
    }

    public function getdata(Request $request){
        $columns = array(          
            0 => 'cp.id',
            1 => 'cp.coupon_code',
            2 => 'cp.coupon_title',
            3 => 'cp.min_order_value',
            4 => 'cp.start_datetime',
            5 => 'cp.is_active',
            6 => 'cp.created_at',
        );

        $sql = "select cp.* from ".$this->tbl." cp WHERE cp.is_active!='2' ";

        $query = qry($sql);
        $totalData = count($query);
        $totalFiltered = $totalData;

        if (!empty($request['search']['value'])) {   
            $searchString = "'%" . str_replace(",", "','", $request['search']['value']) . "%'"; 
            $sql .= " and ( cp.coupon_title LIKE " . $searchString;
            $sql .= " OR cp.coupon_description LIKE " . $searchString;
            $sql .= " OR cp.coupon_code LIKE " . $searchString;
            $sql .= " OR cp.start_datetime LIKE " . $searchString;
            $sql .= " OR cp.end_datetime LIKE " . $searchString;
            $sql .= " OR cp.min_order_value  LIKE " . $searchString;
            $sql .= " OR cp.coupon_terms LIKE " . $searchString . ")";
        }

        $query = qry($sql);
        $totalFiltered = count($query);

        $sql .= " ORDER BY " . $columns[$request->order[0]['column']] . "   " . $request->order[0]['dir'] . " LIMIT " . $request->start . " ," . $request->length . "   ";  
        $query = qry($sql);

        $data = array();

        $cnts = $request->start + 1;

        foreach ($query as $row) {
            $status = "";  $edit = ""; 

        	$discount_type_status = "<br><span class='shadow-none badge badge-info' >".constants('discount_type.'.$row->discount_type.'.name')."</span>";

            if(is_allowedHtml('anything')==true){
                $edit = "<a class='btn btn-rounded btn-primary editit' href='".route('coupon-view')."?id=".$row->id."' title='edit'><i class='fas fa-pen-square'></i></a>";
            }

            if(is_allowedHtml('anything')==true){
                if ($row->is_active == constants('is_active_yes')) {
                $status = "<a class='btn btn-success btn-sm change_status_confirm' data-id='".$row->id."' data-val='1'>ACTIVE</a>";
                } else {
                $status = "<a class='btn btn-danger btn-sm change_status_confirm' data-id='".$row->id."' data-val='0'>DEACTIVE</a>";
                }
            }

            $end_datetime = '...'; $expired_status = '';
            if($row->end_datetime!=''){
                $end_datetime = Carbon::parse($row->end_datetime)->format('d-m-Y')." ".Carbon::parse($row->end_datetime)->format('h:i:s A');
                if($row->end_datetime < date('Y-m-d H:i:s')){
                    $expired_status = "<br><span class='shadow-none badge badge-danger' >Expired</span>";
                }
            }

            $used_count_status = '';
            if($row->used_count > 0){
                $used_count_status = "<br><span class='shadow-none badge badge-dark' >Used - ".$row->used_count."</span>";
            }


            $nestedData = array();
            $nestedData[] = $cnts;
            $nestedData[] = $row->coupon_code.$discount_type_status;
            $nestedData[] = $row->coupon_title;
            $nestedData[] = $row->min_order_value.$used_count_status.$expired_status;
            $nestedData[] = "Starts From <br>".Carbon::parse($row->start_datetime)->format('d-m-Y')." ".Carbon::parse($row->start_datetime)->format('h:i:s A')."<br>to<br>".$end_datetime;
            $nestedData[] = $status;
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

    public function Add(Request $request) {
        $validator = Validator::make($request->all(), [ 
            'user_idx' => 'required|numeric',
            'coupon_code' => 'required|alpha_num|min:2',
            'discount_type' => 'required',
            'hid' => 'required',
            'status' => 'required',
            'applied_for' => 'required',
            'discount_value' => 'required',
            'min_order_value' => 'required',
            'maximum_discount' => 'required',
            'maximum_use_count' => 'required|integer|min:1',
            'start_datetime' => 'required|date_format:Y-m-d H:i:s',
            'applied_for_platform' => 'required',
            'maximum_use_count_peruser' => 'required|integer|min:1',
        ]);

        if($validator->fails() || !array_key_exists($request->discount_type, constants('discount_type'))) {  
            Session::flash('msg', 'Please Check All Required Fields Properly.');
            Session::flash('cls', 'danger');
            return redirect()->back()->withErrors($validator)->withInput();
        } 

        try {

            if($request->discount_type==constants('discount_type.P.key') && $request->discount_value>90) {
                Session::flash('msg', 'Discount Value Must Be Less Than 90%.');
                Session::flash('cls', 'danger');
                return redirect()->back();
            }
            else if($request->discount_type==constants('discount_type.F.key')) {
                $request->maximum_discount = $request->discount_value; 

                if($request->min_order_value <= $request->discount_value){
                    Session::flash('msg', 'Minimum Order Value Must Be Greater Than Discount Value For Flat Discount.');
                    Session::flash('cls', 'danger');
                    return redirect()->back();
                } 
            }

            $countCouponAvailable = Coupon::where('coupon_code', trim($request->coupon_code))->whereNotNull('coupon_code')->count();
            if($countCouponAvailable>0) {
                Session::flash('msg', trim($request->coupon_code).' Coupon Already Available.');
                Session::flash('cls', 'danger');
                return redirect()->back();
            }

            $insertData =  [
                "coupon_code" => strtoupper(trim($request->coupon_code)),
                "coupon_description" => isset($request->coupon_description) ? $request->coupon_description : NULL,
                "coupon_terms" => isset($request->coupon_terms) ? $request->coupon_terms : NULL,
                "coupon_title" => isset($request->coupon_title) ? $request->coupon_title : NULL,
                "discount_type" => $request->discount_type,
                "is_active" => ($request->status==0) ? 0 : 1,
                "start_datetime" => $request->start_datetime,
                "end_datetime" => isset($request->end_datetime) ? $request->end_datetime : NULL, 
                "user_id" => intval($request->user_idx),
                "applied_for" => $request->applied_for,
                "min_order_value" => floatval($request->min_order_value),
                "discount_value" => floatval($request->discount_value),
                "maximum_discount" => floatval($request->maximum_discount),
                "maximum_use_count" => intval($request->maximum_use_count),
                "admin_id" => Session::get('adminid'),
                "used_count" => 0,
                "applied_for_platform" => intval($request->applied_for_platform),
                "maximum_use_count_peruser" => intval($request->maximum_use_count_peruser),
            ];

            
            $lastInsertedData = Coupon::create($insertData);


            Session::flash('msg', 'Coupon Created Successfully!');
            Session::flash('cls', 'success');
            return redirect()->back();
        } catch (\Exception $e) {
            Log::error($e);
            Session::flash('msg', 'Please check Properly, Something Went Wrong. Please Try Again!!');
            Session::flash('cls', 'danger');
            return redirect()->back();
        }
    }

    public function Update(Request $request) {
        $validator = Validator::make($request->all(), [ 
            'user_idx' => 'required|numeric',
            //'coupon_code' => 'required',
            'discount_type' => 'required',
            'hid' => 'required|integer|min:1',
            'created_at' => 'required',
            'status' => 'required',
            'applied_for' => 'required',
            'discount_value' => 'required',
            'min_order_value' => 'required',
            'maximum_discount' => 'required',
            'maximum_use_count' => 'required|integer|min:1',
            'start_datetime' => 'required',
            'applied_for_platform' => 'required',
            'maximum_use_count_peruser' => 'required|integer|min:1',
        ]);

        if($validator->fails() || !array_key_exists($request->discount_type, constants('discount_type'))) {  
            Session::flash('msg', 'Please Check All Required Fields Properly.');
            Session::flash('cls', 'danger');
            return redirect()->back();
        } 

        try {


            if($request->discount_type==constants('discount_type.P.key') && $request->discount_value>90) {
                Session::flash('msg', 'Discount Value Must Be Less Than 90%.');
                Session::flash('cls', 'danger');
                return redirect()->back();
            }
            else if($request->discount_type==constants('discount_type.F.key')) {
                $request->maximum_discount = $request->discount_value; 

                if($request->min_order_value <= $request->discount_value){
                    Session::flash('msg', 'Minimum Order Value Must Be Greater Than Discount Value For Flat Discount.');
                    Session::flash('cls', 'danger');
                    return redirect()->back();
                } 
            }

            $countCoupon = Coupon::where('id', $request->hid)->where('created_at', $request->created_at)->count();
            if($countCoupon==0) {
                Session::flash('msg', 'Not Found to Update.');
                Session::flash('cls', 'danger');
                return redirect()->back();
            }

            

            $updateData =  [
                //"coupon_code" => strtoupper(trim($request->coupon_code)),
                "coupon_description" => isset($request->coupon_description) ? $request->coupon_description : NULL,
                "coupon_terms" => isset($request->coupon_terms) ? $request->coupon_terms : NULL,
                "coupon_title" => isset($request->coupon_title) ? $request->coupon_title : NULL,
                "discount_type" => $request->discount_type,
                "is_active" => ($request->status==0) ? 0 : 1,
                "start_datetime" => $request->start_datetime,
                "end_datetime" => isset($request->end_datetime) ? $request->end_datetime : NULL, 
                "user_id" => intval($request->user_idx),
                "applied_for" => $request->applied_for,
                "min_order_value" => floatval($request->min_order_value),
                "discount_value" => floatval($request->discount_value),
                "maximum_discount" => floatval($request->maximum_discount),
                "maximum_use_count" => intval($request->maximum_use_count),
                "applied_for_platform" => intval($request->applied_for_platform),
                "maximum_use_count_peruser" => intval($request->maximum_use_count_peruser),
            ];
            $lastUpdatedData = Coupon::where('id', $request->hid)->update($updateData);
            Session::flash('msg', 'Coupon Updated Successfully!');
            Session::flash('cls', 'success');
            return redirect()->back();
        } catch (\Exception $e) {
            Log::error($e);
            Session::flash('msg', 'Please check Properly, Something Went Wrong. Please Try Again!!');
            Session::flash('cls', 'danger');
            return redirect()->back();
        }
    }

    public function couponInDropDown(Request $request){
        $dataArray = [];
        try {
            $searchTerm = ''; 
            if(isset($request->searchTerm)) {
                $searchTerm = $request->searchTerm;
            }


            $dataCoupon = Coupon::where('is_active', constants('is_active_yes'))
            ->where(function($querySearch) use ($searchTerm) {
                    $querySearch->where('coupon_title','LIKE', '%'.$searchTerm.'%')
                            ->orwhere('coupon_description','LIKE', '%'.$searchTerm.'%')
                            ->orwhere('coupon_terms','LIKE', '%'.$searchTerm.'%')
                            ->orwhere('coupon_code','LIKE', '%'.$searchTerm.'%')
                            ->orwhere('discount_value','LIKE', '%'.$searchTerm.'%');
            })
          	->orderBy('id','ASC')
          	->limit(constants('limit_in_dropdown25'))
          	->get();

            foreach ($dataCoupon as $key => $value) {
                $dataArray[] = [
                "text" => $value->coupon_code,
                "id" => $value->id,
                ];
            }
            return response()->json($dataArray);
        } catch (\Exception $e) {
            return [];
        }
    }

    public function changeStatus(Request $request) {
        $validator = Validator::make($request->all(), [ 
            'status' => 'required',
            'id' => 'required|numeric',
        ]);
        if($validator->fails()) {  
            $response = ['msg' => 'Missing Value.', 'success' => 0,  ];
            return response()->json($response); 
        } 

        $updateData = [
            'is_active' => $request->status,
        ];

        Coupon::where('id',$request->id)->whereIn('is_active',constants('is_active'))->update($updateData);
        $response = ['msg' => 'Status Changed Successfully.', 'success' => 1,  ];
        return response()->json($response); 
    }

    public function changeCouponDeleted(Request $request) {
        $validator = Validator::make($request->all(), [ 
            'status' => 'required',
            'id' => 'required|integer|min:1',
        ]);

        if($validator->fails()) {  
            $response = ['msg' => 'Missing Value.', 'success' => 0, ];
            return response()->json($response); 
        } 

        $updateData = [
            'is_active' => 2,
        ];

        Coupon::where('id',$request->id)->whereIn('is_active',constants('is_active'))->update($updateData);
        $response = ['msg' => 'Coupon Deleted Successfully.', 'success' => 1, ];
        return response()->json($response);
    }



























}