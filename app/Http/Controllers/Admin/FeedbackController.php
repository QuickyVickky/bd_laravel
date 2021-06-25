<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use Carbon\Carbon;
use App\Models\Inquiry;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
date_default_timezone_set('Asia/Kolkata');

class FeedbackController extends Controller
{
    public $tbl = "tbl_reviews";

    public function index(Request $request){
        $data = ['tbl' => $this->tbl, 'control' => "Feedback" ];
        return view('admin.customfields.feedback')->with($data);
    }

    public function getdata(Request $request){

        $columns = array(          
            0 => 'r.id',
            1 => 'o.id',
            2 => 'r.message',
            3 => 'r.driver_star',
        );
        $sql = "select r.*,u.fullname,u.id as uid, o.bigdaddy_lr_number from ".$this->tbl." r INNER JOIN tbl_orders o ON o.id=r.order_id INNER JOIN tbl_users u ON u.id=r.user_id WHERE r.is_active!='2' ";
        $query = qry($sql);
        $totalData = count($query);
        $totalFiltered = $totalData;

        if (!empty($request['search']['value'])) {   
            $searchString = "'%" . str_replace(",", "','", $request['search']['value']) . "%'"; 
            $sql .= " and ( o.bigdaddy_lr_number  LIKE " . $searchString;
            $sql .= " OR r.bigdaddy_service_star  LIKE " . $searchString;
            $sql .= " OR r.message  LIKE " . $searchString;
            $sql .= " OR r.subject  LIKE " . $searchString;
            $sql .= " OR r.driver_star  LIKE " . $searchString;
            $sql .= " OR u.fullname  LIKE " . $searchString . ")";
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


            $nestedData[] = "<a href='".route('view-customer').'/'.$row->uid."' class='badge badge-primary'>".$row->fullname."</a><br><br><a href='".route('view-order').'/'.$row->order_id."' class='badge badge-info'>ORDER : ".$row->bigdaddy_lr_number."</a>";
            $nestedData[] = $row->subject."<br>".$row->message;



            $star = '<span class=" shadow-none badge outline-badge-primary">Driver : '.$row->driver_star.'</span><br><span class=" shadow-none badge outline-badge-secondary">Service : '.$row->bigdaddy_service_star.'</span>';


            $nestedData[] = $star;
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


    public function change_status_feedback_seen(Request $request) {
        $validator = Validator::make($request->all(), [ 
            'status' => 'required|numeric',
            'id' => 'required|integer|min:1',
        ]);

        if($validator->fails()) {  
            $response = ['msg' => 'Something Went Wrong.', 'success' => 0 ];
            return response()->json($response);   
        }

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
