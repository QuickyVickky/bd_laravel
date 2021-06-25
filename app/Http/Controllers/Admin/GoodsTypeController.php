<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
date_default_timezone_set('Asia/Kolkata');
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\GoodsType;


class GoodsTypeController extends Controller
{
    private $tbl = "tbl_goods_type";

    public function index(Request $request){
        $data = ['tbl' => $this->tbl, 'control' => 'Goods Type' ];
        return view('admin.customfields.goodstype.list')->with($data);
    }

    public function getedit(Request $request){
        $response = qry('SELECT * FROM '.$this->tbl.' where id='.$request->id.' LIMIT 1');
        return response()->json($response);
    }

    public function getdata(Request $request){

        $columns = array(          
            0 => 'ac.id',
            1 => 'ac.name',
            2 => 'ac.created_at',
        );
        $sql = "select ac.* from ".$this->tbl." ac WHERE ac.is_active!='2'";
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
            $editBtn = "";
            $nestedData = array();
            $nestedData[] = $cnts;
            $nestedData[] = $row->name;
            if($row->is_editable==true){
                $editBtn = "<a style='cursor: pointer;' class='btn btn-primary btn-sm edititmain' data-id='".$row->id."'>Edit</a>";
            }

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

    public function Add_OR_Update(Request $request) {
        try {

        if(!isset($request->hid) OR !is_numeric($request->hid) OR !isset($request->name) OR $request->name=='' OR !isset($request->status) OR !is_numeric($request->status)){
            $response = ['msg' => 'Please check Properly, Something Went Wrong. Please Try Again!!', 'success' => 0 ];
            return response()->json($response);
        }

        if($request->hid==0){

            $countGoodsType = GoodsType::where('is_active', '!=', 2)->count();
            if($countGoodsType >= 250){
                $response = ['msg' => 'Limit is Over of 250 Records. You Can Not Add New.', 'success' => 0 ];
                return response()->json($response);
            }

            $data = [
                'name' => $request->name,
                'is_active' => ($request->status==0) ? 0 : 1,
            ];
            
            $lastInsertedData = GoodsType::create($data);

            $response = ['msg' => 'Added Successfully', 'success' => 1 ];
            return response()->json($response);
        }
        else if($request->hid > 0)
        {
            $data = [
                'name' => $request->name,
                'is_active' => ($request->status==0) ? 0 : 1,
                'updated_at' => date('Y-m-d H:i:s'),
            ];
            $affectedRow = update_data($this->tbl,$data,['id' => $request->hid]);
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


    
     /* Route::get('et', [GoodsTypeController::class, 'et'])->name('et');
        Route::get('dt', [GoodsTypeController::class, 'dt'])->name('dt');

        public function et(){
        $array = [
            'idx' => "989789",
            'msg' => "This is a Test Message.",
        ];

        $string = json_encode($array);
        $encryptedString = Crypt::encryptString($string);
        dde($encryptedString);
    }


    public function dt(){
        $string = "eyJpdiI6InpSMDh2eW1TakI1SkVCUlVOM0JTM1E9PSIsInZhbHVlIjoiRk16UEtZNG9CV3BwOGVTNmYyZWVGTEU2a0wvOFNKcmpFUytxc1dUYWxtazljcFRRSmptbm9YZXFqRzhhekZ3NWp6TlJZTDkxZHZNNWFoaUt4dE9vNnc9PSIsIm1hYyI6IjE5YmRhNzMyN2FkZWQ2Zjk0ZDM3MjRmMDkzNzI5MzgzMzdjNDE3OWFiZmU1NzYxMWJhYWY0MjBkMzYzZWJmZDYifQ==";

        $decryptedString = Crypt::decryptString($string);
         $array = json_decode($decryptedString);
        dde($array);
    }*/




  






}
