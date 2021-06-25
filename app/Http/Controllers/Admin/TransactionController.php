<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use App\Models\AccTransactionSubCategory;
use App\Models\Transaction;
use App\Models\AccAccountBanks;
date_default_timezone_set('Asia/Kolkata');
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Exports\JustExcelExport;
use Maatwebsite\Excel\Facades\Excel;

class TransactionController extends Controller
{
    public $tbl = "acc_transactions";

    public function index(Request $request){
        if_allowedRoute('transaction-list');
        $transactionSubCategoryInDropDown = AccTransactionSubCategory::where('is_active', constants('is_active_yes'))->limit(250)->get();
        $accountBanksInDropDown = AccAccountBanks::where('is_active', constants('is_active_yes'))->limit(250)->get();
        $data = ['tbl' => $this->tbl, 'control' => 'Transaction List', 'transactionSubCategoryInDropDown' => $transactionSubCategoryInDropDown, 'accountBanksInDropDown' => $accountBanksInDropDown ];
        return view('admin.account.transaction.list')->with($data);
    }

    public function addIndex(Request $request){
        $type = isset($_GET['type']) ? trim($_GET['type']) : 'Expense';
        $dataAccAccountBanks = AccAccountBanks::where('is_active',constants('is_active_yes'))->orderBy('id','DESC')->limit(100)->get();
        $data = ['control' => 'Add Transaction', 'dataAccAccountBanks' => $dataAccAccountBanks, 'type' => $type ];
        return view('admin.account.transaction.add')->with($data);
    }

    public function editIndex(Request $request){
        $dataAccAccountBanks = AccAccountBanks::where('is_active',constants('is_active_yes'))->orderBy('id','DESC')->limit(100)->get();
        $editid = isset($_GET['editid']) ? trim(intval($_GET['editid'])) : 0;
        $transactionid = isset($_GET['transactionid']) ? trim($_GET['transactionid']) : 0;
        $dataTransaction = 
        Transaction::with('vendor','accountbanks','accounttransferredto','transaction_subcategory')
        ->where('id', $editid)
        ->where('transaction_uuid', $transactionid)
        ->where('is_active',constants('is_active_yes'))
        ->first();

        $data = ['control' => 'Edit/View Transaction', 'one' => $dataTransaction, 'dataAccAccountBanks' => $dataAccAccountBanks ];
        //dde($dataTransaction);

        return view('admin.account.transaction.edit')->with($data);
    }

    public function getdata(Request $request){

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
        if(isset($request->select_vendor_id) && is_array($request->select_vendor_id) && !empty($request->select_vendor_id)){
            $select_vendor_ids_Status = "'" . implode ( "', '", $request->select_vendor_id ) . "'";
            $vendor_ids_Sql =  " and tr.vendor_id IN(".$select_vendor_ids_Status.") ";
        }
        if(isset($request->accountid_from) && is_array($request->accountid_from) && !empty($request->accountid_from)){
            $accountid_from_Status = "'" . implode ( "', '", $request->accountid_from ) . "'";
            $accountbanks_ids_Sql =  " and tr.accountid_from IN(".$accountid_from_Status.") ";
        }
        if(isset($request->transaction_subcategory_id) && is_array($request->transaction_subcategory_id) && !empty($request->transaction_subcategory_id)){
            $transaction_subcategory_ids_Status = "'" . implode ( "', '", $request->transaction_subcategory_id ) . "'";
            $transaction_subcategory_ids_Sql =  " and tr.transaction_subcategory_id IN(".$transaction_subcategory_ids_Status.") ";
        }


        $sql = "select tr.*,tsb.name as tsbname,ab.name as abname,vd.fullname as vdfullname,u.fullname as ufullname,vd.vendor_type as vdvendor_type FROM ".$this->tbl." tr INNER JOIN acc_accounts_or_banks ab ON ab.id=tr.accountid_from LEFT JOIN acc_transaction_subcategory tsb ON tsb.id=tr.transaction_subcategory_id LEFT JOIN acc_vendors vd ON vd.id=tr.vendor_id LEFT JOIN tbl_users u ON u.id=tr.user_id WHERE tr.is_active!='2' ".$transaction_date_Sql.$transaction_type_Sql.$accountbanks_ids_Sql.$transaction_subcategory_ids_Sql.$is_reviewed_Sql.$vendor_ids_Sql." ";


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
            $view = ""; $delete = ""; $edit = "";  $viewFileBtn = ''; $vdfullname = ''; $ufullname = '';

            $view_order = "";
            if($row->order_id>0){
                $view_order = "<br><a target='_blank' href='".route('view-order')."/".$row->order_id."'><span class='shadow-none badge badge-dark'>View Order</span></a>";
            }

            if($row->transaction_type==constants('transaction_type.Credit')){
                $fontcolor = "green";
            }
            else
            {
                $fontcolor = "black";
            }

            $transaction_type = "<br><span class='shadow-none badge badge-".constants('transaction_type_list.'.$row->transaction_type.'.classhtml')."' >".constants('transaction_type_list.'.$row->transaction_type.'.name2')."</span>";

            if($row->vdfullname!=''){
                $vdfullname = "<br><a target='_blank' href='".route('view-vendor')."?id=".$row->vendor_id."'><span class='shadow-none badge badge-info'>".$row->vdfullname."</span></a><br><span class='shadow-none badge badge-secondary'>".$row->vdvendor_type."</span>";
            }
            else if($row->user_id>0){
                $ufullname = "<br><a target='_blank' href='".route('view-customer')."/".$row->user_id."'><span class='shadow-none badge badge-info'>".$row->ufullname."</span></a>";
            }

            
            $nestedData = array();
            $nestedData[] = $cnts;
            $nestedData[] = "<b style='color:".$fontcolor."'> &#x20B9; ".$row->amount."</b>";
            $nestedData[] = date('d-M-Y', strtotime($row->transaction_date));
            $nestedData[] = $row->tsbname."<br><b>".$row->abname."</b>".$vdfullname.$ufullname.$view_order;
            $nestedData[] = $transaction_type;


            if($row->anybillno_document!=''){
                $viewFileBtn = "<br><a target='_blank' href='".sendPath().constants('dir_name.bill').'/'.$row->anybillno_document."'><span class='shadow-none badge badge-info' >file</span></a>";
            }

            $nestedData[] = $row->anybillno.$viewFileBtn;
            $nestedData[] = $row->description;

            if(is_allowedHtml('roleclass_view_btn_transactiondetails')==true){
                $view = "<a data-anybillno_document='".$row->anybillno_document."' data-billno='".$row->anybillno."' class='btn btn-primary btn-sm viewit' data-editid='".$row->id."' data-transactionid='".$row->transaction_uuid."'><i class='fas fa-eye'></i></a>";
            }
            if(is_allowedHtml('roleclass_delete_btn_transactiondetails')==true){
                $delete = "<a data-anybillno_document='".$row->anybillno_document."' class='btn btn-danger btn-rounded btn-sm deleteit' data-editid='".$row->id."' data-transactionid='".$row->transaction_uuid."'><i class='fas fa-trash'></i></a>";
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

    public function Add(Request $request) {
        $validator = Validator::make($request->all(), [ 
            'is_active'=>'required',
            'transaction_type'=>'required',
            'transaction_date'=>'required|date',
            'amount'=>'required|between:0,99999999999.99',
            'hid'=>'required',
            'accountid_from' => 'required|numeric',
            'payment_method' => 'required',
            'select_transaction_subcategory_from_select2_dropdownid' => 'required|numeric',
            'anybillno_document'=> 'nullable|mimes:pdf,jpg,jpeg,png,bmp,csv,xlt,xls,xlsx,xlsb,xlsm,xltx,xltm,txt,rtf,docx|max:1024',
        ]);

        if($validator->fails()) {  
            Session::flash('msg', 'Please Check All Required Fields Properly.');
            Session::flash('cls', 'danger');
            return redirect()->back()->withErrors($validator)->withInput();      
        } 

        try {

            $bankTransferToId = isset($request->select_accountid_transferredto_from_select2_dropdownid) ? $request->select_accountid_transferredto_from_select2_dropdownid : NULL;

            $count1 = AccTransactionSubCategory::where('id', $request->select_transaction_subcategory_from_select2_dropdownid)->where('is_active', constants('is_active_yes'))->count();
            $count2 = AccAccountBanks::where('id', $request->accountid_from)->where('is_active', constants('is_active_yes'))->count();



            if($bankTransferToId==$request->accountid_from || $count2==0 || $count1==0)  {  
                Session::flash('msg', 'Value Missing.');
                Session::flash('cls', 'danger');
                return redirect()->back();      
            } 

            /*---bank transfer transaction starts ---*/
            if($bankTransferToId!=NULL && $request->accountid_from>0)
            {
                $insertDataV1 = [
                'transaction_uuid' => random_text(10).uniqid().mt_rand(1000,9999),
                'transaction_date' => Carbon::parse($request->transaction_date)->format('Y-m-d H:i:s'),
                'amount' => $request->amount,
                'transaction_subcategory_id' => $request->select_transaction_subcategory_from_select2_dropdownid == constants('Transfer_From_Bank_Subcategory_Id') ? constants('Transfer_From_Bank_Subcategory_Id') : constants('Transfer_To_Bank_Subcategory_Id'),
                'transaction_type' => $request->transaction_type == 'Cr' ? 'Cr' : 'Dr',
                'description' => isset($request->description) ? $request->description : NULL,
                'anybillno' => isset($request->anybillno) ? $request->anybillno : NULL,
                'admin_id' => Session::get("adminid"),
                'is_active' => ($request->is_active==0) ? 0 : 1,
                'payment_method' => $request->payment_method,
                'vendor_id' => isset($request->select_vendor_from_select2_dropdown_id) ? $request->select_vendor_from_select2_dropdown_id : NULL,
                'user_id' => NULL,
                'invoice_id' => NULL,
                'is_reviewed' => 0,
                'notes' => 'Bank Transfer Transaction',
                'accountid_from' => $request->accountid_from,
                'accountid_transferredto' => $bankTransferToId,
                'is_editable'=> constants('is_editable_no'),
                ];
                if($request->hasFile('anybillno_document')){
                    $insertDataV1['anybillno_document'] = UploadImage($request->file('anybillno_document'), constants('dir_name.bill'), 'bill');
                }
                $lastData = Transaction::create($insertDataV1);


                $insertDataV2 = [
                'transaction_uuid' => random_text(10).uniqid().mt_rand(1000,9999),
                'transaction_date' => Carbon::parse($request->transaction_date)->format('Y-m-d H:i:s'),
                'amount' => $request->amount,
                'transaction_subcategory_id' => $request->select_transaction_subcategory_from_select2_dropdownid == constants('Transfer_From_Bank_Subcategory_Id') ? constants('Transfer_To_Bank_Subcategory_Id') : constants('Transfer_From_Bank_Subcategory_Id'),
                'transaction_type' => $request->transaction_type == 'Cr' ? 'Dr' : 'Cr',
                'description' => isset($request->description) ? $request->description : NULL,
                'anybillno' => isset($request->anybillno) ? $request->anybillno : NULL,
                'admin_id' => Session::get("adminid"),
                'is_active' => ($request->is_active==0) ? 0 : 1,
                'payment_method' => $request->payment_method,
                'vendor_id' => isset($request->select_vendor_from_select2_dropdown_id) ? $request->select_vendor_from_select2_dropdown_id : NULL,
                'user_id' => NULL,
                'invoice_id' => NULL,
                'is_reviewed' => 0,
                'notes' => 'Bank Transfer Transaction',
                'accountid_from' => $bankTransferToId,
                'accountid_transferredto' => $request->accountid_from,
                'is_editable'=> constants('is_editable_no'),
                ];
                $lastData = Transaction::create($insertDataV2);
            }
            /*---bank transfer transaction ends ---*/
            /*---normla transaction starts ---*/
            else
            {
                $insertData = [
                'transaction_uuid' => random_text(10).uniqid().mt_rand(1000,9999),
                'transaction_date' => Carbon::parse($request->transaction_date)->format('Y-m-d H:i:s'),
                'transaction_subcategory_id' => $request->select_transaction_subcategory_from_select2_dropdownid,
                'amount' => $request->amount,
                'transaction_type' => $request->transaction_type,
                'description' => isset($request->description) ? $request->description : NULL,
                'anybillno' => isset($request->anybillno) ? $request->anybillno : NULL,
                'admin_id' => Session::get("adminid"),
                'is_active' => ($request->is_active==0) ? 0 : 1,
                'payment_method' => $request->payment_method,
                'vendor_id' => isset($request->select_vendor_from_select2_dropdown_id) ? $request->select_vendor_from_select2_dropdown_id : NULL,
                'user_id' => NULL,
                'invoice_id' => NULL,
                'is_reviewed' => 0,
                'notes' => NULL,
                'accountid_from' => $request->accountid_from,
                'accountid_transferredto' => NULL,
                'is_editable'=> constants('is_editable_yes'),
                ];
                if($request->hasFile('anybillno_document')){
                    $insertData['anybillno_document'] = UploadImage($request->file('anybillno_document'), constants('dir_name.bill'), 'bill');
                }
                $lastData = Transaction::create($insertData);
            }
            /*---normla transaction ends ---*/
            
            if($lastData->id > 0){
                Session::flash('msg', 'Added Successfully!');
                Session::flash('cls', 'success');
            }
            else
            {
                Session::flash('msg', 'Something Went Wrong. Please Try Again!!');
                Session::flash('cls', 'danger');
            }
            return redirect()->back();
        } catch (\Exception $e) {
            Log::error($e);
            Session::flash('msg', 'Error, Something Went Wrong.');
            Session::flash('cls', 'danger');
            return redirect()->back()->withError($e->getMessage());
        }
    }

    public function Update(Request $request) {
        $validator = Validator::make($request->all(), [ 
            'is_active'=>'required',
            'transaction_type'=>'required',
            'transaction_date'=>'required|date',
            'amount'=>'required|between:0,99999999999.99',
            'hid'=>'required|numeric',
            'hidtransactionid' => 'required',
            'accountid_from' => 'required|numeric',
            'payment_method' => 'required',
            'select_transaction_subcategory_from_select2_dropdownid' => 'required|numeric',
        ]);

        if($validator->fails()) {  
            Session::flash('msg', 'Please Check All Required Fields Properly.');
            Session::flash('cls', 'danger');
            return redirect()->back()->withErrors($validator)->withInput();      
        } 

        try {
        
            $updateData = [
                'transaction_date' => Carbon::parse($request->transaction_date)->format('Y-m-d H:i:s'),
                'transaction_subcategory_id' => $request->select_transaction_subcategory_from_select2_dropdownid,
                'amount' => $request->amount,
                'transaction_type' => $request->transaction_type,
                'description' => isset($request->description) ? $request->description : NULL,
                'anybillno' => isset($request->anybillno) ? $request->anybillno : NULL,
                'payment_method' => $request->payment_method,
                'vendor_id' => isset($request->select_vendor_from_select2_dropdown_id) ? $request->select_vendor_from_select2_dropdown_id : NULL,
                'accountid_from' => $request->accountid_from,
            ];

            if($request->hasFile('anybillno_document')){
                $updateData['anybillno_document'] = UploadImage($request->file('anybillno_document'), constants('dir_name.bill'), 'bill');
                $request->existing_img = isset($request->existing_img) ? $request->existing_img : '0';
                DeleteFile($request->existing_img, constants('dir_name.bill'));
            }

            Transaction::where('id',$request->hid)->where('transaction_uuid',$request->hidtransactionid)->update($updateData);
            Session::flash('msg', ' Updated Successfully!');
            Session::flash('cls', 'success');
            return redirect()->back();

        } catch (\Exception $e) {
            Log::error($e);
            return redirect()->back()->withError($e->getMessage());
        }
    }

    public function deleteTransaction(Request $request) {
        $validator = Validator::make($request->all(), [ 
            'hid' => 'required|numeric',
            'hidtransactionid' => 'required',
        ]);

        if($validator->fails() || is_allowedHtml('roleclass_delete_btn_transactiondetails')==false ) {  
            $response = ['msg' => 'Missing Value.', 'success' => 0];
            return response()->json($response); 
        } 

        $updateData = [
            'is_active' => '2',
        ];
        Transaction::where('id',$request->hid)->where('transaction_uuid',$request->hidtransactionid)->update($updateData);
        $request->existing_img = isset($request->existing_img) ? $request->existing_img : '0';
        DeleteFile($request->existing_img, constants('dir_name.bill'));
        $response = ['msg' => 'Deleted Successfully', 'success' => 1 ];
        return response()->json($response);
    }
    
    public function exportExcelTransaction(Request $request) {
        ini_set('memory_limit','24M');
        ini_set('max_execution_time', 600);

        $validator = Validator::make($request->all(), [ 
            'filter_global_transaction_date' => 'required',
        ]);
        if($validator->fails()) {  
            Session::flash('cls', 'danger');
            Session::flash('msg', 'Missing Required Value.');
            return redirect()->back();
        }


        $transaction_type_Sql = '';
        $accountbanks_ids_Sql = '';
        $transaction_subcategory_ids_Sql = '';
        $transaction_date_Sql = '';
        $vendor_ids_Sql = '';
        $is_reviewed_Sql = '';
        $customer_id_Sql = '';

        if(isset($request->is_reviewed) && is_array($request->is_reviewed) && !empty($request->is_reviewed)){
            $is_reviewed_Status = "'" . implode ( "', '", $request->is_reviewed ) . "'";
            $is_reviewed_Sql =  " and tr.is_reviewed IN(".$is_reviewed_Status.") ";
        }
        if(isset($request->filter_global_transaction_date) && $request->filter_global_transaction_date!=''){
            $date_start = explode(' - ', $request->filter_global_transaction_date);
            $stop_date = date('Y-m-d', strtotime($date_start[1] . ' +0 day'));
            $transaction_date_Sql = " and tr.transaction_date BETWEEN '".$date_start[0]."' and '".$stop_date."' ";
        }
        if(isset($request->filter_global_transaction_type) && is_array($request->filter_global_transaction_type) && !empty($request->filter_global_transaction_type)){
            $transaction_type_Status = "'" . implode ( "', '", $request->filter_global_transaction_type ) . "'";
            $transaction_type_Sql =  " and tr.transaction_type IN(".$transaction_type_Status.") ";
        }
        if(isset($request->select_vendor_from_select2_dropdown_id) && is_array($request->select_vendor_from_select2_dropdown_id) && !empty($request->select_vendor_from_select2_dropdown_id)){
            $select_vendor_ids_Status = "'" . implode ( "', '", $request->select_vendor_from_select2_dropdown_id ) . "'";
            $vendor_ids_Sql =  " and tr.vendor_id IN(".$select_vendor_ids_Status.") ";
        }
        if(isset($request->filter_global_accountid_from) && is_array($request->filter_global_accountid_from) && !empty($request->filter_global_accountid_from)){
            $accountid_from_Status = "'" . implode ( "', '", $request->filter_global_accountid_from ) . "'";
            $accountbanks_ids_Sql =  " and tr.accountid_from IN(".$accountid_from_Status.") ";
        }
        if(isset($request->filter_global_transaction_subcategory_id) && is_array($request->filter_global_transaction_subcategory_id) && !empty($request->filter_global_transaction_subcategory_id)){
            $transaction_subcategory_ids_Status = "'" . implode ( "', '", $request->filter_global_transaction_subcategory_id ) . "'";
            $transaction_subcategory_ids_Sql =  " and tr.transaction_subcategory_id IN(".$transaction_subcategory_ids_Status.") ";
        }

        if(isset($request->select_customer_from_select2_dropdown_id) && is_array($request->select_customer_from_select2_dropdown_id) && !empty($request->select_customer_from_select2_dropdown_id)){
            $select_customer_ids_Status = "'" . implode ( "', '", $request->select_customer_from_select2_dropdown_id ) . "'";
            $customer_id_Sql =  " and tr.user_id IN(".$select_customer_ids_Status.") ";
        }


        $sql = "select count(tr.id) as cnt FROM ".$this->tbl." tr INNER JOIN acc_accounts_or_banks ab ON ab.id=tr.accountid_from LEFT JOIN acc_transaction_subcategory tsb ON tsb.id=tr.transaction_subcategory_id LEFT JOIN admins ad ON ad.id=tr.admin_id LEFT JOIN acc_vendors vd ON vd.id=tr.vendor_id LEFT JOIN tbl_orders ord ON ord.id=tr.order_id LEFT JOIN tbl_users u ON u.id=tr.user_id WHERE tr.is_active!='2' ".$transaction_date_Sql.$transaction_type_Sql.$accountbanks_ids_Sql.$transaction_subcategory_ids_Sql.$is_reviewed_Sql.$vendor_ids_Sql.$customer_id_Sql." ";
        $transactionCount = qry($sql);

        if(isset($transactionCount[0]->cnt) && $transactionCount[0]->cnt>10000) {
            Session::flash('cls', 'danger');
            Session::flash('msg', 'There are more than 10000 records in Excel, Please Export less than 10000 records.');
            return redirect()->back();
        }

        $sql = "select 
        tr.id as Id,
        tr.amount as Amount,
        tr.transaction_date as TransactionDate,
        DATE_FORMAT(tr.transaction_date, '%d-%m-%Y') as TransactionDate,
        tr.description as Description,
        tr.transaction_type as TransactionType,
        tr.anybillno as BillNo,
        CONCAT('".sendPath().constants('dir_name.bill')."','/',tr.anybillno_document) as BillFileUrl,
        tsb.name as CategoryName,
        ab.name as BankAccount,
        CONCAT(u.fullname,' ',business_name) CustomerDetails,
        vd.fullname as Vendor,
        ord.bigdaddy_lr_number as BDLROrderNo,
        ad.fullname as TransactionCreatedBy FROM ".$this->tbl." tr INNER JOIN acc_accounts_or_banks ab ON ab.id=tr.accountid_from LEFT JOIN acc_transaction_subcategory tsb ON tsb.id=tr.transaction_subcategory_id LEFT JOIN admins ad ON ad.id=tr.admin_id LEFT JOIN acc_vendors vd ON vd.id=tr.vendor_id LEFT JOIN tbl_orders ord ON ord.id=tr.order_id LEFT JOIN tbl_users u ON u.id=tr.user_id WHERE tr.is_active!='2' ".$transaction_date_Sql.$transaction_type_Sql.$accountbanks_ids_Sql.$transaction_subcategory_ids_Sql.$is_reviewed_Sql.$vendor_ids_Sql.$customer_id_Sql." ";

        $transactions = qry($sql);
        $transactions = json_decode(json_encode($transactions), true);

        if(count($transactions)>0){
            $column_name =  array_keys($transactions[0]);
            $export = new JustExcelExport($transactions, $column_name );
            return Excel::download($export, "Transactions_".date('d-M-Y-H-i-sA').'.xlsx'); 
        }
        else
        {
            Session::flash('cls', 'danger');
            Session::flash('msg', 'No Data Found For Excel!!');
            return redirect()->back();
        }

    }

  






}
