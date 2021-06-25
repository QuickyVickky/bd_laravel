<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
date_default_timezone_set('Asia/Kolkata');
use Carbon\Carbon;
use App\Models\AdminNotification;




class NotificationController extends Controller
{
    public $tbl = "tbl_admin_notifications";

    public function index(Request $request) {
        if_allowedRoute('all-notifications');
    	$data = ['tbl' => $this->tbl, 'control' => 'Notifications' ];
		return view('admin.customfields.notification_list')->with($data);
	}


    public function getdata(Request $request){

        $columns = array(          
            0 => 'an.notification_text',
            1 => 'an.created_at',
        );

        $sql = "select an.* from ".$this->tbl." an WHERE an.notification_text!='' ";

        $query = qry($sql);
        $totalData = count($query);
        $totalFiltered = $totalData;

        if (!empty($request['search']['value'])) {   
            $searchString = "'%" . str_replace(",", "','", $request['search']['value']) . "%'"; 
            $sql .= " and ( an.title LIKE " . $searchString;
            $sql .= " OR an.created_at LIKE " . $searchString;
            $sql .= " OR an.notification_text  LIKE " . $searchString . ")";
        }

        $c = count($columns);
            for ($i = 0; $i < $c; $i++) {
                if (!empty($request['columns'][$i]['search']['value'])) {
                    $sql .= " AND " . $columns[$i] . " LIKE '%" . $request['columns'][$i]['search']['value'] . "%'  ";
                }
            }


        $query = qry($sql);
        $totalFiltered = count($query);

        $sql .= " ORDER BY " . $columns[$request->order[0]['column']] . "   " . $request->order[0]['dir'] . " LIMIT " . $request->start . " ," . $request->length . "   ";  
        $query = qry($sql);

        $data = array();

        $cnts = $request->start + 1;
        foreach ($query as $row) {
            $nestedData = array();
            $nestedData[] = $row->notification_text;
            $nestedData[] = Carbon::parse($row->created_at)->format('d-m-Y')." ".Carbon::parse($row->created_at)->format('h:i:s A');
       
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



    public function getFirst10Notifications(Request $request)
    {
    	$from = date('Y-m-d', strtotime(date('Y-m-d') . ' -50 day'));
    	$to = date('Y-m-d', strtotime(date('Y-m-d') . ' +1 day'));
    	AdminNotification::whereNotBetween('created_at', [$from, $to])->delete();
    	$dataAdminNotification = AdminNotification::whereNotNull('notification_text')->orderBy('id','DESC')->limit(15)->get();
    	return response()->json($dataAdminNotification);
    }

    
























}
