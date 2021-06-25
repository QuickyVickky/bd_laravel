<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use Carbon\Carbon;
use App\Models\Inquiry;

date_default_timezone_set('Asia/Kolkata');

class InquiryController extends Controller
{
    public $tbl = "tbl_inquiry";

    public function index(Request $request){
        $data = ['tbl' => $this->tbl, 'control' => "Inquiry" ];
        return view('admin.inquiry.list')->with($data);
    }

    public function getdata(Request $request){

        $columns = array(          
            0 => 'i.id',
            1 => 'i.fullname',
            2 => 'i.email_address',
            3 => 'i.is_active',
        );
        $sql = "select i.* from ".$this->tbl." i WHERE 1=1";
        $query = qry($sql);
        $totalData = count($query);
        $totalFiltered = $totalData;

        if (!empty($request['search']['value'])) {   
            $searchString = "'%" . str_replace(",", "','", $request['search']['value']) . "%'"; 
            $sql .= " and ( i.email_address  LIKE " . $searchString;
            $sql .= " OR i.mobile  LIKE " . $searchString;
            $sql .= " OR i.message  LIKE " . $searchString;
            $sql .= " OR i.subject  LIKE " . $searchString;
            $sql .= " OR i.devicetype  LIKE " . $searchString;
            $sql .= " OR i.fullname  LIKE " . $searchString . ")";
        }

        $query = qry($sql);
        $totalFiltered = count($query);

        $sql .= " ORDER BY " . $columns[$request->order[0]['column']] . "   " . $request->order[0]['dir'] . " LIMIT " . $request->start . " ," . $request->length . "   ";  
        $query = qry($sql);

        $data = array();

        $cnts = $request->start + 1;
        foreach ($query as $row) {
          
            $nestedData = array();
            $nestedData[] = Carbon::parse($row->created_at)->format('d-m-Y')."<br>".Carbon::parse($row->created_at)->format('h:i A');
            $nestedData[] = $row->fullname."<br>".$row->email_address."<br>".$row->mobile;
            $nestedData[] = $row->subject."<br>".$row->message;


            if($row->is_active==0){
                $markas = '<a href="javascript:void(0)" class="btn btn-rounded btn-outline-danger reviewit" data-id="'.$row->id.'" data-val="1" data-toggle="tooltip" data-placement="left" title="Mark As Reviewed"><i class="far fa-check-circle"></i></a>';
            }
            else
            {
                $markas = '<a href="javascript:void(0)" class="btn btn-rounded btn-outline-success active" data-id="'.$row->id.'" data-review="0" data-toggle="tooltip" data-placement="left" title="Reviewed"><i class="fas fa-check-circle"></i></a>';
            }
            
            $nestedData[] = $markas;
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


    public function change_status_inquiry_completed(Request $request) {
        $request->validate([
            'status' => 'required|numeric',
            'id' => 'required',
        ]);
         try {
            Inquiry::whereIn('id',[ $request->id ])->update(['is_active' => $request->status ]);
            $response = ['msg' => ' Completed Successfully !', 'success' => 1];
            return response()->json($response);
         } catch (\Exception $e) {
            $response = ['msg' => 'Something Went Wrong. Please Try Again!!', 'success' => 0 ];
            return response()->json($response);
        }

    }









}
