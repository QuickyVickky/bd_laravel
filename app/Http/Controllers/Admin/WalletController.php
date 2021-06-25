<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Session;
date_default_timezone_set('Asia/Kolkata');
use Carbon\Carbon;
use App\Models\WalletTransaction;
use App\Models\WalletCreditList;
use App\Models\Customer;



class WalletController extends Controller
{
    public $tbl = "tbl_wallet_creditlist";
    public $tbl2 = "tbl_wallet_transaction";

    public function index(Request $request){
    	if_allowedRoute('wallet-transaction-list');
        $data = ['tbl' => $this->tbl, 'control' => 'Wallet Transaction List'];
        return view('admin.transaction.walletlist')->with($data);
    }


    public function walletCreditListIndex(Request $request){
        if_allowedRoute('walletcredit-list');
        $data = ['tbl' => $this->tbl, 'control' => 'WalletCredit List' ];
        return view('admin.customfields.walletcreditlist.list')->with($data);
    }


    

    public function addIndex(Request $request){
        $dataWalletCreditList = WalletCreditList::where('is_active', constants('is_active_yes'))->limit(250)->orderBy('id','DESC')->get();
        $data = ['tbl' => $this->tbl, 'control' => 'Wallet Credit', 'dataWalletCreditList' => $dataWalletCreditList,];
        return view('admin.transaction.walletadd')->with($data);
    }

    public function getdata(Request $request){
        $columns = array(          
            0 => 'wt.id',
            1 => 'wt.transaction_number',
            2 => 'u.fullname',
            3 => 'wt.transaction_credit',
            4 => 'wt.transaction_type',
            5 => 'wt.transaction_datetime',
            5 => 'wt.created_at',
        );

        $sql = "select wt.*,u.fullname as ufullname,u.business_name as ubusiness_name,u.mobile as umobile from ".$this->tbl2." wt INNER JOIN tbl_users u ON u.id=wt.user_id WHERE wt.is_active=".constants('is_active_yes')." ";

        $query = qry($sql);
        $totalData = count($query);
        $totalFiltered = $totalData;

        if (!empty($request['search']['value'])) {   
            $searchString = "'%" . str_replace(",", "','", $request['search']['value']) . "%'"; 
            $sql .= " and ( wt.transaction_number LIKE " . $searchString;
            $sql .= " OR wt.transaction_datetime LIKE " . $searchString;
            $sql .= " OR wt.transaction_credit LIKE " . $searchString;
            $sql .= " OR wt.transaction_amount LIKE " . $searchString;
            $sql .= " OR wt.notes LIKE " . $searchString;
            $sql .= " OR wt.order_id  LIKE " . $searchString;
            $sql .= " OR u.mobile  LIKE " . $searchString;
            $sql .= " OR u.fullname LIKE " . $searchString . ")";
        }

        $query = qry($sql);
        $totalFiltered = count($query);

        $sql .= " ORDER BY " . $columns[$request->order[0]['column']] . "   " . $request->order[0]['dir'] . " LIMIT " . $request->start . " ," . $request->length . "   ";  
        $query = qry($sql);

        $data = array();

        $cnts = $request->start + 1;

        foreach ($query as $row) {
            $view = "";  $view_order = "";  $ufullname = "";

            $transaction_type = "<br><span class='shadow-none badge badge-".constants('transaction_type_list.'.$row->transaction_type.'.classhtml')."' title=".$row->notes." >".constants('transaction_type_list.'.$row->transaction_type.'.name')."</span>";
                
            if($row->order_id>0){
                $view_order = "<br><a target='_blank' href='".route('view-order')."/".$row->order_id."'><span class='shadow-none badge badge-info'>Order</span></a>";
            }

            if($row->user_id>0){
                $ufullname = "<a target='_blank' href='".route('view-customer')."/".$row->user_id."'><span class='shadow-none badge badge-info'>".$row->ufullname."</span></a>";
            }

            $amount_used = '';
            if($row->transaction_amount>0){
                $amount_used = '<br> Amount Spent : '.$row->transaction_amount;
            }

            $nestedData = array();
            $nestedData[] = $cnts;
            $nestedData[] = $row->transaction_number.$view_order;
            $nestedData[] = $ufullname."<br>". $row->ubusiness_name."<br>". $row->umobile;
            $nestedData[] = "Wallet Credit : ".$row->transaction_credit.$amount_used;
            $nestedData[] = $transaction_type;
            $nestedData[] = Carbon::parse($row->created_at)->format('d-m-Y')."<br>".Carbon::parse($row->created_at)->format('h:i:s A');
            $nestedData[] = $view;
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


    public function addWalletCreditManually(Request $request) {

        $validator = Validator::make($request->all(), [ 
            'hid' => 'required',
            'created_at' => 'required',
            'user_idx' => 'required|integer|min:1',
            'wallet_credit'=> 'required|integer|between:1,80000',
            'notes' => 'nullable',
        ]);

        if($validator->fails()) {
            Session::flash('msg', 'Please Check All Required Fields Properly.');
            Session::flash('cls', 'danger');
            return redirect()->back()->withErrors($validator)->withInput();     
        } 

        try {

            $dataCustomer = Customer::where('id',$request->user_idx)->where('is_active', constants('is_active_yes'))->first(['wallet_credit']);
            if(empty($dataCustomer)) {
                Session::flash('msg', 'Customer Must Be Valid & Active');
                Session::flash('cls', 'danger');
                return redirect()->back();
            }

            $tenMinutueAgo = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . ' -10 minutes'));
            $oneMinutuePlus = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . ' +1 minutes'));

            $lastWalletTransaction = WalletTransaction::whereBetween('created_at', [$tenMinutueAgo, $oneMinutuePlus])->where('user_id',$request->user_idx)->orderBy('id','DESC')->first(['created_at']);

            if(!empty($lastWalletTransaction)){
                Session::flash('msg', 'Please, Wait At Least 10 Minutes Before Next Wallet Transaction For This Customer. Last Wallet Transaction Found '.$lastWalletTransaction->created_at.' .');
                Session::flash('cls', 'danger');
                return redirect()->back();
            }

            $dataWalletCreditList = WalletCreditList::where('is_active', constants('is_active_yes'))->where('wallet_credit', $request->wallet_credit)->first();


            $addDataWalletTransaction = [
                    'transaction_amount' => 0,
                    'transaction_credit' => $request->wallet_credit,
                    'transaction_type' => constants('transaction_type.Credit'),
                    'user_id' => $request->user_idx,
                    'transaction_datetime' => date('Y-m-d H:i:s'),
                    'transaction_number' => "ADD_".time().mt_rand(1000,9999),
                    'notes' => $request->notes,
                    'order_id' => 0,
                    'admin_id' => Session::get("adminid"),
                    'is_active' => constants('is_active_yes'),
                    'is_manually_added' => 1,
            ];

            $lastInsertedData = WalletTransaction::create($addDataWalletTransaction);

            $dataCustomer = Customer::where('id',$request->user_idx)->where('is_active', constants('is_active_yes'))->first(['wallet_credit']);

            Customer::where('id', $request->user_idx)->update(['wallet_credit' => floatval($dataCustomer->wallet_credit) + $addDataWalletTransaction['transaction_credit'] ]);

            Session::flash('msg', 'Total '.$addDataWalletTransaction['transaction_credit'].' Wallet Credit Added Successfully!');
            Session::flash('cls', 'success');
            return redirect()->back();

        } catch (\Exception $e) {
            Log::error($e);
            Session::flash('msg', '"Error! Something Went Wrong."');
            Session::flash('cls', 'danger');
            return redirect()->back();
        }
    }





    public function getEditWalletCredit(Request $request){
        $response = WalletCreditList::where('id', $request->id)->first();
        return response()->json($response);
    }

    public function getdata_walletCredit(Request $request){

        $columns = array(          
            0 => 'wcl.id',
            1 => 'wcl.wallet_amount',
            2 => 'wcl.wallet_credit',
            3 => 'wcl.created_at',
        );
        $sql = "select wcl.* from ".$this->tbl." wcl WHERE wcl.is_active!='2'";
        $query = qry($sql);
        $totalData = count($query);
        $totalFiltered = $totalData;

        if (!empty($request['search']['value'])) {   
            $searchString = "'%" . str_replace(",", "','", $request['search']['value']) . "%'"; 
            $sql .= " and ( wcl.wallet_amount LIKE " . $searchString;
            $sql .= " OR wcl.wallet_credit LIKE " . $searchString;
            $sql .= " OR wcl.created_at LIKE " . $searchString . ")";
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
            $nestedData[] = $row->wallet_amount;
            $nestedData[] = $row->wallet_credit;

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

    public function addOrUpdateWalletCredit(Request $request) {
        try {

        $validator = Validator::make($request->all(), [ 
            'hid' => 'required',
            'wallet_amount'=> 'required|numeric|between:1,99999',
            'wallet_credit'=> 'required|numeric|between:1,99999',
            'status' => 'required',
        ]);

        if($validator->fails()) {
            $response = ['msg' => 'Please Check Properly, Validation Failed', 'success' => 0 ];
            return response()->json($response);    
        }

        $countWalletAmountExists = WalletCreditList::where('wallet_amount', floatval($request->wallet_amount))->where('id', '!=', $request->hid)->count();
        if($countWalletAmountExists > 0){
            $response = ['msg' => floatval($request->wallet_amount).' Wallet Amount is Already Exists.', 'success' => 0 ];
            return response()->json($response);
        }


        if($request->hid==0){

            $countWalletCreditList = WalletCreditList::where('is_active', '!=', 2)->count();
            if($countWalletCreditList >= 50){
                $response = ['msg' => 'Limit is Over of 50 Records. You Can Not Add New.', 'success' => 0 ];
                return response()->json($response);
            }

            $data = [
                'wallet_amount' => floatval($request->wallet_amount),
                'wallet_credit' => floatval($request->wallet_credit),
                'is_active' => ($request->status==0) ? 0 : 1,
            ];
            
            $lastInsertedData = WalletCreditList::create($data);

            $response = ['msg' => 'Added Successfully', 'success' => 1 ];
            return response()->json($response);
        }
        else if($request->hid > 0)
        {
            $data = [
                'wallet_amount' => floatval($request->wallet_amount),
                'wallet_credit' => floatval($request->wallet_credit),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
            WalletCreditList::where('id', $request->hid)->update($data);
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