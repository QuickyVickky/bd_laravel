<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AccTransactionSubCategory;
date_default_timezone_set('Asia/Kolkata');
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Session;

class AccCategoryController extends Controller
{

    public $tbl = "";
    public $tbl2 = "acc_transaction_subcategory";

    public function index(Request $request){
        if_allowedRoute('transaction-category-list');
        $data = ['tbl' => $this->tbl, 'control' => 'Category'];
        return view('admin.account.accountcategotylist')->with($data);
    }

    public function getedit(Request $request){
        $response = AccTransactionSubCategory::where('is_active', constants('is_active_yes'))->where('is_editable', constants('is_editable_yes'))->where('id', $request->id)->first();
        return response()->json($response);
    }

    public function getdata(Request $request){

        $columns = array(          
            0 => 'tsb.created_at',
            1 => 'tsb.name',
            2 => 'tsb.created_at',
        );
        $sql = "select tsb.* from ".$this->tbl2." tsb WHERE tsb.is_active!='2'  ";
        $query = qry($sql);
        $totalData = count($query);
        $totalFiltered = $totalData;

        if (!empty($request['search']['value'])) {   
            $searchString = "'%" . str_replace(",", "','", $request['search']['value']) . "%'"; 
            $sql .= " and ( tsb.name LIKE " . $searchString;
            $sql .= " OR tsb.name2 LIKE " . $searchString;
            $sql .= " OR tsb.details LIKE " . $searchString;
            $sql .= " OR tsb.created_at LIKE " . $searchString . ")";
        }

        $query = qry($sql);
        $totalFiltered = count($query);

        $sql .= " ORDER BY " . $columns[$request->order[0]['column']] . "   " . $request->order[0]['dir'] . " LIMIT " . $request->start . " ," . $request->length . "   ";  
        $query = qry($sql);

        $data = array();

        $cnts = $request->start + 1;
        foreach ($query as $row) {
            $edit = ''; $status = '';
            $transaction_type_status = "<br><span class='shadow-none badge badge-".constants('transaction_type_list.'.$row->transaction_type.'.classhtml')."' >".constants('transaction_type_list.'.$row->transaction_type.'.name2')."</span>";

            $nestedData = array();
            $nestedData[] = $cnts;
            $nestedData[] = $row->name.$transaction_type_status;
            if($row->is_editable == constants('is_editable_yes')){
                
                if ($row->is_active == constants('is_active_yes')) {
                    $status = "<a class='btn btn-success btn-sm change_statusit' data-id='".$row->id."' data-val=".constants('is_active_no').">ACTIVE</a>";
                    $edit = '<a href="javascript:void(0)" class="btn btn-rounded btn-outline-primary editit" data-typehid="'.constants('transaction_type_list.'.$row->transaction_type.'.name2') .'" data-id="'.$row->id.'" title="edit this"><i class="fas fa-pen-square"></i></a>';
                } else {
                    $edit = '';
                    $status = "<a class='btn btn-danger btn-sm change_statusit' data-id='".$row->id."' data-val=".constants('is_active_yes').">DEACTIVE</a>";
                }
            }
            
            $nestedData[] = $edit." ".$status;
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

    public function addorupdatemaincategory(Request $request) {
        if(!isset($request->hid) OR !is_numeric($request->hid) OR !isset($request->name) OR $request->name=='' OR !isset($request->status) OR !is_numeric($request->status)){
            $response = ['msg' => 'Please check Properly, Something Went Wrong. Please Try Again!!', 'success' => 0 ];
            return response()->json($response);
        }

        if($request->hid==0){
            $data = [
                'name' => $request->name,
                'name2' => isset($request->name2) ? trim($request->name2) : NULL,
                'details' => isset($request->details) ? trim($request->details) : NULL,
                'is_active' => ($request->status==0) ? 0 : 1,
                'is_editable' => ($request->is_editable==0) ? 0 : 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
            
            $id = insert_data_id($this->tbl,$data);

            if($id > 0){
            $response = ['msg' => 'Added Successfully', 'success' => 1 ];
            }
            else
            {
            $response = ['msg' => 'Something Went Wrong. Please Try Again!!', 'success' => 1 ];
            }
            return response()->json($response);
        }
        else if($request->hid > 0)
        {
            $data = [
                'name' => $request->name,
                'name2' => isset($request->name2) ? trim($request->name2) : NULL,
                'details' => isset($request->details) ? trim($request->details) : NULL,
                'is_active' => ($request->status==0) ? 0 : 1,
                'updated_at' => date('Y-m-d H:i:s'),
            ];
            $affectedRow = update_data($this->tbl,$data,['id' => $request->hid, 'is_editable' => 1]);
            $response = ['msg' => 'Updated Successfully!', 'success' => 1 ];
            return response()->json($response);
        }
        else
        {
            $response = ['msg' => 'Something Went Wrong. Please Try Again!!', 'success' => 0 ];
            return response()->json($response);
        }
    }

    public function Add_Or_Update(Request $request) {
        $request->validate([
            'hid' => 'required|numeric',
            'name' => 'required',
            'transaction_type' => 'required',
            'is_active' => 'required|numeric',
            'is_editable' => 'required|numeric',
            'typehid' => 'required',
        ]);

         try {

            if($request->hid>0)
             {
            
            $updateData = [
            'name' => $request->name,
            'transaction_type' => $request->transaction_type,
            'name2' => isset($request->name2) ? trim($request->name2) : NULL,
            'details' => isset($request->details) ? trim($request->details) : NULL,
            'updated_at' => date('Y-m-d H:i:s'),
            ];

            update_data($this->tbl2, $updateData ,['id' => $request->hid ]);
            $response = ['msg' => $request->typehid.' Updated Successfully!', 'success' => 1 ];
            return response()->json($response);
            }
            else if($request->hid==0)
            {

            $insertData = [
            'name' => $request->name,
            'transaction_type' => $request->transaction_type,
            'name2' => isset($request->name2) ? trim($request->name2) : NULL,
            'details' => isset($request->details) ? trim($request->details) : NULL,
            'is_active'=> $request->is_active == 0 ? 0 : 1,
            'is_editable'=> $request->is_editable,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            ];

            $lastinsertid = insert_data_id($this->tbl2,$insertData);
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
            $response = ['msg' => 'Something Went Wrong. Please Try Again!!', 'success' => 0 ];
            return response()->json($response);
        }
    }


    public function transactionSubCategoryInDropDown(Request $request){
        try {
            $searchTerm = ''; $transaction_type = NULL;
            if(isset($request->searchTerm)) {
                $searchTerm = $request->searchTerm;
            }

            if(isset($request->transaction_type)) {
                $transaction_type = $request->transaction_type;
            }


            $response = AccTransactionSubCategory::where('is_active', constants('is_active_yes'))
            ->where(function($transaction_typeQry) use ($transaction_type) {
                if($transaction_type!=''){
                    $transaction_typeQry->where('transaction_type', $transaction_type);
                }
            })
            ->where(function($searchTermQry) use ($searchTerm) {
                    $searchTermQry->where('name','LIKE', '%'.$searchTerm.'%')
                            ->orwhere('name2','LIKE', '%'.$searchTerm.'%')
                            ->orwhere('details','LIKE', '%'.$searchTerm.'%');
            })
            ->where('transaction_type','!=','')
            ->orderBy('id','ASC')
            ->limit(constants('limit_in_dropdown_large'))
            ->get(['name AS text', 'id as id']);

            return response()->json($response);
        } catch (\Exception $e) {
            return [];
        }
    }



    public function changeTransactionSubcategoryStatus(Request $request) {
        $request->validate([
            'status' => 'required|numeric',
            'id' => 'required|numeric',
        ]);
         try {

            $count1 = AccTransactionSubCategory::where('id', $request->id)->count();

            if(($count1)>0){
                $Customer = AccTransactionSubCategory::where('id', $request->id)->update(['is_active' => $request->status ]);
                $response = ['msg' => 'Changed Successfully !', 'success' => 1];
            }
            else
            {
                $response = ['msg' => 'Can Not Be Changed!', 'success' => 0];
            }   return response()->json($response);
         } catch (\Exception $e) {
            $response = ['msg' => 'Something Went Wrong. Please Try Again!!', 'success' => 0 ];
            return response()->json($response);
        }
    }













}
