<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Session;
date_default_timezone_set('Asia/Kolkata');
use Carbon\Carbon;
use App\Models\Subscription;
use App\Models\SubscriptionFeature;
use App\Models\SubscriptionPurchase;
use App\Models\Customer;



class SubscriptionController extends Controller
{
    public $tbl = "tbl_subscriptions";
    public $tbl2 = "tbl_subscription_purchase";
    public $tbl3 = "tbl_subscription_features";

    public function index(Request $request){
    	if_allowedRoute('subscription-list');
        $data = ['tbl' => $this->tbl, 'control' => 'Subscription'];
        return view('admin.offer.subscription.list')->with($data);
    }

    public function subscriptionFeatureIndex(Request $request){
        if_allowedRoute('subscription-feature-list');
        $data = ['tbl' => $this->tbl, 'control' => 'Subscription Feature' ];
        return view('admin.customfields.subscriptionfeature.list')->with($data);
    }

    public function purchaseIndex(Request $request){
        if_allowedRoute('subscription-purchase-list');
        $data = ['tbl' => $this->tbl, 'control' => 'Subscription Purchase'];
        return view('admin.offer.subscription.purchaselist')->with($data);
    }

    public function addIndex(Request $request){
        $dataSubscriptionFeature = SubscriptionFeature::where('is_active', constants('is_active_yes'))->limit(250)->get();
        $data = ['tbl' => $this->tbl, 'control' => 'Subscription', 'dataSubscriptionFeature' => $dataSubscriptionFeature,];
        return view('admin.offer.subscription.add')->with($data);
    }

    public function viewEditIndex(Request $request){
        $id = isset($_GET['id']) ? trim(intval($_GET['id'])) : 0;
        $dataSubscriptionFeature = SubscriptionFeature::where('is_active', constants('is_active_yes'))->limit(250)->get();
        $dataCoupon = Subscription::where('id', $request->id)->first();
        $data = ['tbl' => $this->tbl, 'control' => 'Subscription', 'one' => $dataCoupon, 'dataSubscriptionFeature' => $dataSubscriptionFeature,];
        return view('admin.offer.subscription.edit')->with($data);
    }

    public function getedit(Request $request){
        $response = Subscription::where('id', $request->id)->first();
        return response()->json($response);
    }

    public function addSubscriptionPurchaseManuallyIndex(Request $request){
        $dataSubscriptionList = Subscription::where('is_active', constants('is_active_yes'))->limit(100)->orderBy('id','DESC')->get();
        $data = ['tbl' => $this->tbl, 'control' => 'Subscription Purchase Manually', 'dataSubscriptionList' => $dataSubscriptionList,];
        return view('admin.offer.subscription.subscriptionpurchaseadd')->with($data);
    }

    public function addSubscriptionPurchaseManually(Request $request) {

        $validator = Validator::make($request->all(), [ 
            'hid' => 'required',
            'created_at' => 'required',
            'user_idx' => 'required|integer|min:1',
            'subscription_id' => 'required|integer|min:1',
            'notes' => 'nullable|max:255',
        ]);

        if($validator->fails()) {
            Session::flash('msg', 'Please Check All Required Fields Properly.');
            Session::flash('cls', 'danger');
            return redirect()->back()->withErrors($validator)->withInput();     
        } 

        try {

            $dataCustomer = Customer::where('id',$request->user_idx)->where('is_active', constants('is_active_yes'))->first();
            if(empty($dataCustomer)) {
                Session::flash('msg', 'Customer Must Be Valid & Active');
                Session::flash('cls', 'danger');
                return redirect()->back();
            }


            $dataSubscription = Subscription::where('id', $request->subscription_id)->where('is_active', constants('is_active_yes'))->first();
            if(empty($dataSubscription)){
                Session::flash('msg', "Subscription Must Be Valid & Available.");
                Session::flash('cls', 'danger');
                return redirect()->back();
            }

            $dataLastSubscriptionPurchaseValid = SubscriptionPurchase::whereColumn('amount_used','<', 'maximum_discount_amount')->where('expired_datetime', '>', date('Y-m-d H:i:s'))->where('user_id', $request->get('user_id'))->orderBy('id','DESC')->where('is_active', constants('is_active_yes'))->first();
            if(!empty($dataLastSubscriptionPurchaseValid)){
                Session::flash('msg', "Can Not Add Another Subscription As This Customer's Last Subscription Has Not Been Expired/Used.");
                Session::flash('cls', 'danger');
                return redirect()->back(); 
            }

           $addDataSubscriptionPurchaseManually = [
                    'transaction_number' => "ADD_".time().mt_rand(1000,9999),
                    'subscription_value' => $dataSubscription->subscription_value,
                    'user_id' => $request->user_idx,
                    'purchase_datetime' => date('Y-m-d H:i:s'),
                    'notes' => isset($request->notes) ? $request->notes : NULL,
                    'admin_id' => Session::get("adminid"),
                    'is_active' => constants('is_active_yes'),
                    'subscription_shortname' => $dataSubscription->subscription_shortname,
                    'subscription_validity_months' => $dataSubscription->subscription_validity_months,
                    'subscription_title' => $dataSubscription->subscription_title,
                    'subscription_description' => $dataSubscription->subscription_description,
                    'subscription_terms' => $dataSubscription->subscription_terms,
                    'discount_type' => $dataSubscription->discount_type,
                    'min_order_value' => $dataSubscription->min_order_value,
                    'discount_value_min' => $dataSubscription->discount_value_min,
                    'discount_value_max' => $dataSubscription->discount_value_max,
                    'maximum_discount_perorder' => $dataSubscription->maximum_discount_perorder,
                    'maximum_discount_amount' => $dataSubscription->maximum_discount_amount,
                    'subscription_feature_ids' => $dataSubscription->subscription_feature_ids,
                    'amount_used' => 0,
                    'expired_datetime' => date('Y-m-d', strtotime(date('Y-m-d') . ' +'.$dataSubscription->subscription_validity_months.' month')),
                    'is_manually_added' => 1,
                    'subscription_id' => $request->subscription_id,
                ];

            SubscriptionPurchase::create($addDataSubscriptionPurchaseManually);

            Session::flash('msg', 'Subscription Purchase Added Successfully!');
            Session::flash('cls', 'success');
            return redirect()->back();

        } catch (\Exception $e) {
            Log::error($e);
            Session::flash('msg', '"Error! Something Went Wrong."');
            Session::flash('cls', 'danger');
            return redirect()->back();
        }
    }


    public function getdata(Request $request){
        $columns = array(          
            0 => 'sp.id',
            1 => 'sp.subscription_shortname',
            2 => 'sp.subscription_title',
            3 => 'sp.created_at',
            4 => 'sp.is_active',
            5 => 'sp.created_at',
        );

        $sql = "select sp.* from ".$this->tbl." sp WHERE sp.is_active!='2' ";

        $query = qry($sql);
        $totalData = count($query);
        $totalFiltered = $totalData;

        if (!empty($request['search']['value'])) {   
            $searchString = "'%" . str_replace(",", "','", $request['search']['value']) . "%'"; 
            $sql .= " and ( sp.subscription_shortname LIKE " . $searchString;
            $sql .= " OR sp.subscription_description LIKE " . $searchString;
            $sql .= " OR sp.subscription_title LIKE " . $searchString;
            $sql .= " OR sp.discount_value_min LIKE " . $searchString;
            $sql .= " OR sp.discount_value_max LIKE " . $searchString;
            $sql .= " OR sp.min_order_value  LIKE " . $searchString;
            $sql .= " OR sp.subscription_terms LIKE " . $searchString . ")";
        }

        $query = qry($sql);
        $totalFiltered = count($query);

        $sql .= " ORDER BY " . $columns[$request->order[0]['column']] . "   " . $request->order[0]['dir'] . " LIMIT " . $request->start . " ," . $request->length . "   ";  
        $query = qry($sql);

        $data = array();

        $cnts = $request->start + 1;

        foreach ($query as $row) {
            $status = "";  $edit = ""; $details = ''; 

            $discount_type_status = "<br><span class='shadow-none badge badge-info' >".constants('discount_type.'.$row->discount_type.'.name')."</span>";

            if(is_allowedHtml('anything')==true){
                $edit = "<a class='btn btn-rounded btn-primary editit' href='".route('subscription-view')."?id=".$row->id."' title='edit'><i class='fas fa-pen-square'></i></a>";
            }

            if(is_allowedHtml('anything')==true){
                if ($row->is_active == constants('is_active_yes')) {
                $status = "<a class='btn btn-success btn-sm change_status_confirm' data-id='".$row->id."' data-val='1'>ACTIVE</a>";
                } else {
                $status = "<a class='btn btn-danger btn-sm change_status_confirm' data-id='".$row->id."' data-val='0'>DEACTIVE</a>";
                }
            }

            $details .= "Purchase Amount : ".$row->subscription_value;
            $details .= "<br>Validity : ".$row->subscription_validity_months. " Month(s)";
            $details .= "<br>Minimum Discount : ".$row->discount_value_min." ".constants('discount_type.'.$row->discount_type.'.sign');
            $details .= "<br>Maximum Discount : ".$row->discount_value_max." ".constants('discount_type.'.$row->discount_type.'.sign');
            $details .= "<br>Maximum Discount Per Order : ".$row->maximum_discount_perorder." &#x20B9;";
            $details .= "<br>Maximum Discount OverAll : ".$row->maximum_discount_amount." &#x20B9;";

            $nestedData = array();
            $nestedData[] = $cnts;
            $nestedData[] = $row->subscription_shortname.$discount_type_status;
            $nestedData[] = $row->subscription_title;
            $nestedData[] = $details;
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

    public function getdata_purchase(Request $request){
        $columns = array(          
            0 => 'spp.id',
            1 => 'spp.subscription_shortname',
            2 => 'spp.subscription_title',
            3 => 'spp.created_at',
            4 => 'spp.is_active',
            5 => 'spp.created_at',
        );

        $sql = "select spp.*,u.fullname as ufullname from ".$this->tbl2." spp LEFT JOIN tbl_users u ON u.id=spp.user_id WHERE spp.is_active!='2' ";

        $query = qry($sql);
        $totalData = count($query);
        $totalFiltered = $totalData;

        if (!empty($request['search']['value'])) {   
            $searchString = "'%" . str_replace(",", "','", $request['search']['value']) . "%'"; 
            $sql .= " and ( spp.subscription_shortname LIKE " . $searchString;
            $sql .= " OR spp.subscription_description LIKE " . $searchString;
            $sql .= " OR spp.subscription_title LIKE " . $searchString;
            $sql .= " OR spp.discount_value_min LIKE " . $searchString;
            $sql .= " OR spp.discount_value_max LIKE " . $searchString;
            $sql .= " OR spp.min_order_value  LIKE " . $searchString;
            $sql .= " OR spp.subscription_terms LIKE " . $searchString . ")";
        }

        $query = qry($sql);
        $totalFiltered = count($query);

        $sql .= " ORDER BY " . $columns[$request->order[0]['column']] . "   " . $request->order[0]['dir'] . " LIMIT " . $request->start . " ," . $request->length . "   ";  
        $query = qry($sql);

        $data = array();

        $cnts = $request->start + 1;

        foreach ($query as $row) {
            $status = "";  $edit = ""; $details = ''; $ufullname = ''; $detailsPurchase = '';

            $discount_type_status = "<br><span class='shadow-none badge badge-info' >".constants('discount_type.'.$row->discount_type.'.name')."</span>";


            if($row->user_id>0){
                $ufullname = "<br><a target='_blank' href='".route('view-customer')."/".$row->user_id."'><span class='shadow-none badge badge-info'>".$row->ufullname."</span></a>";
                $detailsPurchase .= "Purchase DateTime <br>".$row->purchase_datetime;
                $detailsPurchase .= "<br>Expired Date <br>".$row->expired_datetime;
            }


            $details .= "Purchased Amount : ".$row->subscription_value;
            $details .= "<br>Validity : ".$row->subscription_validity_months. " Month(s)";
            $details .= "<br>Minimum Discount : ".$row->discount_value_min." ".constants('discount_type.'.$row->discount_type.'.sign');
            $details .= "<br>Maximum Discount : ".$row->discount_value_max." ".constants('discount_type.'.$row->discount_type.'.sign');
            $details .= "<br>Maximum Discount Per Order : ".$row->maximum_discount_perorder." &#x20B9;";
            $details .= "<br>Maximum Discount OverAll : ".$row->maximum_discount_amount." &#x20B9;";

            $nestedData = array();
            $nestedData[] = $cnts;
            $nestedData[] = $row->subscription_shortname.$discount_type_status;
            $nestedData[] = $row->subscription_title;
            $nestedData[] = $details;
            $nestedData[] = $detailsPurchase.$ufullname;
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
            'subscription_shortname' => 'required|max:101',
            'discount_type' => 'required',
            'hid' => 'required',
            'status' => 'required',
            'subscription_value'=> 'required|numeric|between:1,9999999',
            'subscription_validity_months' => 'required|numeric',
            'min_order_value' => 'required',
            'discount_value_min' => 'required',
            'discount_value_max' => 'required|gt:discount_value_min',
            'maximum_discount_perorder' => 'required|numeric|between:1,9999999',
            'maximum_discount_amount' => 'required|numeric|between:1,9999999',
            'subscription_feature_ids' => 'present|array',
        ]);

        if($validator->fails() || !array_key_exists($request->discount_type, constants('discount_type'))) {
            Session::flash('msg', 'Please Check All Required Fields Properly.');
            Session::flash('cls', 'danger');
            return redirect()->back()->withErrors($validator)->withInput();     
        } 

        try {

            $countSubscription = Subscription::where('is_active', '!=', 2)->count();
            if($countSubscription >= 20){
                Session::flash('msg', 'Limit is Over of 20 Records. You Can Not Add New.');
                Session::flash('cls', 'danger');
                return redirect()->back();
            }

            if($request->discount_type==constants('discount_type.P.key') && $request->discount_value>80) {
                Session::flash('msg', 'Discount Value Must Be Less Than 80%.');
                Session::flash('cls', 'danger');
                return redirect()->back();
            }
            else if($request->discount_type==constants('discount_type.F.key')) {
                if($request->discount_value_max >= $request->min_order_value){
                    Session::flash('msg', 'Maximum Discount Amount Must Be Less Than Minimum Order Value.');
                    Session::flash('cls', 'danger');
                    return redirect()->back();
                }
                else if($request->discount_value_min >= $request->discount_value_max){
                    Session::flash('msg', 'Minimum Discount Amount Must Be Less Than Maximum Discount Amount.');
                    Session::flash('cls', 'danger');
                    return redirect()->back();
                } 
            }

            
            if(isset($request->is_default_bestvalue) && $request->is_default_bestvalue==1){
                Subscription::where('id', '>', 0)->update(["is_default_bestvalue" => 0]); 
                $is_default_bestvalue = 1;
            }
            else
            {
                $is_default_bestvalue = 0;
            }

            

            $insertData =  [
                "subscription_shortname" => $request->subscription_shortname,
                "subscription_description" => isset($request->subscription_description) ? $request->subscription_description : NULL,
                "subscription_terms" => isset($request->subscription_terms) ? $request->subscription_terms : NULL,
                "subscription_title" => isset($request->subscription_title) ? $request->subscription_title : NULL,
                "discount_type" => $request->discount_type,
                "is_active" => ($request->status==0) ? 0 : 1,
                "subscription_value" => floatval($request->subscription_value),
                "min_order_value" => floatval($request->min_order_value),
                "discount_value_min" => floatval($request->discount_value_min),
                "discount_value_max" => floatval($request->discount_value_max),
                "maximum_discount_perorder" => floatval($request->maximum_discount_perorder),
                "maximum_discount_amount" => floatval($request->maximum_discount_amount),
                "admin_id" => Session::get('adminid'),
                "subscription_validity_months" => intval($request->subscription_validity_months),
                "subscription_feature_ids" => implode(',', $request->subscription_feature_ids),
                "subscription_title" => isset($request->subscription_title) ? $request->subscription_title : NULL,
                "is_default_bestvalue" => $is_default_bestvalue,
            ];

            $lastInsertedData = Subscription::create($insertData);

            $countSubscription = Subscription::where('is_default_bestvalue', 1)->count();
            if($countSubscription==0){
                Subscription::where('id', $lastInsertedData->id)->update(["is_default_bestvalue" => 1]); 
            }
            Session::flash('msg', 'Subscription Created Successfully!');
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
            'subscription_shortname' => 'required|max:101',
            'discount_type' => 'required',
            'hid' => 'required|integer|min:1',
            'created_at' => 'required',
            'status' => 'required',
            'subscription_value'=> 'required|numeric|between:1,9999999',
            'subscription_validity_months' => 'required|integer|min:1',
            'min_order_value' => 'required',
            'discount_value_min' => 'required',
            'discount_value_max' => 'required|gt:discount_value_min',
            'maximum_discount_perorder' => 'required|numeric|between:1,9999999',
            'maximum_discount_amount' => 'required|numeric|between:1,9999999',
            'subscription_feature_ids' => 'present|array',
        ]);

        if($validator->fails() || !array_key_exists($request->discount_type, constants('discount_type'))) {  
            Session::flash('msg', 'Please Check All Required Fields Properly.');
            Session::flash('cls', 'danger');
            return redirect()->back()->withErrors($validator)->withInput();     
        } 

        try {

            if($request->discount_type==constants('discount_type.P.key') && $request->discount_value>80) {
                Session::flash('msg', 'Discount Value Must Be Less Than 80%.');
                Session::flash('cls', 'danger');
                return redirect()->back();
            }
            else if($request->discount_type==constants('discount_type.F.key')) {
                if($request->discount_value_max >= $request->min_order_value){
                    Session::flash('msg', 'Maximum Discount Amount Must Be Less Than Minimum Order Value.');
                    Session::flash('cls', 'danger');
                    return redirect()->back();
                }
                else if($request->discount_value_min >= $request->discount_value_max){
                    Session::flash('msg', 'Minimum Discount Amount Must Be Less Than Maximum Discount Amount.');
                    Session::flash('cls', 'danger');
                    return redirect()->back();
                } 
            }

            $countSubscription = Subscription::where('id', $request->hid)->where('created_at', $request->created_at)->count();
            if($countSubscription==0) {
                Session::flash('msg', 'Not Found to Update.');
                Session::flash('cls', 'danger');
                return redirect()->back();
            }


            if(isset($request->is_default_bestvalue) && $request->is_default_bestvalue==1){
                Subscription::where('id', '>', 0)->update(["is_default_bestvalue" => 0]); 
                $is_default_bestvalue = 1;
            }
            else
            {
                $is_default_bestvalue = 0;
            }


            $updateData =  [
                "subscription_shortname" => $request->subscription_shortname,
                "subscription_description" => isset($request->subscription_description) ? $request->subscription_description : NULL,
                "subscription_terms" => isset($request->subscription_terms) ? $request->subscription_terms : NULL,
                "subscription_title" => isset($request->subscription_title) ? $request->subscription_title : NULL,
                "discount_type" => $request->discount_type,
                "is_active" => ($request->status==0) ? 0 : 1,
                "subscription_value" => floatval($request->subscription_value),
                "min_order_value" => floatval($request->min_order_value),
                "discount_value_min" => floatval($request->discount_value_min),
                "discount_value_max" => floatval($request->discount_value_max),
                "maximum_discount_perorder" => floatval($request->maximum_discount_perorder),
                "maximum_discount_amount" => floatval($request->maximum_discount_amount),
                "subscription_validity_months" => intval($request->subscription_validity_months),
                "subscription_feature_ids" => implode(',', $request->subscription_feature_ids),
            ];

            $lastUpdatedData = Subscription::where('id', $request->hid)->update($updateData);
            $countSubscription = Subscription::where('is_default_bestvalue', 1)->count();
            if($countSubscription==0){
                Subscription::where('id', $request->hid)->update(["is_default_bestvalue" => 1]); 
            }
            Session::flash('msg', 'Subscription Updated Successfully!');
            Session::flash('cls', 'success');
            return redirect()->back();
            
        } catch (\Exception $e) {
            Log::error($e);
            Session::flash('msg', 'Please check Properly, Something Went Wrong. Please Try Again!!');
            Session::flash('cls', 'danger');
            return redirect()->back();
        }
    }

    public function subscriptionInDropDown(Request $request){
        $dataArray = [];
        try {
            $searchTerm = ''; 
            if(isset($request->searchTerm)) {
                $searchTerm = $request->searchTerm;
            }


            $dataCoupon = Subscription::where('is_active', constants('is_active_yes'))
            ->where(function($querySearch) use ($searchTerm) {
                    $querySearch->where('subscription_title','LIKE', '%'.$searchTerm.'%')
                            ->orwhere('subscription_description','LIKE', '%'.$searchTerm.'%')
                            ->orwhere('subscription_terms','LIKE', '%'.$searchTerm.'%')
                            ->orwhere('subscription_shortname','LIKE', '%'.$searchTerm.'%')
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
            'id' => 'required|integer|min:1',
        ]);
        if($validator->fails()) {  
            $response = ['msg' => 'Missing Value.', 'success' => 0,  ];
            return response()->json($response); 
        } 

        $updateData = [
            'is_active' => $request->status,
        ];

        Subscription::where('id',$request->id)->whereIn('is_active',constants('is_active'))->update($updateData);
        $response = ['msg' => 'Subscription Changed Successfully.', 'success' => 1,  ];
        return response()->json($response); 
    }

    public function changeSubscriptionDeleted(Request $request) {
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

        Subscription::where('id',$request->id)->whereIn('is_active',constants('is_active'))->update($updateData);
        $response = ['msg' => 'Subscription Deleted Successfully.', 'success' => 1, ];
        return response()->json($response);
    }


    public function getEditSubscriptionFeature(Request $request){
        $response = SubscriptionFeature::where('id', $request->id)->first();
        return response()->json($response);
    }

    public function getdata_subscriptionFeature(Request $request){

        $columns = array(          
            0 => 'sbf.id',
            1 => 'sbf.subscription_feature',
            2 => 'sbf.created_at',
        );
        $sql = "select sbf.* from ".$this->tbl3." sbf WHERE sbf.is_active!='2'";
        $query = qry($sql);
        $totalData = count($query);
        $totalFiltered = $totalData;

        if (!empty($request['search']['value'])) {   
            $searchString = "'%" . str_replace(",", "','", $request['search']['value']) . "%'"; 
            $sql .= " and ( sbf.subscription_feature LIKE " . $searchString;
            $sql .= " OR sbf.created_at LIKE " . $searchString . ")";
        }

        $query = qry($sql);
        $totalFiltered = count($query);

        $sql .= " ORDER BY " . $columns[$request->order[0]['column']] . "   " . $request->order[0]['dir'] . " LIMIT " . $request->start . " ," . $request->length . "   ";  
        $query = qry($sql);

        $data = array();

        $cnts = $request->start + 1;
        foreach ($query as $row) {
            $editBtn = "";
            $nestedData = array();
            $nestedData[] = $cnts;
            $nestedData[] = $row->subscription_feature;

            $editBtn = "<a style='cursor: pointer;' class='btn btn-primary btn-sm edititmain' data-id='".$row->id."'>Edit</a>";
            
            $nestedData[] = $editBtn;
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

    public function addOrUpdateSubscriptionFeature(Request $request) {
        try {

        if(!isset($request->hid) OR !is_numeric($request->hid) OR !isset($request->subscription_feature) OR $request->subscription_feature=='' OR !isset($request->status) OR !is_numeric($request->status)){
            $response = ['msg' => 'Please check Properly, Something Went Wrong. Please Try Again!!', 'success' => 0 ];
            return response()->json($response);
        }

        if($request->hid==0){

            $countSubscriptionFeature = SubscriptionFeature::where('is_active', '!=', 2)->count();
            if($countSubscriptionFeature >= 25){
                $response = ['msg' => 'Limit is Over of 25 Records. You Can Not Add New.', 'success' => 0 ];
                return response()->json($response);
            }

            $data = [
                'subscription_feature' => $request->subscription_feature,
                'is_active' => ($request->status==0) ? 0 : 1,
            ];
            
            $lastInsertedData = SubscriptionFeature::create($data);

            $response = ['msg' => 'Added Successfully', 'success' => 1 ];
            return response()->json($response);
        }
        else if($request->hid > 0)
        {
            $data = [
                'subscription_feature' => $request->subscription_feature,
                'is_active' => ($request->status==0) ? 0 : 1,
                'updated_at' => date('Y-m-d H:i:s'),
            ];
            SubscriptionFeature::where('id', $request->hid)->update($data);
            $response = ['msg' => 'Updated Successfully!', 'success' => 1 ];
            return response()->json($response);
        }
        else
        {
            $response = ['msg' => 'Bad Request By You.', 'success' => 0 ];
            return response()->json($response);
        }
        } catch (\Exception $e) {
            $response = ['msg' => 'Error! Something Went Wrong.', 'success' => 0 ];
            return response()->json($response);
        }
    }



















}