<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use App\Models\SalesExecutive;
date_default_timezone_set('Asia/Kolkata');

class SalesExecutiveController extends Controller
{
    public $tbl = "tbl_salesexecutive";
    public $tbl2 = "tbl_address";
    
    public function index(Request $request){
    	$data = ['tbl' => $this->tbl, 'control' => 'Sales Executive' ];
		return view('admin.salesexecutive.list')->with($data);
	}
	
	public function getdata(Request $request){
        $columns = array(          
            0 => 'se.id',
            1 => 'se.fullname',
            2 => 'se.email',
        );

        $sql = "select se.* from ".$this->tbl." se WHERE se.is_active!='2' ";

        $query = qry($sql);
        $totalData = count($query);
        $totalFiltered = $totalData;

        if (!empty($request['search']['value'])) {   
            $searchString = "'%" . str_replace(",", "','", $request['search']['value']) . "%'"; 
            $sql .= " and ( se.id LIKE " . $searchString;
            $sql .= " OR se.email LIKE " . $searchString;
            $sql .= " OR se.mobile LIKE " . $searchString;
            $sql .= " OR se.created_at LIKE " . $searchString;
            $sql .= " OR se.fullname LIKE " . $searchString . ")";
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
            $nestedData[] = $row->fullname;
            $view = "<a style='cursor: pointer;' class='btn btn-primary btn-sm editit' data-id='".$row->id."'>Edit</a>";
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

    public function getedit(Request $request){
        $response = SalesExecutive::where('id', $request->id)->first();
        return response()->json($response);
    }

    public function Add_OR_Update(Request $request){
        if ($request->fullname=='' || !isset($request->is_active) || !isset($request->hid)) {
            $response = ['msg' => 'Something Went Wrong. Please Try Again!!', 'success' => 0 ];
            return response()->json($response);
        }

        if($request->hid>0){
            $updateData = [
            'fullname' => $request->fullname,
            'email' => isset($request->email) ? trim($request->email) : NULL,
            'mobile' => isset($request->mobile) ? trim($request->mobile) : NULL,
            'is_active' => ($request->is_active==0) ? $request->is_active : 1,
            ];

            SalesExecutive::where('id',$request->hid)->update($updateData);
            $response = ['msg' => $request->typehid.' Updated Successfully!', 'success' => 1 ];
            return response()->json($response);
        }
        else if($request->hid==0){
            $insertData = [
            'fullname' => $request->fullname,
            'email' => isset($request->email) ? trim($request->email) : NULL,
            'mobile' => isset($request->mobile) ? trim($request->mobile) : NULL,
            'is_active' => ($request->is_active==0) ? $request->is_active : 1,
            ];

            $lastData = SalesExecutive::create($insertData);
            $response = ['msg' => $request->typehid.' Added Successfully!', 'success' => 1 , 'data' => $lastData->id];
            return response()->json($response);
        }
        else
        {
            $response = ['msg' => 'Something Went Wrong. Please Try Again!!', 'success' => 0  , 'data' => 0 ];
            return response()->json($response);
        }
    }











}
