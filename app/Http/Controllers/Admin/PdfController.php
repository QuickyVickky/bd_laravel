<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use PDF;
use Illuminate\Support\Facades\Validator;
use App\Models\Order;
use App\Models\OrderParcel;
use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Models\GoodsType;
use App\Models\Driver;
use App\Models\Invoice;
date_default_timezone_set('Asia/Kolkata');
use Carbon\Carbon;


class PdfController extends Controller
{
    private $tbl = "";

    public function index(Request $request){
        $data = ['tbl' => $this->tbl, 'control' => 'Invoice', 'filename' => "Invoice-".date('Y-m-d H:i:s').".pdf" ];
        return view('admin.pdf.orderinvoice')->with($data);
    }


    public function download_invoice(Request $request){
        if(!isset($request->orderId) OR !isset($request->invoice_date) OR !isset($request->invoice_number) OR !isset($request->user_idhid)){
            Session::flash('msg', 'Something Went Wrong. Please Try Again!!');
            Session::flash('cls', 'danger');
            return redirect()->back();
        }


        $dataInvoiceCount = Invoice::where('invoice_number',$request->invoice_number)->whereNotNull('invoice_number')->count();

        if($dataInvoiceCount>0){
            Session::flash('msg', 'This Invoice Number is Already Used. You may Try to Delete old Invoice #'.$request->invoice_number.' or Use another Invoice Number.');
            Session::flash('cls', 'danger');
            return redirect()->back();
        }

    	$orderIds = trim($request->orderId);
    	if($orderIds==''){
            Session::flash('msg', 'Something Went Wrong. Please Try Again!!');
            Session::flash('cls', 'danger');
            return redirect()->back();
    	}
    	else
    	{
        $orderIds = explode(',', $orderIds);

         //return $orderIds;


        $orderData = Order::with([
            'customer' => function($qryCustomer) {
                $qryCustomer->with([
                    'customerAddressFirst' => function($qryAddress) {
                        $qryAddress->orderBy('is_default','DESC');
                    },
                ]);
                $qryCustomer->where('is_active', 0);
            },
            'orderParcel' => function($qryOrderParcel) {
                $qryOrderParcel->with([
                    'goodsType' => function($qryGoodsType) {
                        $qryGoodsType->where('is_active','!=', 2);
                    },
                ]);
                $qryOrderParcel->where('is_active', 0);
            },

            'driver' =>  function($qryDriver) {
                    $qryDriver->where('is_active','!=', 2);
            },
            'vehicle' => function($qryVehicle) {
                
            },
            'invoice' => function($qryInvoice) {
                $qryInvoice->where('is_active',constants('is_active_yes'));
            },

        ])
        ->where('is_active','!=', 2)
        ->where('driver_id','>', 0)
        ->where('user_id', $request->user_idhid)
        ->whereNotIn('status', constants('order_status.cancelled_orders'))
        ->whereIn('id', $orderIds)
        ->get()->toArray();




        if(count($orderData)==0){
            Session::flash('msg', 'No Order Found or You Can Not Create Invoice.');
            Session::flash('cls', 'danger');
            return redirect()->back();
        }



        $invoiecFilename = str_replace(' ', '_', $request->invoice_number."_".$orderData[0]['customer']['fullname'].implode('_', array_column($orderData, 'bigdaddy_lr_number')) ."_".date('FYdhisA')."_".mt_rand(100,999)."_invoice".count($orderData).".pdf");

        $data = [
        'control' => 'Invoice', 
        'filename' => $invoiecFilename, 
        'invoice_date' => Carbon::parse($request->invoice_date)->format('Y-m-d'), 
        'invoice_number' => $request->invoice_number,
        'orderData' => $orderData,
        ];

        $path = public_path('/storage') . '/' . constants('dir_name.invoice');

        //return view('admin.pdf.orderinvoice')->with($data);

        //$customPaper = array(0,0,210*3,148*3);


        $pdf = PDF::loadView('admin.pdf.orderinvoice', $data)->setPaper('A4', 'portrait');
        //->setPaper($customPaper);
        //->setPaper('A4', 'portrait');
        //$pdf->setPaper('A5', 'landscape');
        //Landscape  horizontal ----
        //portrait vertical |

        $pdf->save($path . '/' . $invoiecFilename);






        $insertInvoiceData = [
            'invoice_file'  => $invoiecFilename,
            'invoice_number'  => $request->invoice_number,
            'invoice_date'  => Carbon::parse($request->invoice_date)->format('Y-m-d'), 
            'is_active' => constants('is_active_yes'),
        ];


        $lastDataInvoice = Invoice::create($insertInvoiceData);


        if(is_array($orderData) && !empty($orderData)){
            foreach($orderData as $orderRow) {

                if(isset($orderRow['invoice']['invoice_file']) && $orderRow['invoice']['invoice_file']!=''){
                    DeleteFile($orderRow['invoice']['invoice_file'], constants('dir_name.invoice'));
                        $orderinvoiceDataNull = [
                            'invoice_id'  => NULL,
                        ];
                    Order::where('id',$orderRow['id'])->orWhere('invoice_id',$orderRow['invoice']['id'])->update($orderinvoiceDataNull);
                    createOrderLogs("Invoice Deleted By ".Session::get('fullname'), $orderRow['id']);
                }

                $invoiceData = [
                    'invoice_id'  => $lastDataInvoice->id,
                ];

                Order::where('id',$orderRow['id'])->update($invoiceData);
                createOrderLogs("New Invoice #".$request->invoice_number." Created By ".Session::get('fullname'), $orderRow['id']);
            }
        }

        return $pdf->download($data['filename']);
        return redirect()->back();

    	}
    }


    public function delete_invoice(Request $request) {
        $validator = Validator::make($request->all(), [ 
              'id' => 'required|integer|min:1', /*-- no use of id send any number--*/
              'invoice_number' => 'required',
             ]);   

          if($validator->fails()) {          
            return response()->json([
                    'success' => 0,
                    'msg' => "Validation Failed.",
            ]);                       
         }

         try {

            $from = date('Y-m-d', strtotime(date('Y-m-d') . ' -15 day'));
            $to = date('Y-m-d', strtotime(date('Y-m-d') . ' +1 day'));

            $dataInvoiceOne = Invoice::where('invoice_number',$request->invoice_number)->whereBetween('created_at', [$from, $to])->first();

            if(empty($dataInvoiceOne)){
                return response()->json([
                'success' => 0,
                'msg' => "Can Not Be Deleted As Invoice Deletion Period is Over.",
                ]); 
            }

            $this_img = ($dataInvoiceOne->invoice_file!='') ? $dataInvoiceOne->invoice_file : 0;
            DeleteFile($this_img, constants('dir_name.invoice'));

            $orderDataList = Order::where('invoice_id',$dataInvoiceOne->id)->whereNotNull('invoice_id')->get(['id']);
            
            if(!empty($orderDataList)){
                foreach ($orderDataList as $value) {
                createOrderLogs("Invoice Deleted By ".Session::get('fullname'), $value->id);
                }
            }

            $orderinvoiceData = [
                    'invoice_id'  => NULL,
            ];
            Order::where('invoice_id',$dataInvoiceOne->id)->update($orderinvoiceData);
            Invoice::where('invoice_number',$request->invoice_number)->delete();

            $response = ['msg' => ' Deleted Successfully !', 'success' => 1];
            return response()->json($response);
         } catch (\Exception $e) {
            $response = ['msg' => 'Something Went Wrong. Please Try Again!!', 'success' => 0 ];
            return response()->json($response);
        }
    }

        /*-----get_latest_invoice_number auto generatd-------*/
    public function getLatestInvoiceNumber(Request $request) {
            $lastInvoiceNumber = Invoice::max('invoice_number');
            $nextInvoiceNumber = intval($lastInvoiceNumber) + 1;
            $response = ['msg' => '', 'success' => 1, 'invoice_number' => $nextInvoiceNumber ];
            return response()->json($response);
    }


    




  






}
