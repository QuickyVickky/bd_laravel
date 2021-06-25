<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AccAccountBanks;
use App\Models\AccAccountCategory;
date_default_timezone_set('Asia/Kolkata');
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Session;

class AccAccountController extends Controller
{
	private $tbl = "acc_account_category";
    private $tbl2 = "acc_accounts_or_banks";

    public function index(Request $request){
        if_allowedRoute('accountsnbanks-list');
        $dataAccAccountCategory = AccAccountCategory::where('is_active',constants('is_active_yes'))->orderBy('id','ASC')->limit(10)->get();
        $data = ['tbl' => $this->tbl, 'control' => 'All Account & Banks', 'getAccount_Category' => $dataAccAccountCategory ];
        return view('admin.account.accountnbankslist')->with($data);
    }

    public function getedit(Request $request){
        $dataAccAccountBanks = AccAccountBanks::where('is_editable',constants('is_editable_yes'))->where('id', $request->id)->first();
        return response()->json($dataAccAccountBanks);
    }

    public function getdata(Request $request){
        $columns = array(          
            0 => 'ab.name',
            1 => 'ab.description',
            2 => 'ac.name',
        );

        $sql = "select ab.*,ac.name as acname,ac.details as acdetails from ".$this->tbl2." ab INNER JOIN ".$this->tbl." ac ON ac.id=ab.account_category_id
        WHERE ab.is_active=".constants('is_active_yes')." and ac.is_active=".constants('is_active_yes')." ";


        $query = qry($sql);
        $totalData = count($query);
        $totalFiltered = $totalData;

        if (!empty($request['search']['value'])) {   
            $searchString = "'%" . str_replace(",", "','", $request['search']['value']) . "%'"; 
            $sql .= " and ( ab.name LIKE " . $searchString;
            $sql .= " OR ab.description LIKE " . $searchString;
            $sql .= " OR ab.account_id LIKE " . $searchString; 
            $sql .= " OR ac.name LIKE " . $searchString . ")";
        }

        $query = qry($sql);
        $totalFiltered = count($query);

        $sql .= " ORDER BY " . $columns[$request->order[0]['column']] . "   " . $request->order[0]['dir'] . " LIMIT " . $request->start . " ," . $request->length . "   ";  
        $query = qry($sql);

        $data = array();

        $cnts = $request->start + 1;

        foreach ($query as $row) {
            $edit = '';

            if($row->is_editable==constants('is_editable_yes')){
                $edit = '<a href="javascript:void(0)" class="btn btn-rounded btn-outline-primary editit" onclick="show_add_newaccountModal('.$row->id.')" data-id="'.$row->id.'" title="edit this"><i class="fas fa-pen-square"></i></a>';
            }

            $nestedData = array();
            $nestedData[] = $row->name."<br>".$row->account_id;
            $nestedData[] = $row->description;
            $nestedData[] = $row->acname;
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


    public function Add_Or_Update(Request $request) {
        $validator = Validator::make($request->all(), [ 
            'hid'=> 'required',
            'account_category_id'=> 'required|numeric',
            'name'=>'required',
            'status'=>'required',
        ]);   

        if($validator->fails()) {  
            $response = ['msg' => 'Please check Properly, Something Went Wrong. Please Try Again!!', 'success' => 0 ];
            return response()->json($response);
        }  
        

        if($request->hid==0){
            $data = [
                'name' => $request->name,
                'account_category_id' => $request->account_category_id,
                'account_id' => isset($request->account_id) ? trim($request->account_id) : NULL,
                'description' => isset($request->description) ? trim($request->description) : NULL,
                'is_active' => ($request->status==0) ? 0 : 1,
                'is_editable' => ($request->is_editable==0) ? 0 : 1,
                'admin_id' => Session::get('adminid'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
            
            $id = insert_data_id($this->tbl2,$data);

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
                'account_id' => isset($request->account_id) ? trim($request->account_id) : NULL,
                'description' => isset($request->description) ? trim($request->description) : NULL,
                'updated_at' => date('Y-m-d H:i:s'),
            ];
            $affectedRow = update_data($this->tbl2,$data,['id' => $request->hid, 'is_editable' => 1]);
            $response = ['msg' => 'Updated Successfully!', 'success' => 1 ];
            return response()->json($response);
        }
        else
        {
            $response = ['msg' => 'Something Went Wrong. Please Try Again!!', 'success' => 0 ];
            return response()->json($response);
        }
    }

    public function accountsOrBanksInDropdown(Request $request){
        try {
            $searchTerm = ''; $excludeids = 0;
            if(isset($request->searchTerm)) {
                $searchTerm = $request->searchTerm;
            }

            if(isset($request->excludeids)) {
                $excludeids = $request->excludeids;
            }

            $excludeIdsArray = explode(',', $excludeids);

            $response = AccAccountBanks::where('is_active', constants('is_active_yes'))
            ->where(function($searchTermQry) use ($searchTerm) {
                if($searchTerm!=''){
                    $searchTermQry->where('name','LIKE', '%'.$searchTerm.'%')
                            ->orwhere('description','LIKE', '%'.$searchTerm.'%')
                            ->orwhere('account_id','LIKE', '%'.$searchTerm.'%');
                }
            })
            ->whereNotIn('id', $excludeIdsArray)
            ->orderBy('id','ASC')
            ->limit(constants('limit_in_dropdown_large'))
            ->get(['name AS text', 'id as id']);

            return response()->json($response);

        } catch (\Exception $e) {
            return [];
        }
    }
}
