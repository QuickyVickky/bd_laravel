<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use Carbon\Carbon;
use App\Models\Vehicle;
use App\Models\Driver;
use App\Models\DriverFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\AccessToken;
use App\Models\OrderArrange;
date_default_timezone_set('Asia/Kolkata');
use App\Exports\JustExcelExport;
use Maatwebsite\Excel\Facades\Excel;


class DriverController extends Controller
{
    public $tbl = "tbl_drivers";
    public $tbl2 = "tbl_drivers_files";
    public $tbl3 = "tbl_vehicles";
    public $tbl4 = "tbl_orders";
    
    public function index(Request $request){
        $sql = 'SELECT d.id,d.fullname,d.mobile,v.vehicle_no,d.current_location,d.current_latitude,d.current_longitude FROM '.$this->tbl.' d 
        LEFT JOIN '.$this->tbl3.' v ON v.driver_id=d.id where d.is_active='.constants('is_active_yes').' ORDER BY d.updated_at DESC LIMIT 250';
        $drivers = qry($sql);
    	$data = ['tbl' => $this->tbl, 'control' => 'Driver' , 'drivers' => json_encode($drivers) ];
		return view('admin.driver.list')->with($data);
	}

    public function add_index(Request $request){
        $sql = 'SELECT id,name,short,details,ask_expiry,is_multiple FROM tbl_driver_files_type where is_active="0" and is_exclude="0" ';
        $driver_files_type = qry($sql);
        $data = ['tbl' => $this->tbl, 'control' => 'Driver' , 'driver_files_type' => $driver_files_type ];
        return view('admin.driver.add')->with($data);
    }

    public function view_edit_index($Id=0,Request $request){
        $driverFiles = []; $arrangementOrderData = [];
        $Id = trim(intval($Id));
        $sql = 'SELECT d.*,vd.fullname as vdfullname FROM '.$this->tbl.' d LEFT JOIN acc_vendors vd ON vd.id=d.vendor_id WHERE d.id='.$Id.' and d.is_active!="2" LIMIT 1';
        $one = qry($sql);

        if(!empty($one)){
            $sql = 'SELECT df.* FROM '.$this->tbl2.' df WHERE df.driver_id='.$Id.' and df.is_active!="2" LIMIT 500';
            $driverFiles = qry($sql);

            $arrangementOrderData = OrderArrange::with([
            'order' => function($qryOrder) {
                $qryOrder->with([
                    'order_status','order_payment_status','order_payment_type','customer'
                ]);
                $qryOrder->where('is_active', constants('is_active_yes'));
                },
            ])
            ->where('driver_id',$Id)
            ->where('is_active' , constants('is_active_yes'))
            ->whereIn('arrangement_type' , constants('arrangement_type'))
            ->where('is_completed' , constants('confirmation.no'))
            ->limit(256)->orderBy('arrangement_number','ASC')
            ->orderBy('id','DESC')->get();
        }

        $sql = 'SELECT id,name,short,details,ask_expiry,is_multiple FROM tbl_driver_files_type where is_active="0" and is_exclude="0" ';
        $driver_files_type = qry($sql);

        $data = ['tbl' => $this->tbl, 'control' => 'Driver' , 'driver_files_type' => $driver_files_type , 'one' => $one, 'driverFiles' => $driverFiles, 'arrangementOrderData' => $arrangementOrderData  ];
        return view('admin.driver.view')->with($data);
    }


    public function vehicle_index(Request $request){
        $data = ['tbl' => $this->tbl3, 'control' => 'Vehicle' ];
        return view('admin.driver.vehicle_list')->with($data);
    }

    public function vehicle_add_index(Request $request){
        $Id = isset($_GET['id']) ? trim(intval($_GET['id'])) : 0;
        $thisVehicleData = Vehicle::with('driver')->where('id',$Id)->where('is_active','!=',2)->first();
        $data = ['tbl' => $this->tbl3, 'control' => 'Vehicle' , 'one' => $thisVehicleData , 'id' => $Id];
        return view('admin.driver.vehicle_add')->with($data);
    }

	public function getdata(Request $request){
        $tendayafter = date('Y-m-d', strtotime('+11 days'));

        $columns = array(          
            0 => 'd.id',
            1 => 'd.fullname',
            2 => 'd.mobile',
            3 => 'v.vehicle_no',
            4 => 'd.is_active',
        );
        
        $sql = "select d.*,v.vehicle_no, sh.name as driver_status,sh.classhtml as shclasshtml from ".$this->tbl." d 
        LEFT JOIN tbl_short_helper sh ON sh.short=d.status and sh.type='driver_status'
        LEFT JOIN tbl_vehicles v ON v.driver_id=d.id 
        WHERE d.is_active!='2' ";

        $query = qry($sql);

        $totalData = count($query);
        $totalFiltered = $totalData;

        if (!empty($request['search']['value'])) {   
            $searchString = "'%" . str_replace(",", "','", $request['search']['value']) . "%'"; 
            $sql .= " and ( d.id LIKE " . $searchString;
            $sql .= " OR d.email LIKE " . $searchString;
            $sql .= " OR d.fullname LIKE " . $searchString;
            $sql .= " OR d.mobile LIKE " . $searchString;
            $sql .= " OR v.vehicle_no LIKE " . $searchString;
            $sql .= " OR d.address LIKE " . $searchString . ")";
        }

        $query = qry($sql);
        $totalFiltered = count($query);

        $sql .= " ORDER BY " . $columns[$request->order[0]['column']] . "   " . $request->order[0]['dir'] . " LIMIT " . $request->start . " ," . $request->length . "   ";  
        $query = qry($sql);

        $data = array();

        $cnts = $request->start + 1;
        foreach ($query as $row) {
            $status = ""; $assign = ""; $edit = "";
			$driver_status = "<br><span class='shadow-none badge badge-".$row->shclasshtml."' >".$row->driver_status."</span>";
			
            $nestedData = array();
            $nestedData[] = $cnts;
            $nestedData[] = $row->fullname;
            $nestedData[] = $row->mobile;
            $nestedData[] = $row->vehicle_no." ".$driver_status;
			
			
            if(is_allowedHtml('roleclass_view_btn_driver')==true){
                $view = "<a style='cursor: pointer;'  href='".route('view-driver').'/'.$row->id."' class='btn btn-primary btn-sm editit' data-id='".$row->id."'>View</a>";
            }

            if(is_allowedHtml('roleclass_status_btn_driver')==true){
                if ($row->is_active == 0) {
                $status = "<a style='cursor: pointer;' class='btn btn-success btn-sm change_status_confirm' data-id='".$row->id."' data-val='1'>ACTIVE</a>";
                } else {
                $status = "<a style='cursor: pointer;' class='btn btn-danger btn-sm change_status_confirm' data-id='".$row->id."' data-val='0'>DEACTIVE</a>";
                }
            }
            if(is_allowedHtml('roleclass_assign_btn_driver')==true && $row->vehicle_no!=''){
                $assign = "<a style='cursor: pointer;' class='btn btn-warning btn-sm assignit' data-id='".$row->id."' data-id='".$row->id."'>Assign</a>";
            }

            $nestedData[] = $assign." ".$status." ".$view;

            $is_expired = 0;
            if(($row->license_expiry!="" && $row->license_expiry < $tendayafter)){
                $is_expired = 1;
            }

            $nestedData[] = $is_expired;
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

    public function getdata_files(Request $request) {
        $path = asset('storage'.'/driver_files').'/';
        $tendayafter = date('Y-m-d', strtotime('+11 days'));

        $columns = array(          
            0 => 'df.id',
            1 => 'df.img_type_name',
            2 => 'df.img',
        );
        $sql = "select df.* from ".$this->tbl2." df WHERE df.driver_id=".$request->did." and is_active!='2' ";

        $query = qry($sql);
        $totalData = count($query);
        $totalFiltered = $totalData;

        if (!empty($request['search']['value'])) {   
            $searchString = "'%" . str_replace(",", "','", $request['search']['value']) . "%'"; 
            $sql .= " and ( df.img_type_name LIKE " . $searchString;
            $sql .= " OR df.img_type_name LIKE " . $searchString;
            $sql .= " OR df.img LIKE " . $searchString;
            $sql .= " OR df.created_at LIKE " . $searchString . ")";
        }

        $query = qry($sql);
        $totalFiltered = count($query);

        $sql .= " ORDER BY " . $columns[$request->order[0]['column']] . "   " . $request->order[0]['dir'] . " LIMIT " . $request->start . " ," . $request->length . "   ";  
        $query = qry($sql);

        $data = array();

        $cnts = $request->start + 1;
        foreach ($query as $row) {
            $img = '';
          
            $nestedData = array();
            $nestedData[] = $cnts;

            $red = 0;
            if($row->if_expiry_date!='' && $row->if_expiry_date<$tendayafter){ $red = 1;}



            $nestedData[] = $row->img_type_name;

            if($row->img!=""){
                $ex = explode(',', $row->img);
                if(is_array($ex) && count($ex)>0){

                    foreach ($ex as $key => $imageall) {
                    $exIMG = explode('.', $imageall);
                if(isset($exIMG[1])){
                    if(strtolower($exIMG[1])=='jpg' || strtolower($exIMG[1])=='jpeg' || strtolower($exIMG[1])=='png' || strtolower($exIMG[1])=='bmp' || strtolower($exIMG[1])=='gif' ){
                        $img .= '<span><img src='.$path.$imageall.' class="rounded-circle profile-img" alt="img" width="55px" height="55px" onClick="imgDisplayInModal(this.src)"></span>';
                        }
                        else
                        {
                           $img .= '<a target="_blank" href='.$path.$imageall.' >view file</a>'; 
                        }
                    }
                }

                }
                
                
            }

            $nestedData[] = $img;

            $deleteBtn = '';



            $editBtn = "<a style='cursor: pointer;' data-toggle='modal' data-target='#editModal' class='dropdown-item editit' data-id='".$row->id."' data-dvalue='".$row->driver_id."' data-fshort='".$row->short_helper_name."' data-if_expiry_date='".date("d-m-Y", strtotime($row->if_expiry_date))."' data-img='".$row->img."' data-img_type_name='".$row->img_type_name."' >Edit</a>";

            $deleteBtn = "<a style='cursor: pointer;' class='dropdown-item deleteit' data-id='".$row->id."' data-img='".$row->img."'  data-dvalue='".$row->driver_id."' >Delete</a>";

            


            $actionBtn = '<div class="btn-group"><button type="button" class="btn btn-primary btn-sm dropdown-toggle dropdown-toggle-split"  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-reference="parent" id="dropdownMenuReference5" >Action</button><div class="dropdown-menu" aria-labelledby="dropdownMenuReference5" style="will-change: transform;">'.$editBtn.$deleteBtn.'</div></div>';


            $nestedData[] = $actionBtn;
            $nestedData[] = $red;

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

    public function change_status_confirm(Request $request){
        $updateData = [
            'is_active' => $request->status,
        ];
        update_data($this->tbl, $updateData ,['id' => $request->id ]);

        $logdata = [
                'driver_id' => $request->id,
                'logs' => $request->sendLog." " .Session::get("fullname") ,
                'created_at' => date('Y-m-d H:i:s'),
            ];
        $returnid = insert_data_id("tbl_driver_logs",$logdata);
        echo json_encode($returnid);
    }


    public function checkSubmitOrderArrangement(Request $request){
        if(!isset($request->orderArrangeIds) || !is_array($request->orderArrangeIds) || empty($request->orderArrangeIds) || !isset($request->orderArrangeOrderIds) || !is_array($request->orderArrangeOrderIds) || empty($request->orderArrangeOrderIds) || !isset($request->orderArrangeTypeIds) || !is_array($request->orderArrangeTypeIds) || empty($request->orderArrangeTypeIds)){
            $response = ['msg' => 'Wrong Data, Please Refresh the Page.', 'success' => 0 ];
            return response()->json($response);
        }

        $countCurrent = count($request->orderArrangeIds);

        $dataOrderArrange = OrderArrange::where('is_active' , constants('is_active_yes'))->where('is_completed' , constants('confirmation.no'))->whereIn('id' ,$request->orderArrangeIds)->whereIn('arrangement_type' , constants('arrangement_type'))->get();

        if($countCurrent!=count($dataOrderArrange) || $countCurrent!=count($request->orderArrangeOrderIds) || $countCurrent!=count($request->orderArrangeTypeIds)){
            $response = ['msg' => 'MisMatch Data, Please Refresh the Page.', 'success' => 0 ];
            return response()->json($response);
        }

        $i = 0; $wrongOrderArrangeCount = 0;
        foreach ($request->orderArrangeIds as $key => $value) {

            if($request->orderArrangeTypeIds[$i]==constants('arrangement_type.pickup')){
                $orderArrangeOrderIdsTempArray = $request->orderArrangeOrderIds;

                        $offset = $i+1;
                        $max1 = $offset;
                        $max2 = count($orderArrangeOrderIdsTempArray);
                        for ($ijk=0; ($ijk<$max1 && $ijk<$max2); $ijk++) {
                            unset($orderArrangeOrderIdsTempArray[$ijk]);
                            $offset--;
                        }

                if(!empty($orderArrangeOrderIdsTempArray) && !in_array($request->orderArrangeOrderIds[$i], $orderArrangeOrderIdsTempArray)){
                    $wrongOrderArrangeCount++;
                }
            }
            else
            {
                $orderArrangeOrderIdsTempArray = $request->orderArrangeOrderIds;

                        $offset = $i+1;
                        $max1 = $offset;
                        $max2 = count($orderArrangeOrderIdsTempArray);
                        for ($ijk=0; ($ijk<$max1 && $ijk<$max2); $ijk++) {
                            unset($orderArrangeOrderIdsTempArray[$ijk]);
                            $offset--;
                        }

                if(!empty($orderArrangeOrderIdsTempArray) && in_array($request->orderArrangeOrderIds[$i], $orderArrangeOrderIdsTempArray)){
                    $wrongOrderArrangeCount++;
                }
            }
            $i++;
        }


        if($wrongOrderArrangeCount>0){
            $response = ['msg' => 'Order Arrangement is Incorrect, Please Check Carefully.', 'success' => 0 ];
            return response()->json($response);
        }
        else
        {
            $m = 1;
            foreach ($request->orderArrangeIds as $key1 => $value1) {

                $updateOrderArrangeDataDeliver = [
                    'arrangement_number' => $m,
                ];
                $lastOrderArrangeDataDeliver = OrderArrange::where('order_id', $request->orderArrangeOrderIds[$m-1])->where('id', $value1)->where('is_active' , constants('is_active_yes'))->where('arrangement_type' , $request->orderArrangeTypeIds[$m-1])->update($updateOrderArrangeDataDeliver);
                $m++;
            }
            $response = ['msg' => 'Successfully Updated.', 'success' => 1, 'data' => $request->orderArrangeOrderIds ];
            return response()->json($response);
        }
    }

    public function getdataDriverLogs(Request $request){
        $columns = array(          
            0 => 'ol.id',
            1 => 'ol.logs',
            2 => 'ol.created_at',

        );
        $sql = "select * from tbl_driver_logs ol WHERE ol.driver_id=".$request->id." ";

        $query = qry($sql);
        $totalData = count($query);
        $totalFiltered = $totalData;

        if (!empty($request['search']['value'])) {   
            $searchString = "'%" . str_replace(",", "','", $request['search']['value']) . "%'"; 
            $sql .= " and ( ol.id LIKE " . $searchString;
            $sql .= " OR ol.logs LIKE " . $searchString;
            $sql .= " OR ol.created_at LIKE " . $searchString . ")";
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
            $nestedData[] = $row->logs;
            $nestedData[] = $row->created_at;
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


    public function getdata_vehicle(Request $request){
        $tendayafter = date('Y-m-d', strtotime('+11 days'));

        $columns = array(          
            0 => 'v.id',
            1 => 'v.vehicle_no',
            2 => 'd.fullname',
            3 => 'v.driver_assigned_datetime',
        );

        $sql = "select v.*,d.fullname as driver_name from ".$this->tbl3." v LEFT JOIN ".$this->tbl." d ON d.id=v.driver_id WHERE v.is_active!='2' ";

        $query = qry($sql);

        $totalData = count($query);
        $totalFiltered = $totalData;

        if (!empty($request['search']['value'])) {   
            $searchString = "'%" . str_replace(",", "','", $request['search']['value']) . "%'"; 
            $sql .= " and ( v.id LIKE " . $searchString;
            $sql .= " OR d.id LIKE " . $searchString;
            $sql .= " OR d.fullname LIKE " . $searchString;
            $sql .= " OR v.vehicle_no LIKE " . $searchString;
            $sql .= " OR v.driver_assigned_datetime LIKE " . $searchString . ")";
        }

        $query = qry($sql);
        $totalFiltered = count($query);

        $sql .= " ORDER BY " . $columns[$request->order[0]['column']] . "   " . $request->order[0]['dir'] . " LIMIT " . $request->start . " ," . $request->length . "   ";  
        $query = qry($sql);

        $data = array();

        $cnts = $request->start + 1;
        foreach ($query as $row) {
            $status = ""; $assign = ""; $edit = ""; $removeAssigned = ""; $driver = "";
        
            $nestedData = array();
            $nestedData[] = $cnts;
            $nestedData[] = $row->vehicle_no;

            if($row->driver_id>0){
                $removeAssigned = "<a style='cursor: pointer;' class='btn btn-danger btn-sm removeassignit' data-vehicle='".$row->vehicle_no."' data-id='".$row->id."' data-driver='".$row->driver_id."'>Remove Assigned</a>";
                $driver = '<span class="shadow-none badge outline-badge-primary">'.$row->driver_name.'</span><br>';
            }
    
            $nestedData[] = $driver.Carbon::parse($row->driver_assigned_datetime)->format('d-m-Y')."<br>".Carbon::parse($row->driver_assigned_datetime)->format('h:i A');
            
            $view = "<a style='cursor: pointer;'  href='".route('edit-vehicle').'?id='.$row->id."' class='btn btn-primary btn-sm editit' data-id='".$row->id."'>View</a>";

            $assign = "<a style='cursor: pointer;' class='btn btn-warning btn-sm assignit' data-vehicle='".$row->vehicle_no."' data-id='".$row->id."'>Assign</a>";


            $is_expired = 0;

            if(($row->insurance_expiry!="" && $row->insurance_expiry < $tendayafter)){
                $is_expired = 1;
            }
            else if(($row->permit_expiry!="" && $row->permit_expiry < $tendayafter)){
                $is_expired = 1;
            }
            else if(($row->puc_expiry!="" && $row->puc_expiry < $tendayafter)){
                $is_expired = 1;
            }

            $nestedData[] = $removeAssigned." ".$assign." ".$view;
            $nestedData[] = $row->is_active;
            $nestedData[] = $is_expired;
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

    public function getdataAssignedPickedupOrdersByDriverWise(Request $request) {
        $order_status = "'" . implode ( "', '", constants('order_status.transit_orders') ) . "'";
        $order_status_sql =  " and o.status IN(".$order_status.") ";

        $columns = array(          
            0 => 'o.bigdaddy_lr_number',
            1 => 'o.transporter_lr_number',
            2 => 'u.fullname',
            3 => 'o.pickup_location',
            4 => 'o.final_cost',
            5 => 'o.status',
            6 => 'o.created_at',
        );


        $sql = "select o.*,inv.invoice_number,inv.invoice_file,inv.created_at as invcreated_at,d.fullname as driver_name,u.fullname as ufullname,u.business_name as ubusiness_name,u.mobile as umobile,sh.name as shname, sh.classhtml as shclasshtml from tbl_orders o 
        INNER JOIN tbl_users u ON u.id=o.user_id 
        LEFT JOIN tbl_drivers d ON d.id=o.driver_id 
        LEFT JOIN tbl_short_helper sh ON sh.short=o.status and sh.type='order_status_type' 
        LEFT JOIN tbl_invoice inv ON inv.id=o.invoice_id
        WHERE o.is_active!='2' and o.driver_id=".$request->did."  $order_status_sql ";


        $query = qry($sql);
        $totalData = count($query);
        $totalFiltered = $totalData;

        if (!empty($request['search']['value'])) {   
            $searchString = "'%" . str_replace(",", "','", $request['search']['value']) . "%'"; 
            $sql .= " and ( o.id LIKE " . $searchString;
            $sql .= " OR o.bigdaddy_lr_number LIKE " . $searchString;
            $sql .= " OR o.pickup_location LIKE " . $searchString;
            $sql .= " OR o.drop_location LIKE " . $searchString;
            $sql .= " OR o.contact_person_phone_number LIKE " . $searchString;
            $sql .= " OR o.contact_person_name LIKE " . $searchString;
            $sql .= " OR o.transporter_name LIKE " . $searchString;
            $sql .= " OR o.contact_person_phone_number_drop LIKE " . $searchString;
            $sql .= " OR o.contact_person_name_drop LIKE " . $searchString;
            $sql .= " OR o.transporter_name_drop LIKE " . $searchString;
            $sql .= " OR d.fullname LIKE " . $searchString;

            $sql .= " OR o.transporter_lr_number LIKE " . $searchString;
            $sql .= " OR inv.invoice_number LIKE " . $searchString;
            $sql .= " OR o.if_cheque_number LIKE " . $searchString;
            $sql .= " OR o.if_transaction_number LIKE " . $searchString;
            $sql .= " OR o.payment_comment LIKE " . $searchString;
            $sql .= " OR u.business_name LIKE " . $searchString;
            $sql .= " OR u.mobile LIKE " . $searchString;
            $sql .= " OR u.fullname LIKE " . $searchString;
            $sql .= " OR u.business_name LIKE " . $searchString;
            $sql .= " OR u.GST_number LIKE " . $searchString;
            $sql .= " OR u.email LIKE " . $searchString;
            $sql .= " OR u.mobile LIKE " . $searchString;
            $sql .= " OR u.pan_no LIKE " . $searchString;
            
            $sql .= " OR o.final_cost LIKE " . $searchString . ")";
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


            $invoiceBtn = '';
            if($row->invoice_number!=""){
                $invoiceBtn = "<br><a target='_blank' href='".sendPath().constants('dir_name.invoice').'/'.$row->invoice_file."'><span title='view invoice #".$row->invoice_number."' data-filename=".$row->invoice_file." data-folder=".constants('dir_name.invoice')." class='shadow-none badge badge-secondary spancursorcls filedownloaditno'>".$row->invoice_number."</span></a>";
            }

            $nestedData[] = $row->bigdaddy_lr_number;
            $nestedData[] = $row->transporter_lr_number;
            $nestedData[] = $row->contact_person_name."<br>". $row->ubusiness_name."<br>". $row->contact_person_phone_number.$invoiceBtn;
            $nestedData[] = "Pickup Address - ".$row->pickup_location."<br><br>"."Drop Address - ".$row->drop_location;
            $nestedData[] = $row->final_cost. " Rs";
            $nestedData[] = Carbon::parse($row->created_at)->format('d-m-Y')."<br>".Carbon::parse($row->created_at)->format('h:i:s A');

            $status = "<span class='shadow-none badge badge-".$row->shclasshtml."'>".$row->shname."</span>";
            $nestedData[] = $status;

            $logsBtn = "<a style='cursor: pointer;' data-toggle='modal' data-target='#logsModal' class='btn btn-info btn-sm' onclick='seeLogs(".$row->id.")'><i class='far fa-eye'></i></a>";

            $viewBtn = "<a style='cursor: pointer;' href='".route('view-order').'/'.$row->id."'  class='btn btn-info btn-sm'>View</a>"; 
            

            $nestedData[] = $logsBtn . " " .$viewBtn;
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

    public function getdataDeliveredOrdersDriverWise(Request $request){
        $order_status = "'" . implode ( "', '", constants('order_status.delivered_orders') ) . "'";
        $order_status_sql =  " and o.status IN(".$order_status.") ";

        $columns = array(          
            0 => 'o.bigdaddy_lr_number',
            1 => 'o.transporter_lr_number',
            2 => 'u.fullname',
            3 => 'o.pickup_location',
            4 => 'o.final_cost',
            5 => 'o.status',
            6 => 'o.created_at',
        );


        $sql = "select o.*,inv.invoice_number,inv.invoice_file,inv.created_at as invcreated_at,d.fullname as driver_name,u.fullname as ufullname,u.business_name as ubusiness_name,u.mobile as umobile,sh.name as shname, sh.classhtml as shclasshtml from tbl_orders o 
        INNER JOIN tbl_users u ON u.id=o.user_id 
        LEFT JOIN tbl_drivers d ON d.id=o.driver_id 
        LEFT JOIN tbl_short_helper sh ON sh.short=o.status and sh.type='order_status_type' 
        LEFT JOIN tbl_invoice inv ON inv.id=o.invoice_id
        WHERE o.is_active!='2' and o.driver_id=".$request->did." $order_status_sql ";


        $query = qry($sql);
        $totalData = count($query);
        $totalFiltered = $totalData;

        if (!empty($request['search']['value'])) {   
            $searchString = "'%" . str_replace(",", "','", $request['search']['value']) . "%'"; 
            $sql .= " and ( o.id LIKE " . $searchString;
            $sql .= " OR o.bigdaddy_lr_number LIKE " . $searchString;
            $sql .= " OR o.pickup_location LIKE " . $searchString;
            $sql .= " OR o.drop_location LIKE " . $searchString;
            $sql .= " OR o.contact_person_phone_number LIKE " . $searchString;
            $sql .= " OR o.contact_person_name LIKE " . $searchString;
            $sql .= " OR o.transporter_name LIKE " . $searchString;
            $sql .= " OR o.contact_person_phone_number_drop LIKE " . $searchString;
            $sql .= " OR o.contact_person_name_drop LIKE " . $searchString;
            $sql .= " OR o.transporter_name_drop LIKE " . $searchString;
            $sql .= " OR d.fullname LIKE " . $searchString;

            $sql .= " OR o.transporter_lr_number LIKE " . $searchString;
            $sql .= " OR inv.invoice_number LIKE " . $searchString;
            $sql .= " OR o.if_cheque_number LIKE " . $searchString;
            $sql .= " OR o.if_transaction_number LIKE " . $searchString;
            $sql .= " OR o.payment_comment LIKE " . $searchString;
            $sql .= " OR u.business_name LIKE " . $searchString;
            $sql .= " OR u.mobile LIKE " . $searchString;
            $sql .= " OR u.fullname LIKE " . $searchString;
            $sql .= " OR u.business_name LIKE " . $searchString;
            $sql .= " OR u.GST_number LIKE " . $searchString;
            $sql .= " OR u.email LIKE " . $searchString;
            $sql .= " OR u.mobile LIKE " . $searchString;
            $sql .= " OR u.pan_no LIKE " . $searchString;
            
            $sql .= " OR o.final_cost LIKE " . $searchString . ")";
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

            $invoiceBtn = '';
            if($row->invoice_number!=""){
                $invoiceBtn = "<br><a target='_blank' href='".sendPath().constants('dir_name.invoice').'/'.$row->invoice_file."'><span title='view invoice #".$row->invoice_number."' data-filename=".$row->invoice_file." data-folder=".constants('dir_name.invoice')." class='shadow-none badge badge-secondary spancursorcls filedownloaditno'>".$row->invoice_number."</span></a>";
            }

            $nestedData[] = $row->bigdaddy_lr_number;
            $nestedData[] = $row->transporter_lr_number;
            $nestedData[] = $row->contact_person_name."<br>". $row->ubusiness_name."<br>". $row->contact_person_phone_number.$invoiceBtn;
            $nestedData[] = "Pickup Address - ".$row->pickup_location."<br><br>"."Drop Address - ".$row->drop_location;
            $nestedData[] = $row->final_cost. " Rs";
            $nestedData[] = Carbon::parse($row->created_at)->format('d-m-Y')."<br>".Carbon::parse($row->created_at)->format('h:i:s A');

            $status = "<span class='shadow-none badge badge-".$row->shclasshtml."'>".$row->shname."</span>";
            $nestedData[] = $status;

            $logsBtn = "<a style='cursor: pointer;' data-toggle='modal' data-target='#logsModal' class='btn btn-info btn-sm' onclick='seeLogs(".$row->id.")'><i class='far fa-eye'></i></a>";

            $viewBtn = "<a style='cursor: pointer;' href='".route('view-order').'/'.$row->id."'  class='btn btn-info btn-sm'>View</a>"; 
            

            $nestedData[] = $logsBtn . " " .$viewBtn;
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

    public function assign_vehicle_to_driver(Request $request) {
        $driverId = isset($request->select_driver_id) ? $request->select_driver_id : 0;
        $updateData = [
            'driver_id' => $driverId,
            'driver_assigned_datetime' => date('Y-m-d H:i:s'),
        ];

        $updateDataZero = [
            'driver_id' => 0,
        ];

        Vehicle::where('driver_id',$driverId)->update($updateDataZero);
        Vehicle::where('id',$request->vehicle_hidid)->update($updateData);
        echo json_encode(1);
    }

    public function removeAssignedDriverFromVehicle(Request $request) {
        $updateDataZero = [
            'driver_id' => 0,
        ];
        Vehicle::where('id',$request->id)->update($updateDataZero);
        echo json_encode(1);
    }


    public function getDriverWithVehicleAndSelect(Request $request){
        $response = [];
        try {
            $searchTerm = '';
            if(isset($request->searchTerm)) {
                $searchTerm = $request->searchTerm;
            }

            $data = Driver::with('vehicle')->where('is_active', constants('is_active_yes'))
                ->where(function($query) use ($searchTerm) {
                    $query->where('fullname','LIKE', '%'.$searchTerm.'%')
                            ->orwhere('email','LIKE', '%'.$searchTerm.'%')
                            ->orwhere('mobile','LIKE', '%'.$searchTerm.'%');
            })
          ->orderBy('id','DESC')
          ->limit(constants('limit_in_dropdown'))
          ->get();

          foreach ($data as $key => $value) {
            $vehicleNo = isset($value->vehicle->vehicle_no) ? $value->vehicle->vehicle_no : '';

            $response[] = [
                'text' => $value->fullname. " | ".$vehicleNo,
                'id' => $value->id,
            ];
          }
            return response()->json($response);
        } catch (\Exception $e) {
            return $response;
        }
    }

    public function Add(Request $request) {
        ini_set("memory_limit","256M");
        ini_set('max_execution_time','300');

        $validator = Validator::make($request->all(), [ 
            'status' => 'required',
            'fullname' => 'required',
            'mobile' => 'required',
            'country' => 'required',
            'state' => 'required',
            'city' => 'required',
        ]);   

        if($validator->fails()) {          
            Session::flash('msg', 'Something Went Wrong. Please Try Again!!');
            Session::flash('cls', 'danger'); 
            return redirect()->back();              
         }

        try {

            if(isset($request->email) && valid_email($request->email)==true){
                $sqlEmail = "OR (email='".$request->email."' and email!='') ";
            }
            if(isset($request->mobile) && $request->mobile!=''){
                $sqlMobile = "OR (mobile='".$request->mobile."' and mobile!='') ";
            }

            $sql = "select id from acc_vendors WHERE (1=2 $sqlEmail $sqlMobile ) ";
            $countVendor = qry($sql);
            if(!empty($countVendor)){
                Session::flash('msg', 'This Email Or Mobile Number is Already Registered in Vendor.');
                Session::flash('cls', 'danger');
                return redirect()->back();
            }


            $sql = "select id from ".$this->tbl." WHERE (1=2 $sqlEmail $sqlMobile ) ";
            $count = qry($sql);
            if(!empty($count)){
                Session::flash('msg', 'Please Check Details Properly, This Email Or Mobile is Already Registered !');
                Session::flash('cls', 'danger');
                return redirect()->back();
            }

            $insertData = [
                'fullname' => $request->fullname,
                'pan_card' => isset($request->pan_card) ? $request->pan_card : NULL,
                'email' => isset($request->email) ? $request->email : NULL,
                'mobile' => $request->mobile,
                'password' => bcrypt(random_text(10)),
                'is_active' => ($request->status==0) ? 0 : 1,
                'address' => isset($request->address) ? $request->address : '', 
                'country' => isset($request->country) ? $request->country : 'India',
                'state' => isset($request->state) ? $request->state : NULL,
                'city' => isset($request->city) ? $request->city : NULL,
                'pincode' => isset($request->pincode) ? $request->pincode : NULL,
                'license_expiry' => isset($request->license_expiry) ? $request->license_expiry : NULL,
                'is_salary_based' => ($request->is_salary_based==1) ? 1 : 0,
                'salary_amount' => ($request->is_salary_based==1) ? intval($request->salary_amount) : 0,
            ];

            $upload_profile_pic = '';
            if($request->file('profile_pic')!=''){
                $upload_profile_pic = UploadImage($request->file('profile_pic'), constants('dir_name.driver'),"dp");
                $insertData['profile_pic'] = $upload_profile_pic;
            }

            $upload_aadhar_card_file = [];
            if(is_array($request->file('aadhar_card_file')) && !empty($request->file('aadhar_card_file'))){
                foreach($request->file('aadhar_card_file') as $fx) {
                    $upload_aadhar_card_file_name = UploadImage($fx, constants('dir_name.driver') , 'ac');
                    $upload_aadhar_card_file[] = $upload_aadhar_card_file_name;
                }
            $insertData['aadhar_card_file'] = implode(',', $upload_aadhar_card_file);
            }

            $upload_pan_card_file = [];
            if(is_array($request->file('pan_card_file')) && !empty($request->file('pan_card_file'))){
                foreach($request->file('pan_card_file') as $fx) {
                    $upload_pan_card_file_name = UploadImage($fx, constants('dir_name.driver') , 'ac');
                    $upload_pan_card_file[] = $upload_pan_card_file_name;
                }
            $insertData['pan_card_file'] = implode(',', $upload_pan_card_file);
            }


            $upload_license_file = [];
            if(is_array($request->file('license_file')) && !empty($request->file('license_file'))){
                foreach($request->file('license_file') as $fx) {
                    $upload_license_file_name = UploadImage($fx, constants('dir_name.driver') , 'lis');
                    $upload_license_file[] = $upload_license_file_name;
                }
            $insertData['license_file'] = implode(',', $upload_license_file);
            }
            


            $lastData = Driver::create($insertData);


            if(is_array($request->file('justimgO')) && !empty($request->file('justimgO'))){
                $m = 0;
                $upload_other_file_name = '';
                foreach($request->file('justimgO') as $fx) {
                    $upload_other_file_name = UploadImage($fx, constants('dir_name.driver') , 'ot');
                    
                    $filesdata = [
                        'img' => $upload_other_file_name,
                        'driver_id'  => $lastData->id ,
                        'img_type_name'  => isset($request->justnameO[$m]) ? $request->justnameO[$m] : 'Other',
                        'short_helper_name' => 'O' ,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ];
                                        
                    insert_data_id($this->tbl2,$filesdata);
                    $m++;
                }
            }

            if($lastData->id > 0){

                $createVendorData = [
                    'vendor_type' => constants('vendor_type.Driver.key'),
                    'vendor_about' => isset($request->vendor_about) ? trim($request->vendor_about) : NULL,
                    'fullname' => $request->fullname,
                    'firstname' => $request->firstname,
                    'lastname' => $request->lastname,
                    'email' => isset($request->email) ? trim($request->email) : NULL,
                    'mobile' => isset($request->mobile) ? trim($request->mobile) : NULL,
                    'admin_id' => Session::get('adminid'),
                    'is_active'=> constants('is_active_yes'),
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                    'address' => isset($request->address) ? trim($request->address) : NULL,
                    'landmark' => isset($request->landmark) ? trim($request->landmark) : NULL,
                    'country' => isset($request->country) ? trim($request->country) : 'India',
                    'state' => isset($request->state) ? trim($request->state) : NULL,
                    'city' => isset($request->city) ? trim($request->city) : NULL,
                    'pincode' => isset($request->pincode) ? trim($request->pincode) : NULL,
                ];
                $vendor_id = insert_data_id("acc_vendors",$createVendorData);

                if(isset($vendor_id)){
                    Driver::where('id', $lastData->id)->update(['vendor_id' => $vendor_id]);
                }

                Session::flash('msg', ' Added Successfully!');
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
            Session::flash('msg', 'Driver Not Created');
            Session::flash('cls', 'danger');
            return redirect()->back()->withError($e->getMessage());
        }
    }

    public function Update(Request $request) {
        ini_set("memory_limit","100M");
        ini_set('max_execution_time','300');

        $validator = Validator::make($request->all(), [ 
            'status' => 'required',
            'fullname' => 'required',
            'mobile' => 'required',
            'country' => 'required',
            'state' => 'required',
            'city' => 'required',
            'hid' => 'required|numeric',
            'vendor_id' => 'required|numeric',
        ]);   

        if($validator->fails()) {          
            Session::flash('msg', 'Please Try Again and Fill All Required Field !');
            Session::flash('cls', 'danger'); 
            return redirect()->back();              
        }

        $sql = 'SELECT id,mobile FROM tbl_drivers where mobile="'.$request->mobile.'" and mobile!="" and id!='.$request->hid.' LIMIT 1';
        $driver = qry($sql);

        if(!empty($driver)){
            Session::flash('msg', 'This Mobile is Already Registered !');
            Session::flash('cls', 'danger');
            return redirect()->back();
        }

            $updateData = [
                'fullname' => $request->fullname,
                'pan_card' => isset($request->pan_card) ? $request->pan_card : NULL,
                'email' => isset($request->email) ? $request->email : NULL,
                'mobile' => $request->mobile,
                'password' => bcrypt(random_text(10)),
                'is_active' => ($request->status==0) ? 0 : 1,
                'address' => isset($request->address) ? $request->address : '', 
                'country' => isset($request->country) ? $request->country : 'India',
                'state' => isset($request->state) ? $request->state : NULL,
                'city' => isset($request->city) ? $request->city : NULL,
                'pincode' => isset($request->pincode) ? $request->pincode : NULL,
                'license_expiry' => isset($request->license_expiry) ? $request->license_expiry : NULL,
                'is_salary_based' => ($request->is_salary_based==1) ? 1 : 0,
                'salary_amount' => ($request->is_salary_based==1) ? intval($request->salary_amount) : 0,
                'vendor_id' => $request->vendor_id,
            ];

       

            $upload_profile_pic = '';
            if($request->file('profile_pic')!=''){
                $upload_profile_pic = UploadImage($request->file('profile_pic'), constants('dir_name.driver'),"dp");
                $updateData['profile_pic'] = $upload_profile_pic;
                $this_img = isset($request->existing_img_profile_pic) ? $request->existing_img_profile_pic : '0';
                DeleteFile($this_img, constants('dir_name.driver'));
            }

            $upload_aadhar_card_file = [];
            if(is_array($request->file('aadhar_card_file')) && !empty($request->file('aadhar_card_file'))){
                foreach($request->file('aadhar_card_file') as $fx) {
                    $upload_aadhar_card_file_name = UploadImage($fx, constants('dir_name.driver') , 'ac');
                    $upload_aadhar_card_file[] = $upload_aadhar_card_file_name;
                }
            $updateData['aadhar_card_file'] = implode(',', $upload_aadhar_card_file);
            $this_img = isset($request->existing_img_aadhar_card_file) ? $request->existing_img_aadhar_card_file : '0';
                if(is_array($this_img) && !empty($this_img)){
                    foreach($this_img as $ti) {
                    DeleteFile($ti, constants('dir_name.driver'));
                    }
                }
            }

            $upload_pan_card_file = [];
            if(is_array($request->file('pan_card_file')) && !empty($request->file('pan_card_file'))){
                foreach($request->file('pan_card_file') as $fx) {
                    $upload_pan_card_file_name = UploadImage($fx, constants('dir_name.driver') , 'ac');
                    $upload_pan_card_file[] = $upload_pan_card_file_name;
                }
            $updateData['pan_card_file'] = implode(',', $upload_pan_card_file);
            $this_img = isset($request->existing_img_pan_card_file) ? $request->existing_img_pan_card_file : '0';
                if(is_array($this_img) && !empty($this_img)){
                    foreach($this_img as $ti) {
                    DeleteFile($ti, constants('dir_name.driver'));
                    }
                }
            }

            $upload_license_file = [];
            if(is_array($request->file('license_file')) && !empty($request->file('license_file'))){
                foreach($request->file('license_file') as $fx) {
                    $upload_license_file_name = UploadImage($fx, constants('dir_name.driver') , 'lis');
                    $upload_license_file[] = $upload_license_file_name;
                }
            $updateData['license_file'] = implode(',', $upload_license_file);
            $this_img = isset($request->existing_img_license_file) ? $request->existing_img_license_file : '0';
                if(is_array($this_img) && !empty($this_img)){
                    foreach($this_img as $ti) {
                    DeleteFile($ti, constants('dir_name.driver'));
                    }
                }
            }
           

        if(isset($request->password)){
            $updateData['password'] = bcrypt($request->password);
        }

        Driver::where('id',$request->hid)->update($updateData);


        if($request->status==constants('is_active_no')){
            AccessToken::where('usertype', constants('usertype.driver'))->where('user_id', $request->hid)->delete();
        }


        if(is_array($request->file('justimgO')) && !empty($request->file('justimgO'))){
                $m = 0;
                $upload_other_file_name = '';
            foreach($request->file('justimgO') as $fx) {
                    $upload_other_file_name = UploadImage($fx, constants('dir_name.driver') , 'ot');
                    
                    $filesdata = [
                        'img' => $upload_other_file_name,
                        'driver_id'  => $request->hid ,
                        'img_type_name'  => isset($request->justnameO[$m]) ? $request->justnameO[$m] : 'Other',
                        'short_helper_name' => 'O' ,
                    ];

                    DriverFile::create($filesdata);
                $m++;
            }
        }


        Session::flash('msg', 'Updated Successfully!');
        Session::flash('cls', 'success');
        return redirect()->back();
    }

    public function Add_OR_Update_Vehicle(Request $request) {
        ini_set("memory_limit","256M");
        ini_set('max_execution_time','300');
        
        $request->validate([
            'is_active'=>'required',
            'vehicle_no'=>'required',
            'hid'=>'required',
        ]);

        try {

            if($request->hid==0){

                $insertData = [
                'vehicle_no' => $request->vehicle_no,
                'driver_assigned_datetime' => date('Y-m-d H:i:s'),
                'driver_id' => 0,
                'about' => isset($request->about) ? $request->about : NULL,
                'is_active' => ($request->is_active==0) ? 0 : 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'insurance_expiry' => isset($request->insurance_expiry) ? Carbon::parse($request->insurance_expiry)->format('Y-m-d H:i:s') : NULL,
                'permit_expiry' => isset($request->permit_expiry) ? Carbon::parse($request->permit_expiry)->format('Y-m-d H:i:s') : NULL,
                'puc_expiry' => isset($request->puc_expiry) ? Carbon::parse($request->puc_expiry)->format('Y-m-d H:i:s') : NULL,
                ];

                $uploadfile = '';
                if($request->hasFile('vehicle_img')){
                    $uploadfile = UploadImage($request->file('vehicle_img'),constants('dir_name.vehicle'));
                    $insertData['vehicle_img'] = $uploadfile;
                }

            
            $upload_rc_book_file = [];
            if(is_array($request->file('rc_book_file')) && !empty($request->file('rc_book_file'))){
                foreach($request->file('rc_book_file') as $fx) {
                    $upload_rc_book_file_name = UploadImage($fx, constants('dir_name.vehicle') , 'rc');
                    $upload_rc_book_file[] = $upload_rc_book_file_name;
                }
            $insertData['rc_book_file'] = implode(',', $upload_rc_book_file);
            $this_img = isset($request->existing_img_rc_book_file) ? $request->existing_img_rc_book_file : '0';
                if(is_array($this_img) && !empty($this_img)){
                    foreach($this_img as $ti) {
                    DeleteFile($ti, constants('dir_name.vehicle'));
                    }
                }
            }


            $upload_insurance_file = [];
            if(is_array($request->file('insurance_file')) && !empty($request->file('insurance_file'))){
                foreach($request->file('insurance_file') as $fx) {
                    $upload_insurance_file_name = UploadImage($fx, constants('dir_name.vehicle') , 'ins');
                    $upload_insurance_file[] = $upload_insurance_file_name;
                }
            $insertData['insurance_file'] = implode(',', $upload_insurance_file);
            $this_img = isset($request->existing_img_insurance_file) ? $request->existing_img_insurance_file : '0';
                if(is_array($this_img) && !empty($this_img)){
                    foreach($this_img as $ti) {
                    DeleteFile($ti, constants('dir_name.vehicle'));
                    }
                }
            }
            

            $upload_permit_file = [];
            if(is_array($request->file('permit_file')) && !empty($request->file('permit_file'))){
                foreach($request->file('permit_file') as $fx) {
                    $upload_permit_file_name = UploadImage($fx, constants('dir_name.vehicle') , 'per');
                    $upload_permit_file[] = $upload_permit_file_name;
                }
            $insertData['permit_file'] = implode(',', $upload_permit_file);
            $this_img = isset($request->existing_img_permit_file) ? $request->existing_img_permit_file : '0';
                if(is_array($this_img) && !empty($this_img)){
                    foreach($this_img as $ti) {
                    DeleteFile($ti, constants('dir_name.vehicle'));
                    }
                }
            }

            $upload_puc_file = [];
            if(is_array($request->file('puc_file')) && !empty($request->file('puc_file'))){
                foreach($request->file('puc_file') as $fx) {
                    $upload_puc_file_name = UploadImage($fx, constants('dir_name.vehicle') , 'puc');
                    $upload_puc_file[] = $upload_puc_file_name;
                }
            $insertData['puc_file'] = implode(',', $upload_puc_file);
            $this_img = isset($request->existing_img_puc_file) ? $request->existing_img_puc_file : '0';
                if(is_array($this_img) && !empty($this_img)){
                    foreach($this_img as $ti) {
                    DeleteFile($ti, constants('dir_name.vehicle'));
                    }
                }
            }


                $id = insert_data_id($this->tbl3,$insertData);

                if($id > 0){
                    Session::flash('msg', ' Added Successfully!');
                    Session::flash('cls', 'success');
                }
                else
                {
                Session::flash('msg', 'Something Went Wrong. Please Try Again!!');
                Session::flash('cls', 'danger');
                }
                return redirect()->back();
            }
            else if($request->hid>0){


                $updateData = [
                'vehicle_no' => $request->vehicle_no,
                'about' => isset($request->about) ? $request->about : NULL,
                'is_active' => ($request->is_active==0) ? 0 : 1,
                'insurance_expiry' => isset($request->insurance_expiry) ? Carbon::parse($request->insurance_expiry)->format('Y-m-d H:i:s') : NULL,
                'permit_expiry' => isset($request->permit_expiry) ? Carbon::parse($request->permit_expiry)->format('Y-m-d H:i:s') : NULL,
                'puc_expiry' => isset($request->puc_expiry) ? Carbon::parse($request->puc_expiry)->format('Y-m-d H:i:s') : NULL,
                ];

            $upload_rc_book_file = [];
            if(is_array($request->file('rc_book_file')) && !empty($request->file('rc_book_file'))){
                foreach($request->file('rc_book_file') as $fx) {
                    $upload_rc_book_file_name = UploadImage($fx, constants('dir_name.vehicle') , 'rc');
                    $upload_rc_book_file[] = $upload_rc_book_file_name;
                }
            $updateData['rc_book_file'] = implode(',', $upload_rc_book_file);
            $this_img = isset($request->existing_img_rc_book_file) ? $request->existing_img_rc_book_file : '0';
                if(is_array($this_img) && !empty($this_img)){
                    foreach($this_img as $ti) {
                    DeleteFile($ti, constants('dir_name.vehicle'));
                    }
                }
            }


            $upload_insurance_file = [];
            if(is_array($request->file('insurance_file')) && !empty($request->file('insurance_file'))){
                foreach($request->file('insurance_file') as $fx) {
                    $upload_insurance_file_name = UploadImage($fx, constants('dir_name.vehicle') , 'ins');
                    $upload_insurance_file[] = $upload_insurance_file_name;
                }
            $updateData['insurance_file'] = implode(',', $upload_insurance_file);
            $this_img = isset($request->existing_img_insurance_file) ? $request->existing_img_insurance_file : '0';
                if(is_array($this_img) && !empty($this_img)){
                    foreach($this_img as $ti) {
                    DeleteFile($ti, constants('dir_name.vehicle'));
                    }
                }
            }
            

            $upload_permit_file = [];
            if(is_array($request->file('permit_file')) && !empty($request->file('permit_file'))){
                foreach($request->file('permit_file') as $fx) {
                    $upload_permit_file_name = UploadImage($fx, constants('dir_name.vehicle') , 'per');
                    $upload_permit_file[] = $upload_permit_file_name;
                }
            $updateData['permit_file'] = implode(',', $upload_permit_file);
            $this_img = isset($request->existing_img_permit_file) ? $request->existing_img_permit_file : '0';
                if(is_array($this_img) && !empty($this_img)){
                    foreach($this_img as $ti) {
                    DeleteFile($ti, constants('dir_name.vehicle'));
                    }
                }
            }

            $upload_puc_file = [];
            if(is_array($request->file('puc_file')) && !empty($request->file('puc_file'))){
                foreach($request->file('puc_file') as $fx) {
                    $upload_puc_file_name = UploadImage($fx, constants('dir_name.vehicle') , 'puc');
                    $upload_puc_file[] = $upload_puc_file_name;
                }
            $updateData['puc_file'] = implode(',', $upload_puc_file);
            $this_img = isset($request->existing_img_puc_file) ? $request->existing_img_puc_file : '0';
                if(is_array($this_img) && !empty($this_img)){
                    foreach($this_img as $ti) {
                    DeleteFile($ti, constants('dir_name.vehicle'));
                    }
                }
            }

                Vehicle::where('id',$request->hid)->update($updateData);
                Session::flash('msg', ' Updated Successfully!');
                Session::flash('cls', 'success');
                return redirect()->back();
            }
            else
            {
                Session::flash('msg', 'Something Went Wrong. Please Try Again!!');
                Session::flash('cls', 'danger');
                return redirect()->back();
            }

        } catch (\Exception $e) {
            Session::flash('msg', 'Something Went Wrong. Please Try Again!!');
            Session::flash('cls', 'danger');
           return redirect()->back()->withError($e->getMessage());
        }
    }

    public function update_files(Request $request) {
        
            $uploadfile = [];
            if($request->file('otherfile')!=''){
                foreach ($request->file('otherfile') as  $kk) {
                    $uploadfilex = UploadImage($kk, constants('dir_name.driver'),"ot");
                    $uploadfile[] = $uploadfilex;
                }
            }
            
             $filesdata = [
                'img' => implode(',', $uploadfile),
                'driver_id'  => $request->did,
                'img_type_name'  => $request->img_type_name ,
                'short_helper_name' => $request->fshort ,
                'updated_at' => date('Y-m-d H:i:s'),
            ];
            update_data($this->tbl2, $filesdata ,['id' => $request->fid ]);
            

            if(isset($request->existing_img0)){
                $finalArray = isset($request->existing_img0) ? explode(',', $request->existing_img0) : [];
                if(is_array($finalArray) && count($finalArray)>0){
                foreach ($finalArray as $key => $value) {
                     DeleteFile($value, constants('dir_name.driver'));
                    }
                }
            }
            Session::flash('msg', 'Updated Successfully!');
            Session::flash('cls', 'success');
            return redirect()->back();
    }

    public function delete_files(Request $request) {
        DriverFile::where('id',$request->id)->delete();
            
        if(isset($request->img)){
            $finalArray = isset($request->img) ? explode(',', $request->img) : [];
            if(is_array($finalArray) && count($finalArray)>0){
                foreach ($finalArray as $key => $value) {
                     DeleteFile($value, constants('dir_name.driver'));
                }
            }
        }

        $response = ['msg' => ' Deleted Successfully!', 'success' => 1 , 'data' => 1 ];
        return response()->json($response);
    }


    public function getdata_payroll_orders(Request $request){

        $columns = array(          
            0 => 'o.bigdaddy_lr_number',
            1 => 'o.transporter_lr_number',
            2 => 'o.final_cost',
            3 => 'o.order_driver_trip_amount',
            4 => 'o.created_at',
        );

        $order_date_Sql = '';
        $driver_ids_Sql = '';
        $driver_assign_type_Sql = '';

        if(isset($request->filter_global_order_date) && $request->filter_global_order_date!=''){
            $date_start = explode(' - ', $request->filter_global_order_date);
            $stop_date = date('Y-m-d', strtotime($date_start[1] . ' +0 day'));
            $order_date_Sql = " and o.created_at BETWEEN '".$date_start[0]."' and '".$stop_date."' ";
        }

        if(isset($request->select_driver_id) && is_array($request->select_driver_id) && !empty($request->select_driver_id)){
            $select_driver_ids_Status = "'" . implode ( "', '", $request->select_driver_id ) . "'";
            $driver_ids_Sql =  " and o.driver_id IN(".$select_driver_ids_Status.") ";
        }

        if(isset($request->driver_assign_type) && is_array($request->driver_assign_type) && !empty($request->driver_assign_type)){
            $driver_assign_type_Status = "'" . implode ( "', '", $request->driver_assign_type ) . "'";
            $driver_assign_type_Sql =  " and o.order_driver_trip_type IN(".$driver_assign_type_Status.") ";
        }


        $sql = "select o.*, inv.invoice_number,inv.invoice_file,inv.created_at as invcreated_at
        FROM ".$this->tbl4." o
        LEFT JOIN ".$this->tbl4." d ON d.id=o.driver_id
        LEFT JOIN tbl_invoice inv ON inv.id=o.invoice_id
        WHERE o.is_active!='2' ".$order_date_Sql.$driver_ids_Sql.$driver_assign_type_Sql." ";
        $query = qry($sql);

        $totalData = count($query);
        $totalFiltered = $totalData;

        if(!empty($request['search']['value'])) { 
            $searchString = str_replace("'", "", $request['search']['value'] );  
            $searchString = "'%" . str_replace(",", "','", $searchString). "%'"; 

            $sql .= " and ( o.id LIKE " . $searchString;
            $sql .= " OR o.bigdaddy_lr_number LIKE " . $searchString;
            $sql .= " OR o.pickup_location LIKE " . $searchString;
            $sql .= " OR o.drop_location LIKE " . $searchString;
            $sql .= " OR o.contact_person_phone_number LIKE " . $searchString;
            $sql .= " OR o.contact_person_name LIKE " . $searchString;
            $sql .= " OR o.user_id LIKE " . $searchString;
            $sql .= " OR d.fullname LIKE " . $searchString;
            $sql .= " OR o.pickup_latitude LIKE " . $searchString;
            $sql .= " OR o.pickup_longitude LIKE " . $searchString;
            $sql .= " OR o.drop_latitude LIKE " . $searchString;
            $sql .= " OR o.drop_longitude LIKE " . $searchString;
            $sql .= " OR o.transporter_lr_number LIKE " . $searchString;
            $sql .= " OR inv.invoice_number LIKE " . $searchString;
            $sql .= " OR o.if_cheque_number LIKE " . $searchString;
            $sql .= " OR o.if_transaction_number LIKE " . $searchString;
            $sql .= " OR o.payment_comment LIKE " . $searchString;
            $sql .= " OR o.order_driver_trip_amount LIKE " . $searchString;
            $sql .= " OR o.final_cost LIKE " . $searchString . ")";
        }


        $query = qry($sql);
        $totalFiltered = count($query);

        $sql .= " ORDER BY " . $columns[$request->order[0]['column']] . "   " . $request->order[0]['dir'] . " LIMIT " . $request->start . " ," . $request->length . "   ";  

        $query = qry($sql);

        $data = array();

        $cnts = $request->start + 1;
        foreach ($query as $row) {

            $invoiceBtn = '';
            if($row->invoice_number!=""){
                $invoiceBtn = "<br><a target='_blank' href='".sendPath().constants('dir_name.invoice').'/'.$row->invoice_file."'><span title='view invoice #".$row->invoice_number."' data-filename=".$row->invoice_file." data-folder=".constants('dir_name.invoice')." class='shadow-none badge badge-secondary spancursorcls filedownloaditno'>".$row->invoice_number." </span></a>";
            }


            $payrolltype_status = "<br><span class='shadow-none badge badge-info' >". constants('driver_assign_type_value.'.$row->order_driver_trip_type.'.name')."</span>";

            $nestedData = array();
            $nestedData[] = $row->bigdaddy_lr_number.$invoiceBtn;
            $nestedData[] = $row->transporter_lr_number;
            $nestedData[] = "DeliveryCharge :".$row->final_cost."<br>"." ReDeliveryCharge :".$row->redeliver_charge."<br>"." MinOrderValueCharge :".$row->min_order_value_charge."<br>"." Discount :".$row->discount."<br>"." PaymentDiscount :".$row->payment_discount;
            $nestedData[] = $row->order_driver_trip_amount.$payrolltype_status;


            $nestedData[] = Carbon::parse($row->created_at)->format('d-m-Y')."<br>".Carbon::parse($row->created_at)->format('h:i:s A');

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




    public function getdataTimingreportsOrders(Request $request){
        
        $columns = array(          
            0 => 'o.bigdaddy_lr_number',
            1 => 'o.transporter_lr_number',
            2 => 'u.fullname',
            3 => 'o.pickup_location',
            4 => 'o.driver_id',
            5 => 'o.created_at',
        );


        $driveraction_datetime_Sql = '';
        $driver_ids_Sql = '';
        $driver_arrangement_type_Sql = '';

        if(isset($request->timingreports_filter_global_order_date) && $request->timingreports_filter_global_order_date!=''){
            $date_start = explode(' - ', $request->timingreports_filter_global_order_date);
            $stop_date = date('Y-m-d', strtotime($date_start[1] . ' +0 day'));
            $driveraction_datetime_Sql = " and doa.driveraction_datetime BETWEEN '".$date_start[0]."' and '".$stop_date."' ";
        }

        if(isset($request->driver_id) && is_array($request->driver_id) && !empty($request->driver_id)){
            $select_driver_ids_Status = "'" . implode ( "', '", $request->driver_id ) . "'";
            $driver_ids_Sql =  " and doa.driver_id IN(".$select_driver_ids_Status.") ";
        }

        if(isset($request->driver_arrangement_type_value) && is_array($request->driver_arrangement_type_value) && !empty($request->driver_arrangement_type_value)){
            $driver_arrangement_type_value_Status = "'" . implode ( "', '", $request->driver_arrangement_type_value ) . "'";
            $driver_arrangement_type_Sql =  " and doa.arrangement_type IN(".$driver_arrangement_type_value_Status.") ";
        }
        
        
        $sql = "select o.*,doa.orderaction_datetime as doaorderaction_datetime, doa.arrangement_type as doaarrangement_type,doa.id as doaid,doa.difference_seconds,doa.is_early_fulfilled,inv.invoice_number,inv.invoice_file,inv.created_at as invcreated_at, (select name from tbl_short_helper where short=o.payment_status and type='payment_status') as payment_status, (select classhtml from tbl_short_helper where short=o.payment_status and type='payment_status') as classhtml,d.fullname as driver_name,u.fullname as ufullname,u.business_name as ubusiness_name,u.mobile as umobile,sh.name as shname, sh.classhtml as shclasshtml from ".$this->tbl4." o INNER JOIN tbl_users u ON u.id=o.user_id LEFT JOIN tbl_drivers d ON d.id=o.driver_id LEFT JOIN tbl_short_helper sh ON sh.short=o.status and sh.type='order_status_type' LEFT JOIN tbl_invoice inv ON inv.id=o.invoice_id INNER JOIN tbl_driver_order_arrangement doa ON doa.order_id=o.id and doa.is_completed=".constants('confirmation.yes')." WHERE o.is_active=".constants('is_active_yes')." and doa.is_active=".constants('is_active_yes')." ".$driveraction_datetime_Sql.$driver_ids_Sql.$driver_arrangement_type_Sql."  ";

       


        $query = qry($sql);
        $totalData = count($query);
        $totalFiltered = $totalData;

        if(!empty($request['search']['value'])) {  

            $searchString = str_replace("'", "", $request['search']['value'] );  
            $searchString = "'%" . str_replace(",", "','", $searchString). "%'"; 

            $sql .= " and ( o.id LIKE " . $searchString;
            $sql .= " OR o.bigdaddy_lr_number LIKE " . $searchString;
            $sql .= " OR o.pickup_location LIKE " . $searchString;
            $sql .= " OR o.drop_location LIKE " . $searchString;
            $sql .= " OR o.contact_person_phone_number LIKE " . $searchString;
            $sql .= " OR o.contact_person_name LIKE " . $searchString;
            $sql .= " OR d.fullname LIKE " . $searchString;
            $sql .= " OR o.pickup_latitude LIKE " . $searchString;
            $sql .= " OR o.pickup_longitude LIKE " . $searchString;
            $sql .= " OR o.drop_latitude LIKE " . $searchString;
            $sql .= " OR o.drop_longitude LIKE " . $searchString;
            $sql .= " OR o.transporter_lr_number LIKE " . $searchString;
            $sql .= " OR inv.invoice_number LIKE " . $searchString;
            $sql .= " OR u.business_name LIKE " . $searchString;
            $sql .= " OR u.mobile LIKE " . $searchString;
            $sql .= " OR u.fullname LIKE " . $searchString;
            $sql .= " OR u.business_name LIKE " . $searchString;
            $sql .= " OR u.GST_number LIKE " . $searchString;
            $sql .= " OR u.email LIKE " . $searchString;
            $sql .= " OR u.mobile LIKE " . $searchString;
            $sql .= " OR u.pan_no LIKE " . $searchString;
            $sql .= " OR o.final_cost LIKE " . $searchString . ")";
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

            $invoiceBtn = '';
            if($row->invoice_number!=""){
                $invoiceBtn = "<br><a target='_blank' href='".sendPath().constants('dir_name.invoice').'/'.$row->invoice_file."'><span title='view invoice #".$row->invoice_number."' data-filename=".$row->invoice_file." data-folder=".constants('dir_name.invoice')." class='shadow-none badge badge-secondary spancursorcls filedownloaditno'>".$row->invoice_number." </span></a>";
            }

            $logsBtn = "<span class='admid-select-color shadow-none badge badge-info' onclick='seeLogs(".$row->id.")' ><i class='far fa-eye'></i></span>";
            $view_order = "<br><a target='_blank' href='".route('view-order')."/".$row->id."'><span class='shadow-none badge badge-dark'>View Order</span></a>";

            $arrangement_type = "<br><span class='shadow-none badge badge-".constants('arrangement_typeName.'.$row->doaarrangement_type.'.classhtml')."' >".constants('arrangement_typeName.'.$row->doaarrangement_type.'.name')."</span>";

            $nestedData = array();
            $nestedData[] = $row->bigdaddy_lr_number." ".$logsBtn.$view_order."<br>".Carbon::parse($row->created_at)->format('d-m-Y')."<br>".Carbon::parse($row->created_at)->format('h:i:s A');
            $nestedData[] = $row->transporter_lr_number;
            $nestedData[] = $row->ufullname."<br>". $row->ubusiness_name."<br>". $row->umobile.$invoiceBtn;
            $nestedData[] = "Pickup Address<br>".$row->pickup_location."<br><br>"."Drop Address<br>".$row->drop_location;
            $nestedData[] = $row->driver_name.$arrangement_type;


            
            if($row->difference_seconds>0 && $row->is_early_fulfilled==1){
                $timings = "<b class='greencolorcls'>".secondsToTimings($row->difference_seconds)."</b>";

            }
            else if($row->difference_seconds>0 && $row->is_early_fulfilled==0){
                $timings = "<b class='redcolorcls'>".secondsToTimings($row->difference_seconds)."</b>";
            }
            else
            {
                $timings = "";
            }
            
        
            $nestedData[] = $timings;
            $nestedData[] = $row->doaid;
            $nestedData[] = Carbon::parse($row->doaorderaction_datetime)->format('d-m-Y')." ".Carbon::parse($row->doaorderaction_datetime)->format('h:i:s A');
            //$nestedData['DT_RowId'] = "r" . $row->id;
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


    public function exportExcelDriverTimingReportsOrders(Request $request) {
        try {

        ini_set('memory_limit','24M');
        ini_set('max_execution_time', 600);

        $validator = Validator::make($request->all(), [ 
            'timingreports_filter_global_order_date' => 'required',
        ]);
        if($validator->fails()) {  
            Session::flash('cls', 'danger');
            Session::flash('msg', 'Missing Required Value.');
            return redirect()->back();
        }
        

        $driveraction_datetime_Sql = '';
        $driver_ids_Sql = '';
        $driver_arrangement_type_Sql = '';

        if(isset($request->timingreports_filter_global_order_date) && $request->timingreports_filter_global_order_date!=''){
            $date_start = explode(' - ', $request->timingreports_filter_global_order_date);
            $stop_date = date('Y-m-d', strtotime($date_start[1] . ' +0 day'));
            $driveraction_datetime_Sql = " and doa.driveraction_datetime BETWEEN '".$date_start[0]."' and '".$stop_date."' ";
        }

        if(isset($request->driver_id) && is_array($request->driver_id) && !empty($request->driver_id)){
            $select_driver_ids_Status = "'" . implode ( "', '", $request->driver_id ) . "'";
            $driver_ids_Sql =  " and doa.driver_id IN(".$select_driver_ids_Status.") ";
        }

        if(isset($request->driver_arrangement_type_value) && is_array($request->driver_arrangement_type_value) && !empty($request->driver_arrangement_type_value)){
            $driver_arrangement_type_value_Status = "'" . implode ( "', '", $request->driver_arrangement_type_value ) . "'";
            $driver_arrangement_type_Sql =  " and doa.arrangement_type IN(".$driver_arrangement_type_value_Status.") ";
        }


        $sql = "select count(o.id) as cnt FROM ".$this->tbl4." o LEFT JOIN ".$this->tbl." d ON d.id=o.driver_id LEFT JOIN tbl_invoice inv ON inv.id=o.invoice_id INNER JOIN tbl_driver_order_arrangement doa ON doa.order_id=o.id and doa.is_completed=".constants('confirmation.yes')." WHERE o.is_active=".constants('is_active_yes')." and doa.is_active=".constants('is_active_yes')." ".$driveraction_datetime_Sql.$driver_ids_Sql.$driver_arrangement_type_Sql."  ";
        $orderCount = qry($sql);


        if(isset($orderCount[0]->cnt) && $orderCount[0]->cnt>10000) {
            Session::flash('cls', 'danger');
            Session::flash('msg', 'There are more than 10000 records in Excel, Please Export less than 10000 records.');
            return redirect()->back();
        }

        $sql = "select 
        o.bigdaddy_lr_number as BigDaddyLRNumber,
        o.transporter_lr_number as TranspoterLRNumber,
        inv.invoice_number as InvoiceNumber,
        DATE_FORMAT(o.created_at, '%d-%m-%Y') as OrderCreatedDate,
        (SELECT CASE
        WHEN doa.arrangement_type = 2 THEN 'Deliver'
        WHEN doa.arrangement_type = 1 THEN 'Pickup'
        WHEN doa.arrangement_type = 0 THEN 'Undeliver'
        ELSE 'NotFound'
        END) as OrderType,
        (SELECT CASE
        WHEN doa.is_early_fulfilled = 0 THEN 'LateReached'
        ELSE 'EarlyReached'
        END) as DriverOrderFullFillment,
        (doa.difference_seconds/60) as DriverFullFillmenMinutes,
        d.fullname as DriverName
        FROM ".$this->tbl4." o
        LEFT JOIN ".$this->tbl." d ON d.id=o.driver_id
        LEFT JOIN tbl_invoice inv ON inv.id=o.invoice_id
        INNER JOIN tbl_driver_order_arrangement doa ON doa.order_id=o.id and doa.is_completed=".constants('confirmation.yes')." WHERE o.is_active=".constants('is_active_yes')." and doa.is_active=".constants('is_active_yes')." ".$driveraction_datetime_Sql.$driver_ids_Sql.$driver_arrangement_type_Sql."  ";
        $orderLists = qry($sql);
        $orderLists = json_decode(json_encode($orderLists), true);

        if(count($orderLists)>0){
            $column_name =  array_keys($orderLists[0]);
            $export = new JustExcelExport($orderLists, $column_name );
            return Excel::download($export, "OrderTimingReport_".date('d-M-Y-H-i-sA').'.xlsx'); 
        }
        else
        {
            Session::flash('cls', 'danger');
            Session::flash('msg', 'No Data Found For Excel!!');
            return redirect()->back();
        }
        } catch (\Exception $e) {
            Log::error($e);
            Session::flash('msg', 'Excel Not Exported - '.$e->getMessage());
            Session::flash('cls', 'danger');
            return redirect()->back();
        }
    }

    public function exportExcelDriverPayrollOrders(Request $request) {
        try {

        ini_set('memory_limit','24M');
        ini_set('max_execution_time', 600);

        $validator = Validator::make($request->all(), [ 
            'filter_global_order_date' => 'required',
        ]);
        if($validator->fails()) {  
            Session::flash('cls', 'danger');
            Session::flash('msg', 'Missing Required Value.');
            return redirect()->back();
        }
        

        $order_date_Sql = '';
        $driver_ids_Sql = '';
        $driver_assign_type_Sql = '';

        if(isset($request->filter_global_order_date) && $request->filter_global_order_date!=''){
            $date_start = explode(' - ', $request->filter_global_order_date);
            $stop_date = date('Y-m-d', strtotime($date_start[1] . ' +0 day'));
            $order_date_Sql = " and o.created_at BETWEEN '".$date_start[0]."' and '".$stop_date."' ";
        }

        if(isset($request->select_driver_from_select2_dropdown_id) && is_array($request->select_driver_from_select2_dropdown_id) && !empty($request->select_driver_from_select2_dropdown_id)){
            $select_driver_ids_Status = "'" . implode ( "', '", $request->select_driver_from_select2_dropdown_id ) . "'";
            $driver_ids_Sql =  " and o.driver_id IN(".$select_driver_ids_Status.") ";
        }

        if(isset($request->driver_assign_type) && is_array($request->driver_assign_type) && !empty($request->driver_assign_type)){
            $driver_assign_type_Status = "'" . implode ( "', '", $request->driver_assign_type ) . "'";
            $driver_assign_type_Sql =  " and o.order_driver_trip_type IN(".$driver_assign_type_Status.") ";
        }


        $sql = "select count(o.id) as cnt
        FROM ".$this->tbl4." o
        LEFT JOIN ".$this->tbl4." d ON d.id=o.driver_id
        LEFT JOIN tbl_invoice inv ON inv.id=o.invoice_id
        WHERE o.is_active!='2' ".$order_date_Sql.$driver_ids_Sql.$driver_assign_type_Sql." ";
        $orderCount = qry($sql);


        if(isset($orderCount[0]->cnt) && $orderCount[0]->cnt>10000) {
            Session::flash('cls', 'danger');
            Session::flash('msg', 'There are more than 10000 records in Excel, Please Export less than 10000 records.');
            return redirect()->back();
        }

        $sql = "select 
        o.bigdaddy_lr_number as BigDaddyLRNumber,
        o.transporter_lr_number as TranspoterLRNumber,
        o.final_cost as DeliveryCharge,
        o.min_order_value_charge as MinOrderValueCharge,
        o.redeliver_charge as RedeliverCharge,
        o.discount as Discount,
        o.payment_discount as PaymentDiscount,
        inv.invoice_number as InvoiceNumber,
        o.order_driver_trip_amount as DriverChargeForPayRoll,
        o.order_driver_trip_type as PayRollType,
        DATE_FORMAT(o.created_at, '%d-%m-%Y') as OrderCreatedDate

        FROM ".$this->tbl4." o
        LEFT JOIN ".$this->tbl." d ON d.id=o.driver_id
        LEFT JOIN tbl_invoice inv ON inv.id=o.invoice_id
        WHERE o.is_active!='2' ".$order_date_Sql.$driver_ids_Sql.$driver_assign_type_Sql." ";
        $orderLists = qry($sql);
        $orderLists = json_decode(json_encode($orderLists), true);

        if(count($orderLists)>0){
            $column_name =  array_keys($orderLists[0]);
            $export = new JustExcelExport($orderLists, $column_name );
            return Excel::download($export, "Orders_".date('d-M-Y-H-i-sA').'.xlsx'); 
        }
        else
        {
            Session::flash('cls', 'danger');
            Session::flash('msg', 'No Data Found For Excel!!');
            return redirect()->back();
        }
        } catch (\Exception $e) {
            Log::error($e);
            Session::flash('msg', 'Excel Not Exported - '.$e->getMessage());
            Session::flash('cls', 'danger');
            return redirect()->back()->withError($e->getMessage());
        }
    }




























}
