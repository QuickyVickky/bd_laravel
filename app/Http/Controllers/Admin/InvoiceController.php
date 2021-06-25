<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
date_default_timezone_set('Asia/Kolkata');
use Carbon\Carbon;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\ShortHelper;
use App\Models\AccAccountBanks;
use App\Models\CustomerAddress;

class InvoiceController extends Controller
{
    public $tbl = "tbl_invoice";
    public $tbl2 = "tbl_orders";
    public $tbl3 = "tbl_users";
    

    
    public function index(Request $request){
        if_allowedRoute('invoice-list');
        $payment_statusTypeData = ShortHelper::where('is_active', constants('is_active_yes'))->where('type', 'payment_status')->limit(50)->get();
        $dataAccAccountBanks = AccAccountBanks::where('is_active',constants('is_active_yes'))->orderBy('id','DESC')->limit(100)->get();
    	$data = ['tbl' => $this->tbl, 'control' => 'Invoice List', 'payment_status' => $payment_statusTypeData , 'dataAccAccountBanks' => $dataAccAccountBanks, ];
		return view('admin.invoice.list')->with($data);
	}


	public function getdata(Request $request){
        $orderStatusForInvoice = array_merge(constants('order_status.delivered_orders'),constants('order_status.transit_orders'));
        $order_status = "'" . implode ( "', '", $orderStatusForInvoice ) . "'";

        $order_status_sql =  " and ordertbl.status IN(".$order_status.") ";
        $order_payment_pending_sql =  " and ordertbl.payment_status=".constants('payment_status.Pending')." and ordertbl.invoice_id=inv.id  ";


        $filter_payment_status_Sql = '';
        if(isset($request->payment_status) && $request->payment_status!=''){
            $filter_payment_status_Sql = ' and o.payment_status LIKE "'.$request->payment_status.'" '; 
        }


        $columns = array(          
            0 => 'inv.invoice_number',
            1 => 'inv.invoice_date',
            2 => 'inv.created_at',
            3 => 'inv.updated_at',
        );

        $sql = "select inv.*, (select count(ordertbl1.id) from ".$this->tbl2." ordertbl1 where ordertbl1.is_active='".constants('is_active_yes')."' and ordertbl1.invoice_id=inv.id) as ordercount,(select count(ordertbl.id) from ".$this->tbl2." ordertbl where ordertbl.is_active='".constants('is_active_yes')."' ".$order_status_sql.$order_payment_pending_sql.") as getpayment_status,(select c.business_name from ".$this->tbl3." c where c.id=o.user_id) as cbusiness_name,(select c.fullname from ".$this->tbl3." c where c.id=o.user_id) as cfullname from ".$this->tbl." inv INNER JOIN ".$this->tbl2." o ON o.invoice_id=inv.id WHERE inv.is_active=".constants('is_active_yes')." $filter_payment_status_Sql  ";

        $query = qry($sql);
        $totalData = count($query);
        $totalFiltered = $totalData;

        if(!empty($request['search']['value'])) {   
            $searchString = "'%" . str_replace(",", "','", $request['search']['value']) . "%'"; 
            $sql .= " and ( inv.invoice_number LIKE " . $searchString;
            $sql .= " OR inv.invoice_file LIKE " . $searchString;
            $sql .= " OR o.contact_person_name_drop LIKE " . $searchString;
            $sql .= " OR o.contact_person_name LIKE " . $searchString;
            $sql .= " OR o.transporter_lr_number LIKE " . $searchString;
            $sql .= " OR o.bigdaddy_lr_number LIKE " . $searchString . ")";
        }

        $c = count($columns);
            for ($i = 0; $i < $c; $i++) {
                if (!empty($request['columns'][$i]['search']['value'])) {
                    $sql .= " AND " . $columns[$i] . " LIKE '%" . $request['columns'][$i]['search']['value'] . "%'  ";
                }
        }

        $sql .= " GROUP BY o.invoice_id ";  
        

        
        $query = qry($sql);
        $totalFiltered = count($query);

        $sql .= " ORDER BY " . $columns[$request->order[0]['column']] . "   " . $request->order[0]['dir'] . " LIMIT " . $request->start . " ," . $request->length . "   "; 
        $query = qry($sql);


        $data = array();

        $cnts = $request->start + 1;
        foreach ($query as $row) {
            $deleteInvoiceBtn = ''; $invoiceBtn = ''; $payment_statusBtn = ''; $vieworderBtn = '';

            
            if($row->invoice_number!=""){
                $invoiceBtn = "<a target='_blank' href='".sendPath().constants('dir_name.invoice').'/'.$row->invoice_file."'><span title='view invoice #".$row->invoice_number."' data-filename=".$row->invoice_file." data-folder=".constants('dir_name.invoice')." class='shadow-none badge badge-secondary spancursorcls filedownloaditno'> ".$row->invoice_number." </span></a>";

                if(is_allowedHtml('roleclass_delete_btn_invoice')==true){
                    $deleteInvoiceBtn = "<a title='delete invoice #".$row->invoice_number."' class='btn btn-danger btn-sm delete_invoice_btnidcls' data-id=".$row->id." data-inv=".$row->invoice_number.">Delete</a>";
                }
                $vieworderBtn = "<span class='shadow-none badge badge-warning spancursorcls vieworderit' data-id=".$row->id.">view</span>";

            }

            $payment_status = " <span class='shadow-none badge badge-".constants('payment_statusName.'.constants('payment_status.Paid').'.classhtml')."' >".constants('payment_statusName.'.constants('payment_status.Paid').'.name')."</span>";

            if(is_allowedHtml('roleclass_payment_btn_invoice')==true){
                if($row->getpayment_status > 0){
                    $payment_statusBtn = '<a href="javascript:void(0)" class="button_margin_bottom_5 btn btn-rounded btn-outline-danger invoicepaymentregisterit" data-id="'.$row->id.'" data-val="'.constants('payment_status.Paid').'" data-toggle="tooltip" data-placement="left" title="Mark As Paid"><i class="far fa-check-circle"></i></a>';
                    $payment_status = " <span class='shadow-none badge badge-".constants('payment_statusName.'.constants('payment_status.Pending').'.classhtml')."' >".constants('payment_statusName.'.constants('payment_status.Pending').'.name')."</span>";

                }
            }

        
            $nestedData = array();
            $nestedData[] = $row->cbusiness_name."<br>".$row->cfullname."<br>".$invoiceBtn.$payment_status;
            $nestedData[] = Carbon::parse($row->invoice_date)->format('d-m-Y');
            $nestedData[] = $row->ordercount." Order(s)"."<br>".$vieworderBtn;
            $nestedData[] = $payment_statusBtn." ".$deleteInvoiceBtn;

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


    public function getOrderHtmlByInvoiceId(Request $request) {
        $ehtml = '';
        $request->validate([
            'id' => 'required|numeric',
        ]);
         try {


        $getDataOrder = Order::with(['order_status','order_payment_status','order_payment_type'])
        ->whereIn('invoice_id',[ $request->id ])
        ->where('is_active',constants('is_active_yes'))
        ->get();



            if(!empty($getDataOrder)){
                $ehtml .= '<div class="divremovecls">';
                foreach ($getDataOrder as $key => $value) {
                    if($value->payment_status==constants('payment_status.Paid')){
                        $ehtml .= '<b title="Paid">Bigdaddy LR Number : '.$value->bigdaddy_lr_number.'</b><br>';
                    }
                    else
                    {
                        $ehtml .= '<b title="Pending" style="color:red;">Bigdaddy LR Number : '.$value->bigdaddy_lr_number.'</b><br>';
                    }
                    
                }
                $ehtml .= '</div>';
            }

            $response = ['msg' => ' Completed Successfully !', 'success' => 1, 'ehtml' => $ehtml ];
            return response()->json($response);
         } catch (\Exception $e) {
            $response = ['msg' => 'Something Went Wrong. Please Try Again!!', 'success' => 0, 'ehtml' => $ehtml ];
            return response()->json($response);
        }
    }





}
