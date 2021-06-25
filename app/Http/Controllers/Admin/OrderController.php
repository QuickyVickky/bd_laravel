<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderFile;
use App\Models\OrderLog;
use App\Models\OrderReview;
use App\Models\OrderParcel;
use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Models\GoodsType;
use App\Models\Driver;
use App\Models\ShortHelper;
use App\Models\Vehicle;
use App\Models\CustomerUploadedFileOrder;
use App\Models\AccountManage;
use App\Models\Invoice;
use App\Models\OrderArrange;
use App\Models\AccVendor;
use App\Models\Transaction;
date_default_timezone_set('Asia/Kolkata');
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Session;
use App\Http\Controllers\Client\Api\customer\RazorPayController;


class OrderController extends Controller
{
    public $tbl = "tbl_orders";
    public $tbl2 = "tbl_order_parcel_details";
    public $tbl3 = "tbl_invoice";
    
    public function index(Request $request) {
    	$data = ['tbl' => $this->tbl, 'control' => 'Orders' ];
		return view('admin.order.list')->with($data);
	}

    public function delivered_index(Request $request){
        $payment_statusTypeData = ShortHelper::where('is_active', constants('is_active_yes'))->where('type', 'payment_status')->limit(50)->get();
        $data = ['tbl' => $this->tbl, 'control' => 'Delivered Orders', 'payment_status' => $payment_statusTypeData , ];
        return view('admin.order.delivered_list')->with($data);
    }

    public function undelivered_index(Request $request){
        $data = ['tbl' => $this->tbl, 'control' => 'Undelivered Orders' ];
        return view('admin.order.undelivered_list')->with($data);
    }

    public function tobeassigned_index(Request $request){
        $data = ['tbl' => $this->tbl, 'control' => 'To Be Assigned Orders' ];
        return view('admin.order.tobeassigned_list')->with($data);
    }
	
    public function tobeapproved_index(Request $request){
        $data = ['tbl' => $this->tbl, 'control' => 'To Be Approve Orders', 'control1' => 'Requested Orders' , 'control2' => 'Approved Orders' ];
        return view('admin.order.tobeapproved_list')->with($data);
    }

	public function assigned_index(Request $request){
        $data = ['tbl' => $this->tbl, 'control' => 'Assigned Orders' ];
        return view('admin.order.assigned_list')->with($data);
    }
	
	public function cancelled_index(Request $request){
        $data = ['tbl' => $this->tbl, 'control' => 'Cancelled Orders' ];
        return view('admin.order.cancelled_list')->with($data);
    }

    public function lrupload_index(Request $request){
        $data = ['tbl' => $this->tbl, 'control' => 'LR Uploads' ];
        return view('admin.order.lrupload')->with($data);
    }

    public function orderTrackingIndex(Request $request) {
        $data = ['tbl' => $this->tbl, 'control' => 'Order Tracking' ];
        return view('admin.order.orderlisttracking')->with($data);
    }

    public function add_index(Request $request){
        if_allowedRoute('order-add');
        $lastorder = []; $R = []; $address = [];
        if(Session::get("lastorderid")!=''){
            $sql = "Select * from tbl_orders WHERE is_active!='2' AND id=".Session::get("lastorderid")." limit 1 ";
            $lastorder = qry($sql);
            if(count($lastorder)>0){
            $sql = "Select * from tbl_users WHERE is_active!='2' AND id=".$lastorder[0]->user_id." limit 1 ";
            $R = qry($sql);
            $sql = "Select * from tbl_address WHERE is_active!='2' AND user_id=".$lastorder[0]->user_id." ORDER BY is_default DESC limit 1 ";
            $address = qry($sql);
            }
        }

        $sql = 'SELECT * FROM tbl_goods_type where is_active="0" limit 250';
        $goods_type = qry($sql);
        $data = ['tbl' => $this->tbl, 'control' => 'Add Order', 'goods_type' => $goods_type,  'lastorder' => $lastorder ,  'address' => $address,  'userdata' => $R ];
        return view('admin.order.add')->with($data);
    }

    public function view_order($orderId=0, Request $request){
        if_allowedRoute('view-order');
        $orderId = trim(intval($orderId));

        $sql = 'SELECT o.*,inv.invoice_number,inv.invoice_file,inv.created_at as invcreated_at, v.vehicle_no,shps.name as shpsname,shpt.name as shptname,d.id as did,d.fullname as dfullname,d.email as demail,d.mobile as dmobile, shost.details as shostdetails, shost.classhtml as shostclasshtml FROM '.$this->tbl.' o LEFT JOIN tbl_drivers d ON d.id=o.driver_id LEFT JOIN tbl_vehicles v ON v.id=o.vehicle_id LEFT JOIN tbl_short_helper shps ON shps.short=o.payment_status and shps.type="payment_status" LEFT JOIN tbl_short_helper shpt ON shpt.short=o.payment_type and shpt.type="payment_type" LEFT JOIN '.$this->tbl3.' inv ON inv.id=o.invoice_id LEFT JOIN tbl_short_helper shost ON shost.short=o.status and shost.type="order_status_type" WHERE o.id='.$orderId.' and o.is_active!="2"  ';
        $order = qry($sql);

        $sql = 'SELECT op.*,(select gt.name from tbl_goods_type gt where gt.id=op.goods_type_id  limit 1) as goods_type_name FROM tbl_order_parcel_details op WHERE op.order_id='.$orderId.' and op.is_active="0"   ';
        $parcel = qry($sql);

        $user_id = isset($order[0]->user_id) ? $order[0]->user_id : 0;
        $sql = 'SELECT u.* FROM tbl_users u WHERE u.id='.$user_id.'  limit 1 ';
        $user = qry($sql);

        $dataOrderFile = OrderFile::where('order_id',$orderId)->where('is_active',constants('is_active_yes'))->limit(250)->get();

        $data = ['tbl' => $this->tbl, 'control' => 'View Order', 'order' => $order , 'parcel' => $parcel , 'user' => $user, 'orderId' => $orderId , 'dataOrderFile' => $dataOrderFile];
        return view('admin.order.view')->with($data);
    }

    public function edit_order($orderId=0, Request $request){
        if_allowedRoute('edit-order');
        $orderId = trim(intval($orderId));

        $orderData = Order::with([
            'order_status',
            'customer' => function($qryCustomer) {
                $qryCustomer->with([
                    'customerAddressFirst' => function($qryAddress) {
                        $qryAddress->orderBy('is_default','DESC');
                    },
                ]);
                $qryCustomer->where('is_active', constants('is_active_yes'));
            },
            'orderParcel' => function($qryOrderParcel) {
                $qryOrderParcel->with([
                    'goodsType' => function($qryGoodsType) {
                        $qryGoodsType->where('is_active','!=', 2);
                    },
                ]);
                $qryOrderParcel->where('is_active', constants('is_active_yes'));
            },
            'orderFile' => function($qryOrderFile) {
                $qryOrderFile->where('is_active',constants('is_active_yes'));
            },
            'invoice' => function($qryInvoice) {
                $qryInvoice->where('is_active',constants('is_active_yes'));
            },
        ])
        ->where('is_active','!=', 2)
        ->where('id', $orderId)
        ->first();
    
        $goods_type = GoodsType::where('is_active','!=', 2)->skip(0)->take(500)->get();

        $data = ['tbl' => $this->tbl, 'control' => 'Edit Order', 'order' => $orderData , 'orderId' => $orderId , 'goods_type' => $goods_type ];
        return view('admin.order.edit')->with($data);
    }

    public function getdata_lrupload(Request $request){
        $columns = array(          
            0 => 'cuf.created_at',
            1 => 'cuf.user_id',
            2 => 'cuf.lrfile',
            3 => 'cuf.is_active',
        );

        $statusSql = ' and cuf.is_active="'.$request->status.'" ';
        
        $sql = "select cuf.*,u.fullname as ufullname,u.business_name as ubusiness_name,u.mobile as umobile from tbl_customer_uploaded_files cuf INNER JOIN tbl_users u ON u.id=cuf.user_id WHERE 1=1 $statusSql ";
        

        $query = qry($sql);
        $totalData = count($query);
        $totalFiltered = $totalData;

        if (!empty($request['search']['value'])) {   
            $searchString = "'%" . str_replace(",", "','", $request['search']['value']) . "%'"; 
            $sql .= " and ( cuf.id LIKE " . $searchString;
            $sql .= " OR cuf.lrfile LIKE " . $searchString;
            $sql .= " OR cuf.created_at LIKE " . $searchString;
            $sql .= " OR u.mobile LIKE " . $searchString;
            $sql .= " OR u.business_name LIKE " . $searchString;
            $sql .= " OR u.fullname LIKE " . $searchString . ")";
        }

        $query = qry($sql);
        $totalFiltered = count($query);

        $sql .= " ORDER BY " . $columns[$request->order[0]['column']] . "   " . $request->order[0]['dir'] . " LIMIT " . $request->start . " ," . $request->length . "   ";  
        $query = qry($sql);

        $data = array();

        $cnts = $request->start + 1;

        foreach ($query as $row) {
            $lrfile = '';

            $nestedData = array();
            $nestedData[] = Carbon::parse($row->created_at)->format('d-m-Y')."<br>".Carbon::parse($row->created_at)->format('h:i:s A');
            $nestedData[] = $row->ufullname."<br>". $row->ubusiness_name."<br>". $row->umobile;

            if($row->lrfile!=''){
                $exIMG = explode('.', $row->lrfile);
            }
            
            if(isset($exIMG[1])){
                $path = sendPath().constants('dir_name.customer').'/'.$row->lrfile;

                if(in_array(strtolower($exIMG[1]), constants('image_extension'))){
                    $lrfile = '<a target="_blank" href='.$path.' ><span><img src='.$path.' class="profile-img" alt="img" width="55px" height="55px"></span></a>';
                }
                else
                {
                    $lrfile = '<a target="_blank" href='.$path.' >Download</a>'; 
                }
            }

            $nestedData[] = $lrfile;

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

    public function getdata(Request $request){
		
        $columns = array(          
            0 => 'o.bigdaddy_lr_number',
            1 => 'o.transporter_lr_number',
            2 => 'u.fullname',
            3 => 'o.pickup_location',
            4 => 'o.final_cost',
            5 => 'o.created_at',
            6 => 'o.driver_id',
			7 => 'o.created_at',
        );
		
		
        $sql = "select o.*,inv.invoice_number,inv.invoice_file,inv.created_at as invcreated_at, (select name from tbl_short_helper where short=o.payment_status and type='payment_status') as payment_status, (select classhtml from tbl_short_helper where short=o.payment_status and type='payment_status') as classhtml,d.fullname as driver_name,u.fullname as ufullname,u.business_name as ubusiness_name,u.mobile as umobile,sh.name as shname, sh.classhtml as shclasshtml from ".$this->tbl." o INNER JOIN tbl_users u ON u.id=o.user_id LEFT JOIN tbl_drivers d ON d.id=o.driver_id LEFT JOIN tbl_short_helper sh ON sh.short=o.status and sh.type='order_status_type' LEFT JOIN ".$this->tbl3." inv ON inv.id=o.invoice_id WHERE o.is_active!='2'  ";
		

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


            $payment_status = "<br><span class='shadow-none badge badge-".$row->classhtml."' >".$row->payment_status."</span>";
            $nestedData = array();
            $nestedData[] = $row->bigdaddy_lr_number;
            $nestedData[] = $row->transporter_lr_number;
            $nestedData[] = $row->ufullname."<br>". $row->ubusiness_name."<br>". $row->umobile.$invoiceBtn;
            $nestedData[] = "Pickup Address - ".$row->pickup_location."<br><br>"."Drop Address - ".$row->drop_location;
            $nestedData[] = $row->final_cost. " Rs";
            $nestedData[] = Carbon::parse($row->created_at)->format('d-m-Y')."<br>".Carbon::parse($row->created_at)->format('h:i:s A');
            $status = "<span class='shadow-none badge badge-".$row->shclasshtml."'>".$row->shname."</span>";

            $nestedData[] = $status.$payment_status;
            $nestedData[] = $row->driver_name;

            $logsBtn = "<a style='cursor: pointer;' data-toggle='modal' data-target='#logsModal' class='btn btn-info btn-sm button_margin_bottom_5' onclick='seeLogs(".$row->id.")'><i class='far fa-eye'></i></a>";

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

    public function getdata_tobeassigned(Request $request){
        $order_status = "'" . implode ( "', '", constants('order_status.new_orders') ) . "'";
        $order_status_sql =  " and o.status IN(".$order_status.") ";

        
        $columns = array(          
            0 => 'o.bigdaddy_lr_number',
            1 => 'o.transporter_lr_number',
            2 => 'u.fullname',
            3 => 'o.pickup_location',
            4 => 'o.final_cost',
            5 => 'o.created_at',
            6 => 'o.driver_id',
            7 => 'o.created_at',
        );


        $sql = "select o.*,inv.invoice_number,inv.invoice_file,inv.created_at as invcreated_at,d.fullname as driver_name,u.fullname as ufullname,u.business_name as ubusiness_name,u.mobile as umobile,sh.name as shname, sh.classhtml as shclasshtml, (select name from tbl_short_helper where short=o.payment_status and type='payment_status') as payment_status, (select classhtml from tbl_short_helper where short=o.payment_status and type='payment_status') as classhtml from ".$this->tbl." o INNER JOIN tbl_users u ON u.id=o.user_id LEFT JOIN tbl_drivers d ON d.id=o.driver_id LEFT JOIN tbl_short_helper sh ON sh.short=o.status and sh.type='order_status_type' LEFT JOIN ".$this->tbl3." inv ON inv.id=o.invoice_id WHERE o.is_active!='2' $order_status_sql ";

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

            $payment_status = "<br><span class='shadow-none badge badge-".$row->classhtml."' >".$row->payment_status."</span>";

            
            $nestedData[] = $row->bigdaddy_lr_number. "&nbsp;&nbsp;&nbsp; <input class='selectit' type='checkbox' id='chk' data-id='".$row->id."' name='selectlist[]' > ";
            $nestedData[] = $row->transporter_lr_number;

            $invoiceBtn = '';
            if($row->invoice_number!=""){
                $invoiceBtn = "<br><a target='_blank' href='".sendPath().constants('dir_name.invoice').'/'.$row->invoice_file."'><span title='view invoice #".$row->invoice_number."' data-filename=".$row->invoice_file." data-folder=".constants('dir_name.invoice')." class='shadow-none badge badge-secondary spancursorcls filedownloaditno'>".$row->invoice_number."</span></a>";
            }

            $nestedData[] = $row->ufullname."<br>". $row->ubusiness_name."<br>". $row->umobile.$invoiceBtn;
            $nestedData[] = "Pickup Address - ".$row->pickup_location."<br><br>"."Drop Address - ".$row->drop_location;
            
            $nestedData[] = $row->final_cost. " Rs";
            $nestedData[] = Carbon::parse($row->created_at)->format('d-m-Y')."<br>".Carbon::parse($row->created_at)->format('h:i:s A');

            $status = "<span class='shadow-none badge badge-".$row->shclasshtml."'>".$row->shname."</span>";

            $nestedData[] = $status.$payment_status;
            $nestedData[] = $row->driver_name;


            $assignBtn = "<a style='cursor: pointer;' data-toggle='modal' data-target='#assignModal' class='dropdown-item assignit' data-ovalue='".$row->id."' data-bigdaddylrnumberid='".$row->bigdaddy_lr_number."' data-orderdateid='".$row->created_at."' data-dvalue='".$row->driver_id."' >Assign</a>";

            $deliverBtn = "<a style='cursor: pointer;' class='dropdown-item deliverit' data-oid='".$row->id."' data-bigdaddylrnumberid='".$row->bigdaddy_lr_number."' data-orderdateid='".$row->created_at."' >Deliver</a>";

            $cancelBtn = "<a style='cursor: pointer;' class='dropdown-item cancelit' data-oid='".$row->id."' data-bigdaddylrnumberid='".$row->bigdaddy_lr_number."' data-orderdateid='".$row->created_at."' >Cancel <i class='far fa-window-close'></i></a>"; 
  

            $actionBtn = '<div class="btn-group"><button type="button" class="btn btn-primary btn-sm dropdown-toggle dropdown-toggle-split button_margin_bottom_5"  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-reference="parent" id="dropdownMenuReference5" >Action</button><div class="dropdown-menu" aria-labelledby="dropdownMenuReference5" style="will-change: transform;">'.$assignBtn.$deliverBtn.$cancelBtn.'</div></div>';


            $logsBtn = "<a style='cursor: pointer;' data-toggle='modal' data-target='#logsModal' class='btn btn-info btn-sm button_margin_bottom_5' onclick='seeLogs(".$row->id.")'><i class='far fa-eye'></i></a>";

            $viewBtn = "<a style='cursor: pointer;' href='".route('view-order').'/'.$row->id."'  class='btn btn-info btn-sm'>View</a>"; 
            

            $nestedData[] = $actionBtn. " " .$logsBtn . " " .$viewBtn;
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

    public function getdata_assigned(Request $request){
        $order_status = "'" . implode ( "', '", constants('order_status.active_transit_orders') ) . "'";
        $order_status_sql =  " and o.status IN(".$order_status.") ";


         $columns = array(          
            0 => 'o.bigdaddy_lr_number',
            1 => 'o.transporter_lr_number',
            2 => 'u.fullname',
            3 => 'o.pickup_location',
            4 => 'o.final_cost',
            5 => 'o.created_at',
            6 => 'o.driver_id',
            7 => 'o.created_at',
        );
        
        
        $sql = "select o.*,inv.invoice_number,inv.invoice_file,inv.created_at as invcreated_at,shps.name as shpspayment_status, shps.classhtml as shpsclasshtml,d.fullname as driver_name,u.fullname as ufullname,u.business_name as ubusiness_name,u.mobile as umobile,sh.name as shname, sh.classhtml as shclasshtml from ".$this->tbl." o INNER JOIN tbl_users u ON u.id=o.user_id LEFT JOIN tbl_drivers d ON d.id=o.driver_id LEFT JOIN tbl_short_helper sh ON sh.short=o.status and sh.type='order_status_type' LEFT JOIN tbl_short_helper shps ON shps.short=o.payment_status and shps.type='payment_status' LEFT JOIN ".$this->tbl3." inv ON inv.id=o.invoice_id WHERE o.is_active!='2'  $order_status_sql ";
        

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
            
            $nestedData[] = $row->bigdaddy_lr_number. "&nbsp;&nbsp;&nbsp; <input class='selectit' type='checkbox' id='chk' data-id='".$row->id."' name='selectlist[]' > ";
            $nestedData[] = $row->transporter_lr_number;

            $invoiceBtn = '';
            if($row->invoice_number!=""){
                $invoiceBtn = "<br><a target='_blank' href='".sendPath().constants('dir_name.invoice').'/'.$row->invoice_file."'><span title='view invoice #".$row->invoice_number."' data-filename=".$row->invoice_file." data-folder=".constants('dir_name.invoice')." class='shadow-none badge badge-secondary spancursorcls filedownloaditno'>".$row->invoice_number."</span></a>";
            }

            $nestedData[] = $row->ufullname."<br>". $row->ubusiness_name."<br>". $row->umobile.$invoiceBtn;
            $nestedData[] = "Pickup Address - ".$row->pickup_location."<br><br>"."Drop Address - ".$row->drop_location;
            
            $nestedData[] = $row->final_cost. " Rs";
            $nestedData[] = Carbon::parse($row->created_at)->format('d-m-Y')."<br>".Carbon::parse($row->created_at)->format('h:i:s A');
            $payment_status = "<br><span class='shadow-none badge badge-".$row->shpsclasshtml."' >".$row->shpspayment_status."</span>";
            $status = "<span class='shadow-none badge badge-".$row->shclasshtml."'>".$row->shname."</span>";

            $nestedData[] = $status.$payment_status;
            $nestedData[] = $row->driver_name;


            $assignBtn = "<a style='cursor: pointer;' data-toggle='modal' data-target='#assignModal' class='dropdown-item assignit' data-ovalue='".$row->id."' data-bigdaddylrnumberid='".$row->bigdaddy_lr_number."' data-orderdateid='".$row->created_at."' data-dvalue='".$row->driver_id."' >Assign</a>";

            $deliverBtn = "<a style='cursor: pointer;' class='dropdown-item deliverit' data-oid='".$row->id."' data-bigdaddylrnumberid='".$row->bigdaddy_lr_number."' data-orderdateid='".$row->created_at."' >Deliver</a>";

            $cancelBtn = "<a style='cursor: pointer;' class='dropdown-item cancelit' data-oid='".$row->id."' data-bigdaddylrnumberid='".$row->bigdaddy_lr_number."' data-orderdateid='".$row->created_at."' >Cancel <i class='far fa-window-close'></i></a>"; 
  

          $actionBtn = '<div class="btn-group"><button type="button" class="btn btn-primary btn-sm dropdown-toggle dropdown-toggle-split button_margin_bottom_5"  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-reference="parent" id="dropdownMenuReference5" >Action</button><div class="dropdown-menu" aria-labelledby="dropdownMenuReference5" style="will-change: transform;">'.$assignBtn.$deliverBtn.$cancelBtn.'</div></div>';


            $logsBtn = "<a style='cursor: pointer;' data-toggle='modal' data-target='#logsModal' class='btn btn-info btn-sm button_margin_bottom_5' onclick='seeLogs(".$row->id.")'><i class='far fa-eye'></i></a>";

            $viewBtn = "<a style='cursor: pointer;' href='".route('view-order').'/'.$row->id."'  class='btn btn-info btn-sm'>View</a>"; 
            

            $nestedData[] = $actionBtn. " " .$logsBtn . " " .$viewBtn;
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

    public function getdata_requested_orders(Request $request){
        $order_status = "'" . implode ( "', '", constants('order_status.requested_orders') ) . "'";
        $order_status_sql =  " and o.status IN(".$order_status.") ";

        $columns = array(          
            0 => 'o.id',
            1 => 'u.fullname',
            2 => 'o.pickup_location',
            3 => 'o.drop_location',
            4 => 'o.created_at',
            5 => 'o.created_at',
        );

        
        $sql = "select o.*,inv.invoice_number,inv.invoice_file,inv.created_at as invcreated_at,u.fullname as ufullname,u.business_name as ubusiness_name,u.mobile as umobile,sh.name as shname, sh.classhtml as shclasshtml from ".$this->tbl." o INNER JOIN tbl_users u ON u.id=o.user_id  LEFT JOIN tbl_short_helper sh ON sh.short=o.status and sh.type='order_status_type' LEFT JOIN ".$this->tbl3." inv ON inv.id=o.invoice_id WHERE o.is_active!='2' $order_status_sql ";
        

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
            $sql .= " OR o.pickup_latitude LIKE " . $searchString;
            $sql .= " OR o.pickup_longitude LIKE " . $searchString;
            $sql .= " OR o.drop_latitude LIKE " . $searchString;
            $sql .= " OR o.drop_longitude LIKE " . $searchString;

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

        $query = qry($sql);
        $totalFiltered = count($query);

        $sql .= " ORDER BY " . $columns[$request->order[0]['column']] . "   " . $request->order[0]['dir'] . " LIMIT " . $request->start . " ," . $request->length . "   ";  
        $query = qry($sql);

        $data = array();

        $cnts = $request->start + 1;

        foreach ($query as $row) {
            $nestedData = array();
            $status = "<span class='shadow-none badge badge-".$row->shclasshtml."'>".$row->shname."</span>";
            $nestedData[] = $status;

            $invoiceBtn = '';
            if($row->invoice_number!=""){
                $invoiceBtn = "<br><a target='_blank' href='".sendPath().constants('dir_name.invoice').'/'.$row->invoice_file."'><span title='view invoice #".$row->invoice_number."' data-filename=".$row->invoice_file." data-folder=".constants('dir_name.invoice')." class='shadow-none badge badge-secondary spancursorcls filedownloaditno'>".$row->invoice_number."</span></a>";
            }

            $nestedData[] = $row->ufullname."<br>". $row->ubusiness_name."<br>". $row->umobile.$invoiceBtn;
            $nestedData[] = $row->pickup_location;
            $nestedData[] = $row->drop_location;

            $nestedData[] = Carbon::parse($row->created_at)->format('d-m-Y')."<br>".Carbon::parse($row->created_at)->format('h:i:s A');

            $logsBtn = "<a style='cursor: pointer;' data-toggle='modal' data-target='#logsModal' class='btn btn-info btn-sm button_margin_bottom_5' onclick='seeLogs(".$row->id.")'><i class='far fa-eye'></i></a>";

            $viewBtn = "<a style='cursor: pointer;' href='".route('edit-order').'/'.$row->id."'  class='btn btn-warning btn-sm'>Edit</a>"; 
            

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

    public function getdata_approved_orders(Request $request){
        $order_status = "'" . implode ( "', '", constants('order_status.approved_orders') ) . "'";
        $order_status_sql =  " and o.status IN(".$order_status.") ";


        $columns = array(          
            0 => 'o.id',
            1 => 'u.fullname',
            2 => 'o.pickup_location',
            3 => 'o.drop_location',
            4 => 'o.final_cost',
            5 => 'o.created_at',
            6 => 'o.created_at',
        );
        
        
        $sql = "select o.*,inv.invoice_number,inv.invoice_file,inv.created_at as invcreated_at,d.fullname as driver_name,u.fullname as ufullname,u.business_name as ubusiness_name,u.mobile as umobile,sh.name as shname, sh.classhtml as shclasshtml from ".$this->tbl." o INNER JOIN tbl_users u ON u.id=o.user_id LEFT JOIN tbl_drivers d ON d.id=o.driver_id LEFT JOIN tbl_short_helper sh ON sh.short=o.status and sh.type='order_status_type' LEFT JOIN ".$this->tbl3." inv ON inv.id=o.invoice_id WHERE o.is_active!='2'  $order_status_sql ";
        

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

        $query = qry($sql);
        $totalFiltered = count($query);

        $sql .= " ORDER BY " . $columns[$request->order[0]['column']] . "   " . $request->order[0]['dir'] . " LIMIT " . $request->start . " ," . $request->length . "   ";  
        $query = qry($sql);

        $data = array();

        $cnts = $request->start + 1;

        foreach ($query as $row) {
            $nestedData = array();
            $status = "<span class='shadow-none badge badge-".$row->shclasshtml."'>".$row->shname."</span>";
            
            $nestedData[] = $status;

            $invoiceBtn = '';
            if($row->invoice_number!=""){
                $invoiceBtn = "<br><a target='_blank' href='".sendPath().constants('dir_name.invoice').'/'.$row->invoice_file."'><span title='view invoice #".$row->invoice_number."' data-filename=".$row->invoice_file." data-folder=".constants('dir_name.invoice')." class='shadow-none badge badge-secondary spancursorcls filedownloaditno'>".$row->invoice_number."</span></a>";
            }

            $nestedData[] = $row->ufullname."<br>". $row->ubusiness_name."<br>". $row->umobile.$invoiceBtn;
            $nestedData[] = $row->pickup_location;
            $nestedData[] = $row->drop_location;
            $nestedData[] = $row->final_cost. " Rs";

            $nestedData[] = Carbon::parse($row->created_at)->format('d-m-Y')."<br>".Carbon::parse($row->created_at)->format('h:i:s A');

            $logsBtn = "<a style='cursor: pointer;' data-toggle='modal' data-target='#logsModal' class='btn btn-info btn-sm button_margin_bottom_5' onclick='seeLogs(".$row->id.")'><i class='far fa-eye'></i></a>";

    
            $confirmBtn = "<a style='cursor: pointer;' data-toggle='modal' class='dropdown-item confirmit' data-id='".$row->id."'>Confirm Order</a>";

            $viewBtn = "<a style='cursor: pointer;' href='".route('view-order').'/'.$row->id."' class='dropdown-item viewit'>View Order</a>";
 

            $actionBtn = '<div class="btn-group"><button type="button" class="btn btn-primary btn-sm dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-reference="parent" id="dropdownMenuReference'.$row->id.'" >Action</button><div class="dropdown-menu" style="will-change: transform;" aria-labelledby="dropdownMenuReference'.$row->id.'">'.$confirmBtn.$viewBtn.'</div></div>';
            

            $nestedData[] = $logsBtn . " " .$actionBtn;
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

    public function getdata_undelivered(Request $request){
        $order_status = "'" . implode ( "', '", constants('order_status.undelivered_orders') ) . "'";
        $order_status_sql =  " and o.status IN(".$order_status.") ";

       $columns = array(          
            0 => 'o.bigdaddy_lr_number',
            1 => 'o.transporter_lr_number',
            2 => 'u.fullname',
            3 => 'o.pickup_location',
            4 => 'o.final_cost',
            5 => 'o.created_at',
            6 => 'o.driver_id',
            7 => 'o.created_at',
        );
        
        
        $sql = "select o.*,inv.invoice_number,inv.invoice_file,inv.created_at as invcreated_at,d.fullname as driver_name,u.fullname as ufullname,u.business_name as ubusiness_name,u.mobile as umobile,sh.name as shname, sh.classhtml as shclasshtml from ".$this->tbl." o INNER JOIN tbl_users u ON u.id=o.user_id LEFT JOIN tbl_drivers d ON d.id=o.driver_id LEFT JOIN tbl_short_helper sh ON sh.short=o.status and sh.type='order_status_type' LEFT JOIN ".$this->tbl3." inv ON inv.id=o.invoice_id WHERE o.is_active!='2'  $order_status_sql ";
        

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
            
            $nestedData[] = $row->bigdaddy_lr_number. "&nbsp;&nbsp;&nbsp; <input class='selectit' type='checkbox' id='chk' data-id='".$row->id."' name='selectlist[]' > ";
            $nestedData[] = $row->transporter_lr_number;

            $invoiceBtn = '';
            if($row->invoice_number!=""){
                $invoiceBtn = "<br><a target='_blank' href='".sendPath().constants('dir_name.invoice').'/'.$row->invoice_file."'><span title='view invoice #".$row->invoice_number."' data-filename=".$row->invoice_file." data-folder=".constants('dir_name.invoice')." class='shadow-none badge badge-secondary spancursorcls filedownloaditno'>".$row->invoice_number."</span></a>";
            }

            $nestedData[] = $row->ufullname."<br>". $row->ubusiness_name."<br>". $row->umobile.$invoiceBtn;
            $nestedData[] = "Pickup Address - ".$row->pickup_location."<br><br>"."Drop Address - ".$row->drop_location;
            
            $nestedData[] = $row->final_cost. " Rs";
            $nestedData[] = Carbon::parse($row->created_at)->format('d-m-Y')."<br>".Carbon::parse($row->created_at)->format('h:i:s A');

            $status = "<span class='shadow-none badge badge-".$row->shclasshtml."'>".$row->shname."</span>";

            $nestedData[] = $status;
            $nestedData[] = $row->driver_name;


            $assignBtn = "<a style='cursor: pointer;' data-toggle='modal' data-target='#assignModal' class='dropdown-item assignit' data-ovalue='".$row->id."' data-bigdaddylrnumberid='".$row->bigdaddy_lr_number."' data-orderdateid='".$row->created_at."' data-dvalue='".$row->driver_id."' >Assign</a>";

            $deliverBtn = "<a style='cursor: pointer;' class='dropdown-item deliverit' data-oid='".$row->id."' data-bigdaddylrnumberid='".$row->bigdaddy_lr_number."' data-orderdateid='".$row->created_at."' >Deliver</a>";

            $cancelBtn = "<a style='cursor: pointer;' class='dropdown-item cancelit' data-oid='".$row->id."' data-bigdaddylrnumberid='".$row->bigdaddy_lr_number."' data-orderdateid='".$row->created_at."' >Cancel <i class='far fa-window-close'></i></a>"; 
  

          $actionBtn = '<div class="btn-group"><button type="button" class="btn btn-primary btn-sm dropdown-toggle dropdown-toggle-split button_margin_bottom_5"  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-reference="parent" id="dropdownMenuReference5" >Action</button><div class="dropdown-menu" aria-labelledby="dropdownMenuReference5" style="will-change: transform;">'.$assignBtn.$deliverBtn.$cancelBtn.'</div></div>';


            $logsBtn = "<a style='cursor: pointer;' data-toggle='modal' data-target='#logsModal' class='btn btn-info btn-sm button_margin_bottom_5' onclick='seeLogs(".$row->id.")'><i class='far fa-eye'></i></a>";

            $viewBtn = "<a style='cursor: pointer;' href='".route('view-order').'/'.$row->id."'  class='btn btn-info btn-sm'>View</a>"; 
            

            $nestedData[] = $actionBtn. " " .$logsBtn . " " .$viewBtn;
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

    public function getdataOrderLogs(Request $request){
        $columns = array(          
            0 => 'ol.id',
            1 => 'ol.logs',
            2 => 'ol.created_at',
        );
        $sql = "select * from tbl_order_logs ol WHERE ol.order_id=".$request->oid." and ol.type='0' ";

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
            $nestedData[] = Carbon::parse($row->created_at)->format('d-F-Y h:i:s A');
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

    

    public function assign_order_to_driver(Request $request) {
        $validator = Validator::make($request->all(), [ 
            'driver_id' => 'required|numeric',
            'driver_assign_type' => 'required',
        ]);

        if($validator->fails()) {  
            $response = ['msg' => 'Missing Value.', 'success' => 0, 'data' => 0 ];
            return response()->json($response); 
        } 

		$sql = "select fullname,vendor_id from tbl_drivers where id=".$request->driver_id." limit 1 ";
        $query = qry($sql);

        $vendor_id = isset($query[0]->vendor_id) ? $query[0]->vendor_id : 0;
        $dataVendorCount = AccVendor::where('id', $vendor_id)->where('id', '!=', 0)->where('is_active', constants('is_active_yes'))->count();
        if($request->driver_assign_type!=constants('driver_assign_type.payroll') && $dataVendorCount==0){
            $response = ['msg' => 'Could Not Be Assigned Due to Driver has not been Connected to Any Vendor.', 'success' => 0, 'data' => 0 ];
            return response()->json($response);
        }

        $dataVehicle = Vehicle::where('driver_id',$request->driver_id)->first();
        if(empty($dataVehicle)){
            $response = ['msg' => 'Could Not Be Assigned Due to Driver has not been Assigned any Vehicle.', 'success' => 0, 'data' => 0 ];
            return response()->json($response);
        }
        else
        {
		$ovalueid = explode(',',$request->ovalueid);
		if(isset($ovalueid) &&  is_array($ovalueid) && count($ovalueid)>0){


            /*------driver--accountss---starts from here------*/
            if($request->driver_assign_type==constants('driver_assign_type.payperparcel')) {
                $forSingleParcelAmount = intval($request->driver_assign_type_amount);
            }
            else if($request->driver_assign_type==constants('driver_assign_type.paypertrip')) {
                $totalOrderParcelCountForThis = OrderParcel::whereIn('order_id', $ovalueid )->where('is_active', constants('is_active_yes'))->count();
                if($totalOrderParcelCountForThis>0){
                    $forSingleParcelAmount = intval($request->driver_assign_type_amount)/$totalOrderParcelCountForThis;
                }
                else
                {
                    $forSingleParcelAmount = 0;
                }
                
            }
            else {
                $forSingleParcelAmount = 0;
            }
            /*------driver--accountss---starts from here------*/



            $totalOrderCount = count($ovalueid);


			foreach($ovalueid as $rowid){

			$updateData = [
                'driver_assigned_datetime' => date('Y-m-d H:i:s'),
                'driver_id' => $request->driver_id,
                'status' => 'A',
                'vehicle_id' => $dataVehicle->id,
                'vehicle_no' => $dataVehicle->vehicle_no,
                'if_undelivered_reason_id' => 0,
                'if_undelivered_reason_text' => NULL,
                'undelivered_datetime' => NULL,
                'if_undelivered_reason_text' => NULL,
                'cancelled_datetime' => NULL,
        	];
            Order::where('id',$rowid)->update($updateData);

            $forThisOrderTripParcelCount = OrderParcel::where('order_id', $rowid )->where('is_active', constants('is_active_yes'))->count();

            $updateParcelAmountData = [
                'order_driver_trip_type' => $request->driver_assign_type,
                'order_driver_trip_amount' => $forSingleParcelAmount*$forThisOrderTripParcelCount,
            ];

            Order::where('id',$rowid)->update($updateParcelAmountData);

            $thisorderData = Order::where('id', $rowid)->first();

            /*if($request->driver_assign_type==constants('driver_assign_type.payperparcel')) {
                $dataAccountManage = [
                    'transaction_uuid' => random_text(10).uniqid().mt_rand(1000,9999),
                    'transaction_date' => date('Y-m-d'),
                    'amount' => $updateParcelAmountData['order_driver_trip_amount'],
                    'transaction_type' =>  constants('transaction_type.Debit'),
                    'description' => $request->driver_assign_type.' #'.$thisorderData->bigdaddy_lr_number.' ('.intval($updateParcelAmountData['order_driver_trip_amount']).'Rs.)',
                    'anybillno' => isset($request->anybillno) ? $request->anybillno : NULL,
                    'admin_id' => Session::get("adminid"),
                    'is_active' => constants('is_active_yes'),
                    'payment_method' => constants('payment_method_for_accounting.Cash.short'),
                    'vendor_id' => $vendor_id,
                    'is_reviewed' => 0,
                    'notes' => "PayPerParcel Order",
                    'accountid_from' => constants('cashonhands_bankid'),
                    'accountid_transferredto' => NULL,
                    'is_editable'=> constants('is_editable_yes'),
                    'transaction_subcategory_id' => constants('driver_transaction_sub_category_id_payperparcel'),
                    'order_id' => $rowid,
                ];

                Transaction::updateOrCreate(
                ['order_id' => $rowid, 'is_active' => constants('is_active_yes') ],
                $dataAccountManage
                );
            }
            else if($request->driver_assign_type==constants('driver_assign_type.paypertrip')) {

                $dataAccountManage = [
                    'transaction_uuid' => random_text(10).uniqid().mt_rand(1000,9999),
                    'transaction_date' => date('Y-m-d'),
                    'amount' => $updateParcelAmountData['order_driver_trip_amount'],
                    'transaction_type' =>  constants('transaction_type.Debit'),
                    'description' => $request->driver_assign_type.' #'.$thisorderData->bigdaddy_lr_number.' ('.intval($updateParcelAmountData['order_driver_trip_amount']).'Rs.)',
                    'anybillno' => isset($request->anybillno) ? $request->anybillno : NULL,
                    'admin_id' => Session::get("adminid"),
                    'is_active' => constants('is_active_yes'),
                    'payment_method' => constants('payment_method_for_accounting.Cash.short'),
                    'vendor_id' => $vendor_id,
                    'is_reviewed' => 0,
                    'notes' => "PayPerParcel Order",
                    'accountid_from' => constants('cashonhands_bankid'),
                    'accountid_transferredto' => NULL,
                    'is_editable'=> constants('is_editable_yes'),
                    'transaction_subcategory_id' => constants('driver_transaction_sub_category_id_payperparcel'),
                    'order_id' => $rowid,
                ];

                Transaction::updateOrCreate(
                ['order_id' => $rowid, 'is_active' => constants('is_active_yes')],
                $dataAccountManage
                );                
            }
            else {
                Transaction::where('order_id' , $rowid)->where('is_active' , constants('is_active_yes'))->delete();
            }*/


            OrderArrange::where('order_id', $rowid)->where('is_active' , constants('is_active_yes'))->where('is_completed' , constants('confirmation.no'))->whereIn('arrangement_type' , constants('arrangement_type'))->delete();


            $lastArrangement = OrderArrange::where('driver_id', $request->driver_id)->where('is_active' , constants('is_active_yes'))->whereIn('arrangement_type' , constants('arrangement_type'))->where('arrangement_number', '>', 0)->orderBy('arrangement_number','DESC')->first(['arrangement_number']);

            $insertOrderArrangeDataPickup = [
                    'order_id' => $rowid,
                    'arrangement_number' => isset($lastArrangement->arrangement_number) ? $lastArrangement->arrangement_number+1 : 1,
                    'driver_id' => $request->driver_id,
                    'is_active' => constants('is_active_yes'),
                    'arrangement_type' => constants('arrangement_type.pickup'),
                    'orderaction_datetime' => NULL,
                    'is_completed' => constants('confirmation.no'),
                    'driveraction_datetime' => NULL,
                    'difference_seconds' => 0,
                    'between_meters' => 0,
                    'between_seconds' => 0,
                    'is_early_fulfilled' => 1,
                    'origins_latitude' => NULL,
                    'origins_longitude' => NULL,
                    'destinations_latitude' => $thisorderData->pickup_latitude,
                    'destinations_longitude' => $thisorderData->pickup_longitude,
            ];
            $lastOrderArrangeDataPickup = OrderArrange::create($insertOrderArrangeDataPickup);

            $insertOrderArrangeDataDeliver = [
                    'order_id' => $rowid,
                    'arrangement_number' => isset($lastArrangement->arrangement_number) ? $lastArrangement->arrangement_number+2 : 2,
                    'driver_id' => $request->driver_id,
                    'is_active' => constants('is_active_yes'),
                    'arrangement_type' => constants('arrangement_type.deliver'),
                    'orderaction_datetime' => NULL,
                    'is_completed' => constants('confirmation.no'),
                    'driveraction_datetime' => NULL,
                    'difference_seconds' => 0,
                    'between_meters' => 0,
                    'between_seconds' => 0,
                    'is_early_fulfilled' => 1,
                    'origins_latitude' => NULL,
                    'origins_longitude' => NULL,
                    'destinations_latitude' => $thisorderData->drop_latitude,
                    'destinations_longitude' => $thisorderData->drop_longitude,
            ];
            $lastOrderArrangeDataDeliver = OrderArrange::create($insertOrderArrangeDataDeliver);


                       
            createDriverNotificationLogs("New Order","Order #".$thisorderData->bigdaddy_lr_number." has been Assigned to You By ".Session::get('fullname'),$request->driver_id,$thisorderData->id);

            pushNotificationToDriverApp("New Order", "Order #".$thisorderData->bigdaddy_lr_number." has been Assigned to You By ".Session::get('fullname') ,$request->driver_id ,['order_id' => $thisorderData->id ]);

        	createOrderLogs("Order Assigned to ".@$query[0]->fullname." By ".Session::get('fullname'), $rowid );
            createOrderLogs("Order Assigned to Driver.", $rowid, 1);

            createCustomerNotificationLogs("Your Order #".$thisorderData->bigdaddy_lr_number." has been Assigned To Driver.",$thisorderData->user_id,$thisorderData->id,constants('notification_type.success'));

            pushNotificationToUser("Order ".constants('user_notification_type.on_assign_order.name'), "Dear ".$thisorderData->fullname.", Your Order #".$thisorderData->bigdaddy_lr_number." has been Assigned To Driver.",'','','',$thisorderData->user_id);

            pushNotificationToUserApp("Order ".constants('user_notification_type.on_assign_order.name'), "Dear ".$thisorderData->fullname.", Your Order #".$thisorderData->bigdaddy_lr_number." has been Assigned To Driver." ,$thisorderData->user_id , ['order_id' => $thisorderData->id, 'user_notification_type' => constants('user_notification_type.on_assign_order.name') ]);

            

            //sendMsg("Dear Customer, Your Order #".$thisorderData->bigdaddy_lr_number." has been Assigned To Driver.", $thisorderData->contact_person_phone_number_drop);
			}

		  }
          $response = ['msg' => 'Assigned Successfully!', 'success' => 1 , 'data' => $request->driver_id ];
          return response()->json($response);
        }
    }

    public function search_driver_and_select() {
        extract($_REQUEST);
        if (!isset($_POST['searchTerm'])) {
            $sql = "select d.id, CONCAT(d.fullname,' | ' , (select name from tbl_short_helper where short=d.status and type='driver_status' limit 1), ' | ', v.vehicle_no)
         as text from tbl_drivers d INNER JOIN tbl_vehicles v ON v.driver_id=d.id where d.is_active!='2' OR d.id=".$driver_id." ORDER BY d.id DESC limit 10";
        } else {
            $sql = "select d.id, CONCAT(d.fullname,' | ' , (select name from tbl_short_helper where short=d.status and type='driver_status' limit 1), ' | ', v.vehicle_no) as text from tbl_drivers d 
         INNER JOIN tbl_vehicles v ON v.driver_id=d.id
         where d.is_active!='2' ";
            $search = $_POST['searchTerm'];

            $sql .= " AND ( d.fullname like '%" . $search . "%'  ";
            $sql .= " OR v.vehicle_no like '%" . $search . "%'  ";
            $sql .= " OR d.pan_card like '%" . $search . "%'  ";
            $sql .= " OR d.email like '%" . $search . "%'  ";
            $sql .= " OR d.mobile like '%" . $search . "%' OR d.id=".$driver_id." ) ORDER BY d.id DESC LIMIT 10 ";
        }

        $query = qry($sql);

        echo json_encode($query);
    }

    public function search_transporter_and_select() {
        if (!isset($_POST['searchTerm'])) {
            $sql = "select id,  CONCAT_WS(' | ', fullname, business_name, GST_number, email ) as text from tbl_users where is_active='0' order by rand() limit 40";
        } else {
            $sql = "select id, CONCAT_WS(' | ', fullname, business_name, GST_number, email ) as text from tbl_users where is_active='0' ";
            $search = $_POST['searchTerm'];

            $sql .= " AND ( GST_number like '%" . $search . "%'  ";
            $sql .= " OR business_name like '%" . $search . "%'  ";
            $sql .= " OR fullname like '%" . $search . "%'  ";
            $sql .= " OR email like '%" . $search . "%'  ";
            $sql .= " OR mobile like '%" . $search . "%' ) LIMIT 40 ";
        }

        $query = qry($sql);
        echo json_encode($query);
    }

    public function search_transporter_and_select13012021() {
        if (!isset($_POST['searchTerm'])) {
            $sql = "select id, CONCAT(fullname , ' | ', email , ' | ' , business_name ,' | ' , GST_number)
         as text from tbl_users where is_active='0' order by rand() limit 40";
        } else {
            $sql = "select id, CONCAT(fullname , ' | ', email , ' | ' , business_name ,' | ' , GST_number)
         as text from tbl_users where is_active='0' ";
            $search = $_POST['searchTerm'];

            $sql .= " AND ( GST_number like '%" . $search . "%'  ";
            $sql .= " OR business_name like '%" . $search . "%'  ";
            $sql .= " OR fullname like '%" . $search . "%'  ";
            $sql .= " OR email like '%" . $search . "%'  ";
            $sql .= " OR mobile like '%" . $search . "%' ) LIMIT 40 ";
        }

        $query = qry($sql);
        echo json_encode($query);
    }

    public function fill_existing_data_from_transporter_details() {
        extract($_REQUEST);
        if (!isset($id)) {
            Session::flash('msg', 'Please Check All Required Fields Properly.');
            Session::flash('cls', 'danger');
            return redirect()->back();
        }

        $id = intval($id);

        $sql = "Select * from tbl_users WHERE is_active!='2' AND id=".$id." limit 1 ";
        $R = qry($sql);

        $sql = "Select * from tbl_address WHERE is_active!='2' AND user_id=".$id." ORDER BY is_default DESC limit 1 ";
        $address = qry($sql);

        $lastOrderData = Order::with([
            'orderParcel' => function($qryOrderParcel) {
                $qryOrderParcel->with([
                    'goodsType' => function($qryGoodsType) {
                        $qryGoodsType->where('is_active','!=', 2);
                    },
                ]);
                $qryOrderParcel->where('is_active', constants('is_active_yes'));
            },
        ])
        ->where('user_id',$id)
        ->where('is_active',constants('is_active_yes'))
        ->orderBy('id', 'desc')
        ->first();


        $json = array('one' => $R, 'address' => $address, 'lastOrderData' => $lastOrderData );

        echo json_encode($json);
    }
  
    public function UpdateOrder(Request $request) {

        $validator = Validator::make($request->all(), [ 
            'hidorderid' => 'required|integer|min:1',
            'created_at' => 'required',
            'user_id' => 'required|integer|min:1',
            'pickup_address'=>'required',
            'drop_address'=>'required',
            'pickup_latitude'=>'required',
            'pickup_longitude'=>'required',
            'drop_latitude'=>'required',
            'drop_longitude'=>'required',
            'contact_person_name'=>'required',
            'contact_person_phone_number'=>'required',
            'contact_person_name_drop'=>'required',
            'contact_person_phone_number_drop'=>'required',
            'goods_type_id'=> 'required',
            'other_text'=> 'required',
            'estimation_value'=> 'required',
            'tempo_charge'=> 'required',
            'service_charge'=> 'required',
            'no_of_parcel'=> 'required',
            'goods_weight'=> 'required',
            'total_weight'=> 'required',
            'final_cost'=> 'required|numeric|between:10,999999999999999.99',
            'customer_estimation_asset_value'=> 'nullable|between:0,99999999999999999.99',
        ]);   

        if($validator->fails()) {  
            Session::flash('msg', 'Please Check All Required Fields Properly.');
            Session::flash('cls', 'danger');
            return redirect()->back()->withErrors($validator)->withInput();      
        }  

        try {
			

        if(Session::get("adminrole")==constants('admins_type.SuperAdmin')){
            $thisorderData = Order::where('id',$request->hidorderid)->where('created_at', $request->created_at)->where('user_id',$request->user_id)->where('is_active', constants('is_active_yes'))->first();
        }
        else
        {
            $thisorderData = Order::where('id',$request->hidorderid)->where('created_at', $request->created_at)->where('user_id',$request->user_id)->whereIn('status',constants('orderEditableStatus'))->where('is_active', constants('is_active_yes'))->first();
        }


        if(!empty($thisorderData) && is_array($request->goods_type_id) && !empty($request->goods_type_id) && is_array($request->other_text) && !empty($request->other_text) && is_array($request->estimation_value) && !empty($request->estimation_value) && is_array($request->tempo_charge) && !empty($request->tempo_charge) && is_array($request->service_charge) && !empty($request->service_charge) && is_array($request->no_of_parcel) && !empty($request->no_of_parcel))
         {

            $varBigdaddyLRnumber = isset($request->bigdaddy_lr_number) ? intval($request->bigdaddy_lr_number) : NULL;
            $countBigdaddyLRnumber = Order::where('bigdaddy_lr_number','=',$request->bigdaddy_lr_number)->where('id','!=',$request->hidorderid)->whereNotNull('bigdaddy_lr_number')->count();

            if($countBigdaddyLRnumber>0){
                Session::flash('msg', 'Bigdaddy LR Number Already Exists.');
                Session::flash('cls', 'danger');
                return redirect()->back();
            }


        $updateData = [
            'bigdaddy_lr_number' => $varBigdaddyLRnumber,
            'pickup_location' => $request->pickup_address,
            'drop_location' => $request->drop_address,
            'pickup_location' => $request->pickup_address,
            'pickup_latitude' => $request->pickup_latitude,
            'pickup_longitude' => $request->pickup_longitude,
            'drop_latitude' => $request->drop_latitude,
            'drop_longitude' => $request->drop_longitude,
            'contact_person_name' => $request->contact_person_name,
            'contact_person_phone_number' => $request->contact_person_phone_number,
            'transporter_name' => isset($request->transporter_name) ? $request->transporter_name : '',
            'contact_person_name_drop' => $request->contact_person_name_drop,
            'contact_person_phone_number_drop' => $request->contact_person_phone_number_drop,
            'transporter_name_drop' => isset($request->transporter_name_drop) ? $request->transporter_name_drop : '',
            'customer_estimation_asset_value' => isset($request->customer_estimation_asset_value) ? $request->customer_estimation_asset_value : 0,
            'other_field_pickup' => isset($request->other_field_pickup) ? $request->other_field_pickup : NULL,
            'other_field_drop' => isset($request->other_field_drop) ? $request->other_field_drop : NULL,
            'transport_cost' => isset($request->transport_cost) ? floatval($request->transport_cost) : 0,
            'min_order_value_charge' => isset($request->min_order_value_charge) ? floatval($request->min_order_value_charge) : 0,
            'discount' => isset($request->discount) ? floatval($request->discount) : 0,
            'redeliver_charge' => isset($request->redeliver_charge) ? floatval($request->redeliver_charge) : 0,
            'transporter_lr_number' => isset($request->transporter_lr_number) ? $request->transporter_lr_number : NULL,
        ];



        $upload_fileLRP = []; $upload_fileLRP_count = 0;
        if(is_array($request->file('fileLRP')) && !empty($request->file('fileLRP'))){
            foreach($request->file('fileLRP') as $fx) {
                $upload_fileLRP_name = UploadImage($fx, constants('dir_name.order'),constants('order_file_type.lrpickup'));
                $upload_fileLRP[] = $upload_fileLRP_name;
                $upload_fileLRP_count++;
            }
        }

        $upload_fileLRD = []; $upload_fileLRD_count = 0;
        if(is_array($request->file('fileLRD')) && !empty($request->file('fileLRD'))){
            foreach($request->file('fileLRD') as $fx) {
                $upload_fileLRD_name = UploadImage($fx, constants('dir_name.order'),constants('order_file_type.lrdrop'));
                $upload_fileLRD[] = $upload_fileLRD_name;
                $upload_fileLRD_count++;
            }
        }

        $upload_fileGP = []; $upload_fileGP_count = 0;
        if(is_array($request->file('fileGP')) && !empty($request->file('fileGP'))){
            foreach($request->file('fileGP') as $fx) {
                $upload_fileGP_name = UploadImage($fx, constants('dir_name.order'),constants('order_file_type.goodspickup'));
                $upload_fileGP[] = $upload_fileGP_name;
                $upload_fileGP_count++;
            }
        }

        $upload_fileGD = []; $upload_fileGD_count = 0;
        if(is_array($request->file('fileGD')) && !empty($request->file('fileGD'))){
            foreach($request->file('fileGD') as $fx) {
                $upload_fileGD_name = UploadImage($fx, constants('dir_name.order'),constants('order_file_type.goodsdrop'));
                $upload_fileGD[] = $upload_fileGD_name;
                $upload_fileGD_count++;
            }
        }
		
		
		if($thisorderData->status=='RO' && $request->ApproveThisOrderCheck=='OA'){
              $updateData['status'] = $request->ApproveThisOrderCheck;
        }

        update_data($this->tbl, $updateData ,[ 'id' => $request->hidorderid ]);
        update_data($this->tbl2 ,['is_active' => 2 ],['order_id' => $request->hidorderid ]);

        if($upload_fileLRP_count>0){
            foreach($upload_fileLRP as $filename_dx) {
                $dt = [ 'order_id' => $request->hidorderid, 'img' => $filename_dx, 'img_type' => constants('order_file_type.lrpickup'),];
                OrderFile::create($dt);
            }
        }

        if($upload_fileLRD_count>0){
            foreach($upload_fileLRD as $filename_dx) {
                $dt = [ 'order_id' => $request->hidorderid, 'img' => $filename_dx, 'img_type' => constants('order_file_type.lrdrop'),];
                OrderFile::create($dt);
            }
        }

        if($upload_fileGP_count>0){
            foreach($upload_fileGP as $filename_dx) {
                $dt = [ 'order_id' => $request->hidorderid, 'img' => $filename_dx, 'img_type' => constants('order_file_type.goodspickup'),];
                OrderFile::create($dt);
            }
        }

        if($upload_fileGD_count>0){
            foreach($upload_fileGD as $filename_dx) {
                $dt = [ 'order_id' => $request->hidorderid, 'img' => $filename_dx, 'img_type' => constants('order_file_type.goodsdrop'),];
                OrderFile::create($dt);
            }
        }


            $total_weightO = 0; $total_no_of_parcelO = 0; $tempo_chargeO = 0; $service_chargeO = 0; $delivery_chargeO = 0; $customer_estimation_asset_valueO = 0; $i =0;
            foreach ($request->goods_type_id as $key => $value) 
            {

                $delivery_charge_i =  intval($request->no_of_parcel[$i])*floatval($request->tempo_charge[$i]) + intval($request->no_of_parcel[$i])*floatval($request->service_charge[$i]);
                $total_weightO += floatval($request->total_weight[$i]);
                $total_no_of_parcelO += intval($request->no_of_parcel[$i]);
                $tempo_chargeO += intval($request->no_of_parcel[$i])*floatval($request->tempo_charge[$i]);
                $service_chargeO += intval($request->no_of_parcel[$i])*floatval($request->service_charge[$i]);
                $customer_estimation_asset_valueO += floatval($request->estimation_value[$i]);
                $delivery_chargeO += $delivery_charge_i;

                $parceldata = [
                    'order_id' => $request->hidorderid,
                    'no_of_parcel' => intval($request->no_of_parcel[$i]),
                    'goods_type_id' => intval($request->goods_type_id[$i]),
                    'goods_weight' => floatval($request->goods_weight[$i]),
                    'total_weight' => floatval($request->total_weight[$i]),
                    'tempo_charge' => floatval($request->tempo_charge[$i]),
                    'service_charge' => floatval($request->service_charge[$i]),
                    'estimation_value' => floatval($request->estimation_value[$i]),
                    'delivery_charge' => $delivery_charge_i,
                    'is_active' => 0,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ];

                if($request->goods_type_id[$i]==constants('goods_type_id_other')){
                   $parceldata['other_text'] = $request->other_text[$i];
                }
                else{
                    $parceldata['other_text'] = NULL;
                }
                    
                $parcelid = insert_data_id($this->tbl2,$parceldata);
                $i++;
            }


            $total_payable_amount = $updateData['redeliver_charge'] + $updateData['min_order_value_charge'] - $updateData['discount'] + $delivery_chargeO;       

            $ORDERDATA = [
                'total_no_of_parcel' => $total_no_of_parcelO,
                'total_weight' => $total_weightO,
                'tempo_charge' => $tempo_chargeO,
                'service_charge' => $service_chargeO,
                'final_cost' => $delivery_chargeO,
                'customer_estimation_asset_value' => $customer_estimation_asset_valueO,
                'total_payable_amount' => $total_payable_amount,
                'updated_at' => date('Y-m-d H:i:s'),
            ];


            $isUpdatedThisOrder = update_data($this->tbl, $ORDERDATA,  ['id' =>  $request->hidorderid ]);
			
			$getThisOrderData = Order::where('id', $request->hidorderid)->where('user_id', $request->user_id)->where('is_active', constants('is_active_yes'))->first();

            if($isUpdatedThisOrder==1 && $thisorderData->status=='RO' && $request->ApproveThisOrderCheck=='OA'){
				/*----------RazorPay Creating Order- starts----------*/
				$totalCost = $getThisOrderData->final_cost + $getThisOrderData->min_order_value_charge + $getThisOrderData->redeliver_charge - $getThisOrderData->discount;

                $is_sendsms = isset($request->is_sendsms) ? $request->is_sendsms : 0;
				
				$orderDataRazorPay = [
				'receipt' => $getThisOrderData->id,
				'amount' => floatval($totalCost),
				'notes' => array(
							"order_id" => $getThisOrderData->id,
							"user_id" => $getThisOrderData->user_id, 
							"transporter_name" => $getThisOrderData->transporter_name, 
							"bigdaddy_lr_number" => $getThisOrderData->bigdaddy_lr_number, 
							"contact_person_phone_number" => $getThisOrderData->contact_person_phone_number, 
							 ),
				];
				/*$createNewOrderRazorPayData = (new RazorPayController())->createNewOrderRazorPay($orderDataRazorPay);
				if(isset($createNewOrderRazorPayData['success']) && $createNewOrderRazorPayData['success']==0){
					Session::flash('msg', $createNewOrderRazorPayData['msg']);
					Session::flash('cls', 'danger');
					return redirect()->back();
				}
				/*----------RazorPay Creating Order- Ends----------*/


                $paymentPageUrl = env('CLIENTBASE_URL')."#/payment?order=".$getThisOrderData->id;
				
                pushNotificationToUser("Order ".constants('user_notification_type.on_approve_order.name'), "Dear Customer, Your Order #".$thisorderData->bigdaddy_lr_number." has been Approved. Please Checkout to Confirm This Order.",'','',$paymentPageUrl,$thisorderData->user_id);
                createOrderLogs("Order Approved.", $request->hidorderid );
                createOrderLogs("Order Approved.", $request->hidorderid, 1);
                createCustomerNotificationLogs("Your Order has been Approved. Please Checkout to Confirm Your Order.",$thisorderData->user_id,$thisorderData->id,constants('notification_type.success'));

                pushNotificationToUserApp("Order ".constants('user_notification_type.on_approve_order.name'), "Dear Customer, Your Order #".$thisorderData->bigdaddy_lr_number." has been Approved. Please Checkout to Confirm This Order.", $thisorderData->user_id , ['order_id' => $thisorderData->id, 'user_notification_type' => constants('user_notification_type.on_approve_order.name') ]);

                if($is_sendsms==1){
                    sendMsg("Dear Customer, Please Click Here ".$paymentPageUrl." to Pay For Your Order.", $getThisOrderData->contact_person_phone_number);
                }
            }
            else
            {
               createOrderLogs("Order Details Edited By ".Session::get('fullname'), $request->hidorderid ); 
            }
            Session::flash('msg', ' Order Updated Successfully!');
            Session::flash('cls', 'success');
        }
        else
        {
            Session::flash('msg', 'This Order Can Not Be Updated.');
            Session::flash('cls', 'danger');
        }
        return redirect()->back();
        
        } catch (\Exception $e) {
            Log::error($e);
            Session::flash('msg', 'Order Not Updated.');
            Session::flash('cls', 'danger');
            return redirect()->back()->withError($e->getMessage());
        }
    }

	public function CreateNewOrder(Request $request) {

        $validator = Validator::make($request->all(), [ 
            'user_id' => 'required|numeric',
            'pickup_address' =>'required',
            'drop_address' =>'required',
            'pickup_latitude' =>'required',
            'pickup_longitude' =>'required',
            'drop_latitude' =>'required',
            'drop_longitude' =>'required',
            'contact_person_name' =>'required',
            'contact_person_phone_number' =>'required',
            'contact_person_name_drop' =>'required',
            'contact_person_phone_number_drop' =>'required',
            'goods_type_id' => 'required',
            'other_text' => 'required',
            'estimation_value' => 'required',
            'tempo_charge' => 'required',
            'service_charge' => 'required',
            'no_of_parcel' => 'required',
            'goods_weight' => 'required',
            'total_weight' => 'required',
            'final_cost' => 'required|between:0,999999999999999.99',
            'customer_estimation_asset_value' => 'nullable|between:0,99999999999999999.99',
            'bigdaddy_lr_number' => 'nullable|numeric',
        ]);   

        if($validator->fails()) {          
            Session::flash('msg', 'Please Check All Required Fields Properly.');
            Session::flash('cls', 'danger');
            return redirect()->back()->withErrors($validator)->withInput();  
        }  

        try {

            $varBigdaddyLRnumber = isset($request->bigdaddy_lr_number) ? intval($request->bigdaddy_lr_number) : NULL;
            $is_sendsms = isset($request->is_sendsms) ? $request->is_sendsms : 0;
            $countBigdaddyLRnumber = Order::where('bigdaddy_lr_number','=',$request->bigdaddy_lr_number)->whereNotNull('bigdaddy_lr_number')->count();

            if($countBigdaddyLRnumber>0){
                Session::flash('msg', 'Bigdaddy LR Number Already Exists.');
                Session::flash('cls', 'danger');
                return redirect()->back();
            }
            if(!isset($request->bigdaddy_lr_number) || $request->bigdaddy_lr_number=='' || $is_sendsms==0)
            {
                $varBigdaddyLRnumber = createBigDaddyLrNumber();  
            }



        $insertData = [
            'bigdaddy_lr_number' => $varBigdaddyLRnumber,
            'user_id' => $request->user_id,
            'pickup_location' => $request->pickup_address,
            'drop_location' => $request->drop_address,
            'pickup_latitude' => $request->pickup_latitude,
            'pickup_longitude' => $request->pickup_longitude,
            'drop_latitude' => $request->drop_latitude,
            'drop_longitude' => $request->drop_longitude,
            'contact_person_name' => $request->contact_person_name,
            'contact_person_phone_number' => $request->contact_person_phone_number,
            'transporter_name' => isset($request->transporter_name) ? $request->transporter_name : '',
            'contact_person_name_drop' => $request->contact_person_name_drop,
            'contact_person_phone_number_drop' => $request->contact_person_phone_number_drop,
            'transporter_name_drop' => isset($request->transporter_name_drop) ? $request->transporter_name_drop : '',
			'customer_estimation_asset_value' => isset($request->customer_estimation_asset_value) ? $request->customer_estimation_asset_value : 0,
            'other_field_pickup' => isset($request->other_field_pickup) ? $request->other_field_pickup : NULL,
            'other_field_drop' => isset($request->other_field_drop) ? $request->other_field_drop : NULL,
            'transport_cost' => isset($request->transport_cost) ? floatval($request->transport_cost) : 0,
            'min_order_value_charge' => isset($request->min_order_value_charge) ? floatval($request->min_order_value_charge) : 0,
            'discount' => isset($request->discount) ? floatval($request->discount) : 0,
            'redeliver_charge' => isset($request->redeliver_charge) ? floatval($request->redeliver_charge) : 0,
            'is_active'=> $request->is_active == 0 ? 0 : 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'order_created_by' => Session::get('adminid'),
            'lr_img'=> NULL,
            'status' => 'P',
            'payment_type_manual' => constants('payment_type_manual.CS.short'),
            'transporter_lr_number' => isset($request->transporter_lr_number) ? $request->transporter_lr_number : NULL,
        ];



        $upload_fileLRP = []; $upload_fileLRP_count = 0;
        if(is_array($request->file('fileLRP')) && !empty($request->file('fileLRP'))){
            foreach($request->file('fileLRP') as $fx) {
                $upload_fileLRP_name = UploadImage($fx, constants('dir_name.order'),constants('order_file_type.lrpickup'));
                $upload_fileLRP[] = $upload_fileLRP_name;
                $upload_fileLRP_count++;
            }
        }

        $upload_fileLRD = []; $upload_fileLRD_count = 0;
        if(is_array($request->file('fileLRD')) && !empty($request->file('fileLRD'))){
            foreach($request->file('fileLRD') as $fx) {
                $upload_fileLRD_name = UploadImage($fx, constants('dir_name.order'),constants('order_file_type.lrdrop'));
                $upload_fileLRD[] = $upload_fileLRD_name;
                $upload_fileLRD_count++;
            }
        }

        $upload_fileGP = []; $upload_fileGP_count = 0;
        if(is_array($request->file('fileGP')) && !empty($request->file('fileGP'))){
            foreach($request->file('fileGP') as $fx) {
                $upload_fileGP_name = UploadImage($fx, constants('dir_name.order'),constants('order_file_type.goodspickup'));
                $upload_fileGP[] = $upload_fileGP_name;
                $upload_fileGP_count++;
            }
        }

        $upload_fileGD = []; $upload_fileGD_count = 0;
        if(is_array($request->file('fileGD')) && !empty($request->file('fileGD'))){
            foreach($request->file('fileGD') as $fx) {
                $upload_fileGD_name = UploadImage($fx, constants('dir_name.order'),constants('order_file_type.goodsdrop'));
                $upload_fileGD[] = $upload_fileGD_name;
                $upload_fileGD_count++;
            }
        }

        $id = insert_data_id($this->tbl,$insertData);




        if($id>0)
         {
            if($upload_fileLRP_count>0){
                foreach($upload_fileLRP as $filename_dx) {
                    $dt = [ 'order_id' => $id, 'img' => $filename_dx, 'img_type' => constants('order_file_type.lrpickup'),];
                    OrderFile::create($dt);
                }
            }

            if($upload_fileLRD_count>0){
                foreach($upload_fileLRD as $filename_dx) {
                    $dt = [ 'order_id' => $id, 'img' => $filename_dx, 'img_type' => constants('order_file_type.lrdrop'),];
                    OrderFile::create($dt);
                }
            }

            if($upload_fileGP_count>0){
                foreach($upload_fileGP as $filename_dx) {
                    $dt = [ 'order_id' => $id, 'img' => $filename_dx, 'img_type' => constants('order_file_type.goodspickup'),];
                    OrderFile::create($dt);
                }
            }

            if($upload_fileGD_count>0){
                foreach($upload_fileGD as $filename_dx) {
                    $dt = [ 'order_id' => $id, 'img' => $filename_dx, 'img_type' => constants('order_file_type.goodsdrop'),];
                    OrderFile::create($dt);
                }
            }

            
            $total_weightO = 0;
            $total_no_of_parcelO = 0;
            $tempo_chargeO = 0;
            $service_chargeO = 0;
            $delivery_chargeO = 0;
            $customer_estimation_asset_valueO = 0;


             $i =0;
            if(!empty($request->goods_type_id)){
                foreach ($request->goods_type_id as $key => $value) {

            $delivery_charge_i =  intval($request->no_of_parcel[$i])*floatval($request->tempo_charge[$i]) + intval($request->no_of_parcel[$i])*floatval($request->service_charge[$i]);

            $total_weightO += floatval($request->total_weight[$i]);
            $total_no_of_parcelO += intval($request->no_of_parcel[$i]);
            $tempo_chargeO += intval($request->no_of_parcel[$i])*floatval($request->tempo_charge[$i]);
            $service_chargeO += intval($request->no_of_parcel[$i])*floatval($request->service_charge[$i]);
            $delivery_chargeO += $delivery_charge_i;
            $customer_estimation_asset_valueO += floatval($request->estimation_value[$i]);


            $parceldata = [
                'order_id' => $id,
                'no_of_parcel' => intval($request->no_of_parcel[$i]),
                'goods_type_id' => intval($request->goods_type_id[$i]),
                'goods_weight' => floatval($request->goods_weight[$i]),
                'total_weight' => floatval($request->total_weight[$i]),
                'tempo_charge' => floatval($request->tempo_charge[$i]),
                'service_charge' => floatval($request->service_charge[$i]),
                'estimation_value' => floatval($request->estimation_value[$i]),
                'delivery_charge' => $delivery_charge_i,
                'is_active' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];

            if($request->goods_type_id[$i]==constants('goods_type_id_other')){
               $parceldata['other_text'] = $request->other_text[$i];
            }
            else{
                $parceldata['other_text'] = NULL;
            }
                
            $parcelid = insert_data_id($this->tbl2,$parceldata);
                $i++;
            }


            $total_payable_amount = $insertData['redeliver_charge'] + $insertData['min_order_value_charge'] - $insertData['discount'] + $delivery_chargeO;

        
            $ORDERDATA = [
                'total_no_of_parcel' => $total_no_of_parcelO,
                'total_weight' => $total_weightO,
                'tempo_charge' => $tempo_chargeO,
                'service_charge' => $service_chargeO,
                'final_cost' => $delivery_chargeO,
                'customer_estimation_asset_value' => $customer_estimation_asset_valueO,
                'updated_at' => date('Y-m-d H:i:s'),
                'total_payable_amount' => $total_payable_amount,
            ];

            if($is_sendsms==1){
            $paymentPageUrl = env('CLIENTBASE_URL')."#/payment?order=".$id;
            pushNotificationToUser(constants('user_notification_type.on_approve_order.name'), "Dear Customer, Your Order has been Created By Bigdaddy. Please Checkout to Confirm This Order.",'','',$paymentPageUrl, $insertData['user_id']);
            createCustomerNotificationLogs("Your Order has Been Created By Bigdaddy. Please Checkout to Confirm Your Order.", $insertData['user_id'] , $id ,constants('notification_type.success'));
            sendMsg("Dear Customer, Please Click Here ".$paymentPageUrl." to Pay For Your Order.", $insertData['contact_person_phone_number']);


            pushNotificationToUserApp("Order ".constants('user_notification_type.on_approve_order.name'), "Dear Customer, Your Order has been Created By Bigdaddy. Please Checkout to Confirm This Order.",  $insertData['user_id'] , ['order_id' => $id, 'user_notification_type' => constants('user_notification_type.on_approve_order.name') ]);

            $ORDERDATA['status'] = 'OA';

            }
                
            Order::where('id', $id)->update($ORDERDATA);

            $getThisOrderData = Order::where('id', $id)->first();

            $total_payable_amount = $getThisOrderData->redeliver_charge + $getThisOrderData->min_order_value_charge - $getThisOrderData->discount + $getThisOrderData->final_cost;

             Order::where('id', $id)->update(['total_payable_amount' => $total_payable_amount]);

            }

            if($request->createmultipleorderid==0){
                Session::flash('lastorderid', $id);
            }
            createOrderLogs("Order Created By ".Session::get('fullname'), $id);
            createOrderLogs("Order Created By Bigdaddy.", $id, 1);
            Session::flash('msg', ' Order Created Successfully!');
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
            Session::flash('msg', 'Order Not Created Or '.$e->getMessage());
            Session::flash('cls', 'danger');
            return redirect()->back()->withError($e->getMessage());
        }
    }

    public function getdata_delivered(Request $request){
        //$orderStatusForInvoice = array_merge(constants('order_status.delivered_orders'),constants('order_status.transit_orders'));
        $order_status = "'" . implode ( "', '", constants('order_status.delivered_orders') ) . "'";
        $order_status_sql =  " and o.status IN(".$order_status.") ";

        $filter_payment_status_Sql = '';
        if(isset($request->payment_status) && $request->payment_status!=''){
            $filter_payment_status_Sql = ' and o.payment_status LIKE "'.$request->payment_status.'" '; 
        }


        $columns = array(          
            0 => 'o.bigdaddy_lr_number',
            1 => 'o.transporter_lr_number',
            2 => 'u.fullname',
            3 => 'o.pickup_location',
            4 => 'o.final_cost',
            5 => 'o.created_at',
            6 => 'o.driver_id',
            7 => 'o.created_at',
        );

        $sql = "select o.*,inv.invoice_number,inv.invoice_file,inv.created_at as invcreated_at,shps.name as shpspayment_status, shps.classhtml as shpsclasshtml, d.fullname as driver_name,u.fullname as ufullname,u.business_name as ubusiness_name,u.mobile as umobile,sh.name as shname, sh.classhtml as shclasshtml from ".$this->tbl." o  INNER JOIN tbl_users u ON u.id=o.user_id
        LEFT JOIN tbl_drivers d ON d.id=o.driver_id
        LEFT JOIN tbl_short_helper sh ON sh.short=o.status and sh.type='order_status_type'
        LEFT JOIN tbl_short_helper shps ON shps.short=o.payment_status and shps.type='payment_status'
        LEFT JOIN ".$this->tbl3." inv ON inv.id=o.invoice_id
        WHERE o.is_active!='2' $filter_payment_status_Sql $order_status_sql ";


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
            $nestedData[] = $row->bigdaddy_lr_number;
            $nestedData[] = $row->transporter_lr_number;

            $invoiceBtn = ''; $payment_statusBtn = '';

            if($row->invoice_number!=""){
                $invoiceBtn = "<br><a target='_blank' href='".sendPath().constants('dir_name.invoice').'/'.$row->invoice_file."'><span title='view invoice #".$row->invoice_number."' data-filename=".$row->invoice_file." data-folder=".constants('dir_name.invoice')." class='shadow-none badge badge-secondary spancursorcls filedownloaditno'>".$row->invoice_number."</span></a>";
            }

            $nestedData[] = $row->ufullname."<br>". $row->ubusiness_name."<br>". $row->umobile.$invoiceBtn;
            $nestedData[] = "Pickup Address - ".$row->pickup_location."<br><br>"."Drop Address - ".$row->drop_location;
            $nestedData[] = $row->final_cost. " Rs";
            $nestedData[] = Carbon::parse($row->created_at)->format('d-m-Y')."<br>".Carbon::parse($row->created_at)->format('h:i:s A');

            $payment_status = "<br><span class='shadow-none badge badge-".$row->shpsclasshtml."' >".$row->shpspayment_status."</span>";
            $status = "<span class='shadow-none badge badge-".$row->shclasshtml."'>".$row->shname."</span>";

            $nestedData[] = $status.$payment_status;
            $nestedData[] = $row->driver_name;

            $logsBtn = "<a data-toggle='modal' data-target='#logsModal' class='btn btn-info btn-sm button_margin_bottom_5' onclick='seeLogs(".$row->id.")'><i class='far fa-eye'></i></a>";

            $viewBtn = "<a href='".route('view-order').'/'.$row->id."' class='btn btn-info btn-sm button_margin_bottom_5'>View</a>"; 

            if($row->payment_status == constants('payment_status.Pending')){
                //$payment_statusBtn = '<a href="javascript:void(0)" class="button_margin_bottom_5 btn btn-rounded btn-outline-danger paymentregisterit" data-id="'.$row->id.'" data-val="'.constants('payment_status.Paid').'" data-toggle="tooltip" data-placement="left" title="Mark As Paid"><i class="far fa-check-circle"></i></a>';
            }
            

            $nestedData[] = $payment_statusBtn." ".$logsBtn . " " .$viewBtn;
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

    public function getdata_cancelled(Request $request){
        $order_status = "'" . implode ( "', '", constants('order_status.cancelled_orders') ) . "'";
        $order_status_sql =  " and o.status IN(".$order_status.") ";

        $columns = array(          
            0 => 'o.bigdaddy_lr_number',
            1 => 'o.transporter_lr_number',
            2 => 'u.fullname',
            3 => 'o.pickup_location',
            4 => 'o.final_cost',
            5 => 'o.created_at',
            6 => 'o.driver_id',
            7 => 'o.created_at',
        );

        $sql = "select o.*,inv.invoice_number,inv.invoice_file,inv.created_at as invcreated_at,d.fullname as driver_name,u.fullname as ufullname,u.business_name as ubusiness_name,u.mobile as umobile,sh.name as shname, sh.classhtml as shclasshtml from ".$this->tbl." o INNER JOIN tbl_users u ON u.id=o.user_id LEFT JOIN tbl_drivers d ON d.id=o.driver_id LEFT JOIN tbl_short_helper sh ON sh.short=o.status and sh.type='order_status_type' LEFT JOIN ".$this->tbl3." inv ON inv.id=o.invoice_id WHERE o.is_active!='2' $order_status_sql ";

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
            $nestedData[] = $row->bigdaddy_lr_number;
            $nestedData[] = $row->transporter_lr_number;

            $invoiceBtn = '';
            if($row->invoice_number!=""){
                $invoiceBtn = "<br><a target='_blank' href='".sendPath().constants('dir_name.invoice').'/'.$row->invoice_file."'><span title='view invoice #".$row->invoice_number."' data-filename=".$row->invoice_file." data-folder=".constants('dir_name.invoice')." class='shadow-none badge badge-secondary spancursorcls filedownloaditno'>".$row->invoice_number."</span></a>";
            }

            $nestedData[] = $row->ufullname."<br>". $row->ubusiness_name."<br>". $row->umobile.$invoiceBtn;
            $nestedData[] = "Pickup Address - ".$row->pickup_location."<br><br>"."Drop Address - ".$row->drop_location;
      
            $nestedData[] = $row->final_cost. " Rs";
            $nestedData[] = Carbon::parse($row->created_at)->format('d-m-Y')."<br>".Carbon::parse($row->created_at)->format('h:i:s A');
            $status = "<span class='shadow-none badge badge-".$row->shclasshtml."'>".$row->shname."</span>";
            $nestedData[] = $status;
            $nestedData[] = $row->driver_name;
            $logsBtn = "<a style='cursor: pointer;' data-toggle='modal' data-target='#logsModal' class='btn btn-info btn-sm button_margin_bottom_5' onclick='seeLogs(".$row->id.")'><i class='far fa-eye'></i></a>";
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

    public function getdata_customerwise(Request $request){
        $columns = array(          
            0 => 'o.bigdaddy_lr_number',
            1 => 'o.transporter_lr_number',
            2 => 'u.fullname',
            3 => 'o.pickup_location',
            4 => 'o.final_cost',
            5 => 'o.status',
            6 => 'o.driver_id',
            7 => 'o.created_at',
        );


        $sql = "select o.*,inv.invoice_number,inv.invoice_file,inv.created_at as invcreated_at,d.fullname as driver_name,u.fullname as ufullname,u.business_name as ubusiness_name,u.mobile as umobile,sh.name as shname, sh.classhtml as shclasshtml from ".$this->tbl." o 
        INNER JOIN tbl_users u ON u.id=o.user_id 
        LEFT JOIN tbl_drivers d ON d.id=o.driver_id 
        LEFT JOIN tbl_short_helper sh ON sh.short=o.status and sh.type='order_status_type' 
        LEFT JOIN ".$this->tbl3." inv ON inv.id=o.invoice_id
        WHERE o.is_active!='2' and o.user_id=".$request->uid." ";


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
            
            $checkbox = "";
            $nestedData = array();
            if($row->driver_id>0 && $row->invoice_number==''){
               $checkbox =  " &nbsp;&nbsp;&nbsp; <input class='selectit' type='checkbox' id='chk' data-id='".$row->id."' name='selectlist[]' > ";
            }

            $invoiceBtn = '';
            if($row->invoice_number!=""){
                $invoiceBtn = "<br><a target='_blank' href='".sendPath().constants('dir_name.invoice').'/'.$row->invoice_file."'><span title='view invoice #".$row->invoice_number."' data-filename=".$row->invoice_file." data-folder=".constants('dir_name.invoice')." class='shadow-none badge badge-secondary spancursorcls filedownloaditno'>".$row->invoice_number."</span></a>";
            }

            $nestedData[] = $row->bigdaddy_lr_number.$checkbox ;
            $nestedData[] = $row->transporter_lr_number;
            $nestedData[] = $row->contact_person_name."<br>". $row->ubusiness_name."<br>". $row->contact_person_phone_number.$invoiceBtn;
            $nestedData[] = "Pickup Address - ".$row->pickup_location."<br><br>"."Drop Address - ".$row->drop_location;
            $nestedData[] = $row->final_cost. " Rs";
            $nestedData[] = Carbon::parse($row->created_at)->format('d-m-Y')."<br>".Carbon::parse($row->created_at)->format('h:i:s A');

            $status = "<span class='shadow-none badge badge-".$row->shclasshtml."'>".$row->shname."</span>";
            $nestedData[] = $status;
            $nestedData[] = $row->driver_name;

            $logsBtn = "<a style='cursor: pointer;' data-toggle='modal' data-target='#logsModal' class='btn btn-info btn-sm button_margin_bottom_5' onclick='seeLogs(".$row->id.")'><i class='far fa-eye'></i></a>";

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

    public function get_order_with_lrnumber_only_tobeassigned(Request $request){
        if(isset($request->order_status) && $request->order_status!='')
        {
            $order_status_sql =  " and o.status IN(".$request->order_status.") ";
        }
        else
        {
            $order_status_sql =  " and o.status NOT IN ('D','C','CU') ";
        }
        
        $columns = array(          
            0 => 'o.bigdaddy_lr_number',
        );
        
        
        
        $sql = "select o.*,d.fullname as driver_name from ".$this->tbl." o LEFT JOIN tbl_drivers d ON d.id=o.driver_id WHERE o.is_active!='2'  $order_status_sql ";
    
        $query = qry($sql);
        $totalData = count($query);
        $totalFiltered = $totalData;

        if (!empty($request['search']['value'])) {   
            $searchString = "'%" . str_replace(",", "','", $request['search']['value']) . "%'"; 
            $sql .= " and ( o.id LIKE " . $searchString;
            $sql .= " OR o.bigdaddy_lr_number LIKE " . $searchString;
            $sql .= " OR o.pickup_location LIKE " . $searchString;
            $sql .= " OR o.transporter_name LIKE " . $searchString;
            $sql .= " OR o.drop_location LIKE " . $searchString . ")";
        }

        $query = qry($sql);
        $totalFiltered = count($query);

        $sql .= " ORDER BY " . $columns[$request->order[0]['column']] . "   " . $request->order[0]['dir'] . " LIMIT " . $request->start . " ," . $request->length . "   ";  
        $query = qry($sql);

        $data = array();

        $cnts = $request->start + 1;

        foreach ($query as $row) {
            $nestedData = array();
            
            $nestedData[] = $row->bigdaddy_lr_number. "&nbsp;&nbsp;&nbsp; <input class='selectit' type='checkbox' id='chk' data-id='".$row->id."' name='selectlist[]' > ";

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

    public function getdata_address_bycustomerid(Request $request){
        $columns = array(          
            0 => 'u.id',
            1 => 'u.address',
        );

        $sql = "select u.* from tbl_address u WHERE u.is_active=".constants('is_active_yes')." and u.user_id=".$request->uid." ";

        $query = qry($sql);
        $totalData = count($query);
        $totalFiltered = $totalData;

        if (!empty($request['search']['value'])) {   
            $searchString = "'%" . str_replace(",", "','", $request['search']['value']) . "%'"; 
            $sql .= " and ( u.id LIKE " . $searchString;
            $sql .= " OR u.city LIKE " . $searchString;
            $sql .= " OR u.address LIKE " . $searchString;
            $sql .= " OR u.landmark LIKE " . $searchString;
            $sql .= " OR u.state LIKE " . $searchString;
            $sql .= " OR u.pincode LIKE " . $searchString . ")";
        }

        $query = qry($sql);
        $totalFiltered = count($query);

        $sql .= " ORDER BY " . $columns[$request->order[0]['column']] . "   " . $request->order[0]['dir'] . " LIMIT " . $request->start . " ," . $request->length . "   ";  
        $query = qry($sql);

        $data = array();

        $cnts = $request->start + 1;
        foreach ($query as $row) {

            $address = $row->address.", ".$row->city."-".$row->pincode;

            $select = "<a style='cursor: pointer;' class='btn btn-primary btn-sm selectaddressit' data-address='".$address."' data-longitude='".$row->longitude."' data-latitude='".$row->latitude."' data-id='".$row->id."' data-typeval='".$request->val."' data-contact_person_name='".$row->contact_person_name."' data-contact_person_number='".$row->contact_person_number."' data-transporter_name='".$row->transporter_name."' >Select</a>";

            $nestedData = array();
            $nestedData[] = $select;
            $nestedData[] = $address;
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

    public function change_order_status_delivered(Request $request){
        $updateData = [
            'delivered_datetime' => date('Y-m-d H:i:s'),
            'status' => 'D',
        ];
        Order::whereIn('id', [ $request->oid ])->update($updateData);
        OrderArrange::where('order_id', $request->oid)->where('is_active' , constants('is_active_yes'))->where('is_completed' , constants('confirmation.no'))->whereIn('arrangement_type' , constants('arrangement_type'))->delete();
        createOrderLogs("Order Successfully Delivered.", $request->oid, 1);
        $id = createOrderLogs("Order Delivered By ".Session::get('fullname'),$request->oid);
        echo json_encode($id);
    }

    public function change_order_status_cancelled(Request $request){
        $updateData = [
            'cancelled_datetime' => date('Y-m-d H:i:s'),
            'status' => 'C',
            'if_cancelled_reason_text' => isset($if_cancelled_reason_text) ? $if_cancelled_reason_text : NULL,
        ];
        update_data($this->tbl, $updateData ,['id' => $request->oid ]);

        OrderArrange::where('order_id', $request->oid)->where('is_active' , constants('is_active_yes'))->where('is_completed' , constants('confirmation.no'))->whereIn('arrangement_type' , constants('arrangement_type'))->delete();

        createOrderLogs("Order Cancelled Due to Some Reason.", $request->oid, 1);
        $id = createOrderLogs("Order Cancelled By ".Session::get('fullname'),$request->oid);
        echo json_encode($id);
    }


    public function deleteThisOrder(Request $request){
        $validator = Validator::make($request->all(), [ 
            'oid' => 'required|numeric',
            'uid' => 'required|numeric',
            'orderdateid' => 'required',
        ]);   

        if($validator->fails() || Session::get("adminrole")!=constants('admins_type.SuperAdmin')) {   
            $response = ['msg' => 'Order Can Not Be Deleted.', 'success' => 0];       
            return response()->json($response);
        }

        $orderLastData = Order::with([
            'orderParcel' => function($qryOrderParcel) {
                $qryOrderParcel->with([
                    'goodsType' => function($qryGoodsType) {
                        $qryGoodsType->where('is_active','!=', 2);
                    },
                ]);
                $qryOrderParcel->where('is_active', 0);
            },
            'orderFile' => function($qryOrderFile) {
                $qryOrderFile->where('is_active',constants('is_active_yes'));
            },
            'invoice' => function($qryInvoice) {
                $qryInvoice->where('is_active',constants('is_active_yes'));
            },
            'order_logs' => function($qryOrder_logs) {
            },
            'order_arrange' => function($qryOrder_arrange) {
                $qryOrder_arrange->where('is_active',constants('is_active_yes'));
            },
        ])
        ->where('is_active','!=', 2)
        ->where('id', $request->oid)
        ->where('user_id', $request->uid)
        ->where('created_at', $request->orderdateid)
        ->first();

        if(!empty($orderLastData)){
            CreateDeletedDataLogs($orderLastData);

            OrderArrange::where('order_id', $request->oid)->where('is_active' , constants('is_active_yes'))->where('is_completed' , constants('confirmation.no'))->whereIn('arrangement_type' , constants('arrangement_type'))->delete();
            OrderLog::where('order_id', $request->oid)->delete();
            OrderReview::where('order_id', $request->oid)->delete();
            OrderParcel::where('order_id', $request->oid)->delete();
            OrderFile::where('order_id', $request->oid)->delete();
            Order::where('id', $request->oid)->delete();

            if(!empty($orderLastData->orderFile)){
                foreach ($orderLastData->orderFile as $key => $value) {
                    $this_img = ($value->img!='') ? $value->img : 0;
                    DeleteFile($this_img, constants('dir_name.order'));
                }
            }

            $response = ['msg' => 'Order Deleted Successfully.', 'success' => 1];       
            return response()->json($response);
        }
        else
        {
            $response = ['msg' => 'Order Not Found.', 'success' => 0];       
            return response()->json($response);
        }
    }


    public function changeOrderStatusToNewOrder(Request $request){
        $count = Order::where('id',$request->oid)->where('status','OA')->count();

        if($count>0){
            $updateData = [
            'status' => 'P',
            'bigdaddy_lr_number' => createBigDaddyLrNumber(),
            ];
            Order::where('id',$request->oid)->update($updateData);
            $id = createOrderLogs("Order Confirmed By ".Session::get('fullname'),$request->oid);
            createOrderLogs("Order Confirmed.",$request->oid, 1);
        }
        else
        {
           $id = 0; 
        }
        echo json_encode($id);
    }

    public function change_status_lruploads_completed(Request $request) {
        $request->validate([
            'status' => 'required|numeric',
            'id' => 'required',
        ]);
         try {
            CustomerUploadedFileOrder::whereIn('id',[ $request->id ])->update(['is_active' => $request->status ]);
            $response = ['msg' => ' Completed Successfully !', 'success' => 1];
            return response()->json($response);
         } catch (\Exception $e) {
            Log::error($e);
            $response = ['msg' => 'Something Went Wrong. Please Try Again!!', 'success' => 0 ];
            return response()->json($response);
        }
    }

    public function markAsPaymentPaid(Request $request) {
        $request->validate([
            'status' => 'required|numeric',
            'id' => 'required',
            'payment_type_manual' => 'required',
            'payment_datetime' => 'required',
        ]);
         try {

            if(!in_array($request->payment_type_manual, array_column(constants('payment_type_manual'), 'short'))){
                $response = ['msg' => 'Bad Request.', 'success' => 0 ];
                return response()->json($response);
            }

            $dataUpdate =[
                'payment_status' => constants('payment_status.Paid'), 
                'payment_datetime' => Carbon::parse($request->payment_datetime)->format('Y-m-d'),
                'payment_type_manual' => $request->payment_type_manual,
                'if_cheque_number' => $request->if_cheque_number,
                'payment_discount' => isset($request->payment_discount) ? floatval($request->payment_discount) : 0,
                'payment_comment' => $request->payment_comment,
                'if_transaction_number' => isset($request->if_transaction_number) ? $request->if_transaction_number : NULL,
            ];

            Order::whereIn('id',[ $request->id])->update($dataUpdate);

            createOrderLogs("Payment Paid is Marked By ".Session::get('fullname'),$request->id);

            $response = ['msg' => ' Completed Successfully !', 'success' => 1];
            return response()->json($response);
         } catch (\Exception $e) {
            Log::error($e);
            $response = ['msg' => 'Something Went Wrong. Please Try Again!!', 'success' => 0 ];
            return response()->json($response);
        }
    }


    public function markAsPaymentPaidInvoice(Request $request) {
        try {

        $validator = Validator::make($request->all(), [ 
            'status' => 'required|numeric',
            'invoice_id' => 'required|integer|min:1',
            'accountid_from' => 'required|integer|min:1',
            'payment_type_manual' => 'required',
            'payment_datetime' => 'required',
            'payment_discount' => 'nullable|numeric|between:1,999999',
        ]);   

        if($validator->fails() || !in_array($request->payment_type_manual, array_column(constants('payment_type_manual'), 'short'))) {          
            $response = ['msg' => 'Bad Request.', 'success' => 0 ];
            return response()->json($response);
        }

            $dataInvoice = Invoice::with([
                'order' => function($qryOrder) {
                    $qryOrder->where('is_active',constants('is_active_yes'));
                },
            ])
            ->where('is_active',constants('is_active_yes'))
            ->where('id', $request->invoice_id)
            ->first();

            if(empty($dataInvoice) && count($dataInvoice->order)>0){
                $response = ['msg' => 'No Order Found.', 'success' => 0 ];
                return response()->json($response);
            }

            $count = count($dataInvoice->order);
            $payment_discountTotal = isset($request->payment_discount) ? floatval($request->payment_discount) : 0;
            $payment_discount = isset($request->payment_discount) ? floatval($request->payment_discount)/$count : 0;
            
            $totalCost = 0 - $payment_discountTotal;

            foreach ($dataInvoice->order as $value) {
                $totalCost += $value->total_payable_amount;
            }


            if($totalCost < 1){
                $response = ['msg' => 'Payment Discount Is Wrong Or Must Be Less Than Order Amount.', 'success' => 0 ];
                return response()->json($response);
            }


           foreach ($dataInvoice->order as $key => $value) {
               $dataUpdate = [
                'payment_status' => constants('payment_status.Paid'), 
                'payment_datetime' => Carbon::parse($request->payment_datetime)->format('Y-m-d'),
                'payment_type_manual' => $request->payment_type_manual,
                'if_cheque_number' => $request->if_cheque_number,
                'payment_discount' => $payment_discount,
                'payment_comment' => $request->payment_comment,
                'if_transaction_number' => isset($request->if_transaction_number) ? $request->if_transaction_number : NULL,
                ];

                Order::whereIn('invoice_id',[ $request->invoice_id])->update($dataUpdate);
                createOrderLogs("Payment Paid is Marked By ".Session::get('fullname'),$value->id);
           }


            $dataAccountManageInvoice = [
                    'transaction_uuid' => random_text(10).uniqid().mt_rand(1000,9999),
                    'transaction_date' => Carbon::parse($request->payment_datetime)->format('Y-m-d'),
                    'amount' => $totalCost,
                    'transaction_type' =>  constants('transaction_type.Credit'),
                    'description' => 'Invoice Payment From #'.$dataInvoice->invoice_number,
                    'anybillno' => 'Invoice #'.$dataInvoice->invoice_number,
                    'anybillno_document' => NULL,
                    'admin_id' => Session::get("adminid"),
                    'is_active' => constants('is_active_yes'),
                    'payment_method' => $request->payment_type_manual,
                    'vendor_id' => NULL,
                    'is_reviewed' => 0,
                    'notes' => "Invoice Payment",
                    'accountid_from' => isset($request->accountid_from) ? $request->accountid_from : constants('cashonhands_bankid'),
                    'accountid_transferredto' => NULL,
                    'is_editable'=> constants('is_editable_yes'),
                    'transaction_subcategory_id' => constants('Income_Invoice_Payment_Subcategory_Id'),
                    'order_id' => NULL,
                    'user_id' => $dataInvoice->order[0]->user_id,
                    'invoice_id' => $request->invoice_id,
            ];

            Transaction::updateOrCreate(['order_id' => NULL, 'is_active' => constants('is_active_yes'), 'invoice_id' => $request->invoice_id, ], $dataAccountManageInvoice );


            $response = ['msg' => ' Completed Successfully !', 'success' => 1];
            return response()->json($response);
         } catch (\Exception $e) {
            Log::error($e);
            $response = ['msg' => 'Something Went Wrong. Please Try Again!!', 'success' => 0 ];
            return response()->json($response);
        }
    }


    public function deleteOrderFiles(Request $request) {
        $request->validate([
            'id' => 'required|numeric',
        ]);
         try {
            $this_img = OrderFile::whereIn('id',[ $request->id ])->first(['img']);
            $this_img = ($this_img!='') ? $this_img : 0;
            DeleteFile($this_img, constants('dir_name.order'));
            OrderFile::whereIn('id',[ $request->id ])->delete();
            $response = ['msg' => ' Deleted Successfully !', 'success' => 1];
            return response()->json($response);
         } catch (\Exception $e) {
            Log::error($e);
            $response = ['msg' => 'Something Went Wrong. Please Try Again!!', 'success' => 0 ];
            return response()->json($response);
        }
    }

    

    
    public function getDataOrderTracking(Request $request){
        
        $columns = array(          
            0 => 'o.bigdaddy_lr_number',
            1 => 'o.transporter_lr_number',
            2 => 'u.fullname',
            3 => 'o.pickup_location',
            4 => 'o.driver_id',
            5 => 'o.created_at',
        );
        
        
        $sql = "select o.*,doa.orderaction_datetime as doaorderaction_datetime,doa.arrangement_number as doaarrangement_number, doa.arrangement_type as doaarrangement_type,doa.id as doaid,inv.invoice_number,inv.invoice_file,inv.created_at as invcreated_at, (select name from tbl_short_helper where short=o.payment_status and type='payment_status') as payment_status, (select classhtml from tbl_short_helper where short=o.payment_status and type='payment_status') as classhtml,d.fullname as driver_name,u.fullname as ufullname,u.business_name as ubusiness_name,u.mobile as umobile,sh.name as shname, sh.classhtml as shclasshtml from ".$this->tbl." o INNER JOIN tbl_users u ON u.id=o.user_id LEFT JOIN tbl_drivers d ON d.id=o.driver_id LEFT JOIN tbl_short_helper sh ON sh.short=o.status and sh.type='order_status_type' LEFT JOIN ".$this->tbl3." inv ON inv.id=o.invoice_id 
        INNER JOIN tbl_driver_order_arrangement doa ON doa.order_id=o.id and doa.orderaction_datetime is not null and doa.is_completed=".constants('confirmation.no')." WHERE o.is_active=".constants('is_active_yes')."  ";
        

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
            $status = "<span class='shadow-none badge badge-".$row->shclasshtml."'>".$row->shname."</span>";
            $payment_status = "<br><span class='shadow-none badge badge-".$row->classhtml."' >".$row->payment_status."</span>";

            $arrangement_type = "<br><span class='shadow-none badge badge-".constants('arrangement_typeName.'.$row->doaarrangement_type.'.classhtml')."' >".constants('arrangement_typeName.'.$row->doaarrangement_type.'.name')."</span>";

            $nestedData = array();
            $nestedData[] = $row->bigdaddy_lr_number." ".$logsBtn.$view_order."<br>".Carbon::parse($row->created_at)->format('d-m-Y')."<br>".Carbon::parse($row->created_at)->format('h:i:s A');
            $nestedData[] = $row->transporter_lr_number." <br>".$status.$payment_status;
            $nestedData[] = $row->ufullname."<br>". $row->ubusiness_name."<br>". $row->umobile.$invoiceBtn;
            $nestedData[] = "Pickup Address<br>".$row->pickup_location."<br><br>"."Drop Address<br>".$row->drop_location;
            $nestedData[] = $row->driver_name.$arrangement_type;



            $pickedup_datetime = "";
            if(strlen($row->pickedup_datetime)>10){
                $pickedup_datetime = "PickUp Time: <br>".Carbon::parse($row->pickedup_datetime)->format('d-m-Y').Carbon::parse($row->pickedup_datetime)->format('h:i:s A');
            }

            
            $seconds = strtotime(date('Y-m-d H:i:s')) - strtotime($row->doaorderaction_datetime);

            if($seconds>0 && 12*3600 > $seconds){
                $timer = $seconds;
            }
            else if($seconds>0 && 12*3600 < $seconds){
                $timer = "<b class='redcolorcls'>Time Over.</b>";
            }
            else
            {
                $timer = "<b>".Carbon::parse($row->doaorderaction_datetime)->format('h:i A')."</b>";
            }
            
        
            $nestedData[] = $timer;
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



    



    






}
