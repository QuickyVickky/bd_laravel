<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
date_default_timezone_set('Asia/Kolkata');
use App\Models\AccountCategory;


class AccountCategoryController extends Controller
{
    public $tbl = "tbl_account_category";

    public function index(Request $request){
        if_allowedRoute('account-category');
        $data = ['tbl' => $this->tbl ];
        return view('admin.account.category')->with($data);
    }

    public function getedit(Request $request){
        $response = qry('SELECT * FROM '.$this->tbl.' where id='.$request->id.' LIMIT 1');
        return response()->json($response);
    }

    public function geteditsub(Request $request){
        $response = qry('SELECT ac.id,ac.name,ac.is_active,ac.created_at,ac.level,ac.path_to,(select name from '.$this->tbl.' WHERE id=ac.path_to limit 1) as main_category_name FROM '.$this->tbl.' ac where ac.id='.$request->id.' LIMIT 1');
        return response()->json($response);
    }

    public function getAllMaincategory() {
        $includeid = 0;
        extract($_REQUEST);
        if (!isset($_POST['searchTerm'])) {
            $sql = "select id, name as text from tbl_account_category where (is_active!='2' OR id=".$includeid.") and level='0' and path_to='0' order by id asc limit 50";
        } else {
            $sql = "select id, name as text from tbl_account_category where ( is_active!='2' OR id=".$includeid." ) and level='0' and path_to='0' ";
            $search = $_POST['searchTerm'];
            $sql .= " AND ( name like '%" . $search . "%' ) order by id asc LIMIT 50  ";
        }

        $response = qry($sql);
        return response()->json($response);
    }

    public function getAllSubcategory() {
        $includeid = 0; 
        extract($_REQUEST);

        if(isset($main_category_id) && $main_category_id> 0) { $main_category_id = $main_category_id;}
        else { $main_category_id = 0; }

        if (!isset($_POST['searchTerm'])) {
            $sql = "select id, name as text from tbl_account_category where (is_active!='2') and level='1' and path_to>0 and path_to=".$main_category_id." order by id asc limit 50";
        } else {
            $sql = "select id, name as text from tbl_account_category where ( is_active!='2' ) and level='1' and path_to>0 and path_to=".$main_category_id."  ";
            $search = $_POST['searchTerm'];
            $sql .= " AND ( name like '%" . $search . "%' ) order by id asc LIMIT 50  ";
        }

        $response = qry($sql);
        return response()->json($response);
    }
    public function getdata(Request $request){

        $columns = array(          
            0 => 'ac.id',
            1 => 'ac.name',
            2 => 'ac.created_at',
        );
        $sql = "select ac.* from ".$this->tbl." ac WHERE ac.is_active!='2' and ac.level='0' and path_to='0' ";
        $query = qry($sql);
        $totalData = count($query);
        $totalFiltered = $totalData;

        if (!empty($request['search']['value'])) {   
            $searchString = "'%" . str_replace(",", "','", $request['search']['value']) . "%'"; 
            $sql .= " and ( ac.name LIKE " . $searchString;
            $sql .= " OR ac.created_at LIKE " . $searchString . ")";
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
            $nestedData[] = $row->name;


            $view = "";
            if($row->is_editable==constants('is_editable_yes')){
                $view = "<a style='cursor: pointer;' class='btn btn-primary btn-sm edititmain' data-id='".$row->id."'>Edit</a>";
            }

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

    public function getdatasubcategory(Request $request){

        $columns = array(          
            0 => 'ac.id',
            1 => 'ac.name',
            2 => 'ac.path_to',
            3 => 'ac.created_at',
        );
        $sql = "select ac.*,(select name from ".$this->tbl." WHERE id=ac.path_to limit 1) as main_category_name from ".$this->tbl." ac WHERE ac.is_active!='2' and ac.level='1' and path_to>0 ";
        $query = qry($sql);
        $totalData = count($query);
        $totalFiltered = $totalData;

        if (!empty($request['search']['value'])) {   
            $searchString = "'%" . str_replace(",", "','", $request['search']['value']) . "%'"; 
            $sql .= " and ( ac.name LIKE " . $searchString;
            $sql .= " OR ac.created_at LIKE " . $searchString . ")";
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
            $nestedData[] = $row->name;
            $nestedData[] = $row->main_category_name;

            $view = "";
            if($row->is_editable==constants('is_editable_yes')){
                $view = "<a style='cursor: pointer;' class='btn btn-primary btn-sm edititsub' data-id='".$row->id."'>Edit</a>";
            }

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

    public function addorupdatemaincategory(Request $request) {
        if(!isset($request->hid) OR !is_numeric($request->hid) OR !isset($request->name) OR $request->name=='' OR !isset($request->status) OR !is_numeric($request->status)){
            $response = ['msg' => 'Please check Properly, Something Went Wrong. Please Try Again!!', 'success' => 0 ];
            return response()->json($response);
        }

        if($request->hid==0){

            $data = [
                'name' => $request->name,
                'level' => 0,
                'path_to' => 0,
                'is_active' => ($request->status==0) ? 0 : 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'is_editable' => 1,
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

            $countAccountCategory = AccountCategory::where('is_editable',constants('is_editable_yes'))->where('id',$request->hid)->count();

            if($countAccountCategory > 0){

                $data = [
                'name' => $request->name,
                'is_active' => ($request->status==0) ? 0 : 1,
                'updated_at' => date('Y-m-d H:i:s'),
                ];
                $affectedRow = update_data($this->tbl,$data,['id' => $request->hid]);
                $response = ['msg' => 'Updated Successfully!', 'success' => 1 ];

            }
            else
            {
                $response = ['msg' => 'Can Not Update!', 'success' => 0 ];
            }
            return response()->json($response);
        }
        else
        {
            $response = ['msg' => 'Something Went Wrong. Please Try Again!!', 'success' => 0 ];
            return response()->json($response);
        }
    }

    public function addorupdatesubcategory(Request $request) {
        if(!isset($request->hid) OR !is_numeric($request->hid) OR !isset($request->name) OR $request->name=='' OR !isset($request->status) OR !is_numeric($request->status) OR !isset($request->main_category_hid) OR !is_numeric($request->main_category_hid) OR $request->main_category_hid<1 ){
            $response = ['msg' => 'Please check Properly, Something Went Wrong. Please Try Again!!', 'success' => 0 ];
            return response()->json($response);
        }

        if($request->hid==0){

            $data = [
                'name' => $request->name,
                'level' => 1,
                'path_to' => $request->main_category_hid,
                'is_active' => ($request->status==0) ? 0 : 1,
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
            $countAccountCategory = AccountCategory::where('is_editable',constants('is_editable_yes'))->where('id',$request->hid)->count();


            if($countAccountCategory > 0){ 

            $data = [
                'name' => $request->name,
                'path_to' => $request->main_category_hid,
                'is_active' => ($request->status==0) ? 0 : 1,
                'updated_at' => date('Y-m-d H:i:s'),
            ];
            $affectedRow = update_data($this->tbl,$data,['id' => $request->hid]);
            $response = ['msg' => 'Updated Successfully!', 'success' => 1 ];

            }
            else
            {
                $response = ['msg' => 'Can Not Update!', 'success' => 0 ];
            }
            return response()->json($response);
        }
        else
        {
            $response = ['msg' => 'Something Went Wrong. Please Try Again!!', 'success' => 0 ];
            return response()->json($response);
        }
    }


    


  






}
