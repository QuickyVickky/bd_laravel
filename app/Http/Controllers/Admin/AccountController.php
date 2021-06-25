<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
date_default_timezone_set('Asia/Kolkata');

class AccountController extends Controller
{
    public $tbl = "tbl_account_manage";

    public function index(Request $request){
        if_allowedRoute('account-list');
        $data = ['tbl' => $this->tbl, 'control' => 'List' ];
        return view('admin.account.list')->with($data);
    }

    public function add_index(Request $request){
        $sql = 'SELECT id,name,short,details FROM tbl_short_helper where type="account_type" and is_active="0" ';
        $account_type = qry($sql);
        $data = ['tbl' => $this->tbl, 'control' => '' , 'account_type' => $account_type ];
        return view('admin.account.add')->with($data);
    }

    public function view_addedit_index(Request $request){
        $Id = isset($_GET['id']) ? trim(intval($_GET['id'])) : 0; $account_type = []; $one = [];
        $sql = 'SELECT am.*,ac.name as sb_name,ac.id as sb_id,(select d.name from tbl_account_category d WHERE d.id=ac.path_to) as mc_name, (select e.id from tbl_account_category e WHERE e.id=ac.path_to) as mc_id
         FROM '.$this->tbl.' am 
        LEFT JOIN tbl_account_category ac ON ac.id=am.account_subcategory_id


        WHERE am.id='.$Id.' and am.is_active!="2" LIMIT 1';
        $one = qry($sql);
        if(!empty($one)){
            $sql = 'SELECT id,name,short,details FROM tbl_short_helper where type="account_type" and is_active="0" ';
            $account_type = qry($sql);
        }
        $data = ['tbl' => $this->tbl, 'control' => 'Edit', 'one' => $one, 'account_type' => $account_type ];
        return view('admin.account.edit')->with($data);
    }

    public function getedit(Request $request){
        $user = qry('SELECT * FROM '.$this->tbl.' where id='.$request->id.' LIMIT 1');
        echo json_encode($user);
    }

    public function getdata(Request $request){

        $columns = array(          
            0 => 'am.id',
            1 => 'am.amount',
            2 => 'am.account_datetime',
            3 => 'ac.name',
            4 => 'sh.name',
            5 => 'am.anybillno',
            6 => 'am.comments',
            7 => 'am.created_at',
        );


        $sql = "select am.*,sh.name as account_type,sh.classhtml, ac.name as sub_category_name,(select d.name from tbl_account_category d WHERE d.id=ac.path_to ) as main_category_name
        from ".$this->tbl." am 
        LEFT JOIN tbl_account_category ac ON ac.id=am.account_subcategory_id
        LEFT JOIN tbl_short_helper sh ON sh.short=am.type

        WHERE am.is_active!='2' ";

        $query = qry($sql);
        $totalData = count($query);
        $totalFiltered = $totalData;

        if (!empty($request['search']['value'])) {   
            $searchString = "'%" . str_replace(",", "','", $request['search']['value']) . "%'"; 
            $sql .= " and ( am.amount LIKE " . $searchString;
            $sql .= " OR am.anybillno LIKE " . $searchString;
            $sql .= " OR sh.name LIKE " . $searchString;
            $sql .= " OR ac.name LIKE " . $searchString;
            $sql .= " OR am.comments  LIKE " . $searchString . ")";
        }


        $query = qry($sql);
        $totalFiltered = count($query);

        $sql .= " ORDER BY " . $columns[$request->order[0]['column']] . "   " . $request->order[0]['dir'] . " LIMIT " . $request->start . " ," . $request->length . "   ";  
        $query = qry($sql);

        $data = array();

        $cnts = $request->start + 1;
        foreach ($query as $row) {
            $view = ""; $delete = ""; $edit = "";  $viewFileBtn = '';
            $account_type = "<br><span class='shadow-none badge badge-".$row->classhtml."' >".$row->account_type."</span>";
            $nestedData = array();
            $nestedData[] = $cnts;
            $nestedData[] = "&#x20B9; ".$row->amount;
            $nestedData[] = date('d-M-Y', strtotime($row->account_datetime));
            $nestedData[] = $row->main_category_name."<br>".$row->sub_category_name;
            $nestedData[] = $account_type;

            if($row->anybillno_document!=''){
                $viewFileBtn = "<br><a target='_blank' href='".sendPath().constants('dir_name.bill').'/'.$row->anybillno_document."'><span class='shadow-none badge badge-info' >file</span></a>";
            }

            $nestedData[] = $row->anybillno.$viewFileBtn;
            $nestedData[] = $row->comments;


            if(is_allowedHtml('roleclass_view_btn_accountdetails')==true){
                $view = "<a style='cursor: pointer;' data-anybillno_document='".$row->anybillno_document."' data-billno='".$row->anybillno."' class='btn btn-primary btn-sm viewit' data-id='".$row->id."'><i class='fas fa-eye'></i></a>";
            }
            if(is_allowedHtml('roleclass_delete_btn_accountdetails')==true){
                $delete = "<a style='cursor: pointer;' data-anybillno_document='".$row->anybillno_document."' class='btn btn-danger btn-rounded btn-sm deleteit' data-id='".$row->id."'><i class='fas fa-trash'></i></a>";
            }
            if(is_allowedHtml('roleclass_edit_btn_accountdetails')==true){
                 $edit = "<a style='cursor: pointer;' href='".route('view-account').'?id='.$row->id."' class='btn btn-info btn-rounded btn-sm' data-id='".$row->id."'><i class='fas fa-edit'></i></a>";
            }

            $nestedData[] = $view." ".$delete." ".$edit;
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


    public function Add(Request $request) {
        $request->validate([
            'is_active'=>'required',
            'select_main_category'=>'required|numeric',
            'account_datetime'=>'required|date',
            'amount'=>'required|between:0,9999999999.99',
            'account_type'=>'required',
            'hid'=>'required',
        ]);

        try {


            $insertData = [
                'account_datetime' => $request->account_datetime,
                'account_subcategory_id' => isset($request->select_sub_category) ? $request->select_sub_category : $request->select_main_category ,
                'amount' => $request->amount,
                'type' => $request->account_type,
                'comments' => isset($request->comments) ? $request->comments : NULL,
                'anybillno' => isset($request->anybillno) ? $request->anybillno : NULL,
                'added_by' => Session::get("adminid"),
                'is_active' => ($request->status==0) ? 0 : 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ];

            if($request->hasFile('anybillno_document')){
                $insertData['anybillno_document'] = UploadImage($request->file('anybillno_document'), constants('dir_name.bill'), 'bill');
            }

            $id = insert_data_id($this->tbl,$insertData);

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
        } catch (\Exception $e) {
            return $e;
           return redirect()->back()->withError($e->getMessage());
        }
    }

    public function Update(Request $request) {
        $request->validate([
            'is_active'=>'required',
            //'select_main_category'=>'required|numeric',
            'account_datetime'=>'required|date',
            'amount'=>'required|between:0,9999999999.99',
            'account_type'=>'required',
            'hid'=>'required',
        ]);

        try {

            

            $updateData = [
                'account_datetime' => $request->account_datetime,
                //'account_subcategory_id' => isset($request->select_sub_category) ? $request->select_sub_category : $request->select_main_category ,
                'amount' => $request->amount,
                'type' => $request->account_type,
                'comments' => isset($request->comments) ? $request->comments : NULL,
                'anybillno' => isset($request->anybillno) ? $request->anybillno : NULL,
                'is_active' => ($request->status==0) ? 0 : 1,
                'updated_at' => date('Y-m-d H:i:s'),
            ];

            if($request->hasFile('anybillno_document')){
                $updateData['anybillno_document'] = UploadImage($request->file('anybillno_document'), constants('dir_name.bill'), 'bill');
                $request->existing_img = isset($request->existing_img) ? $request->existing_img : '0';
                DeleteFile($request->existing_img, constants('dir_name.bill'));
            }

                update_data($this->tbl, $updateData ,['id' => $request->hid ]);
                Session::flash('msg', ' Updated Successfully!');
                Session::flash('cls', 'success');
                return redirect()->back();
        } catch (\Exception $e) {
            return $e;
           return redirect()->back()->withError($e->getMessage());
        }
    }

    public function delete_expenses(Request $request) {
        $updateData = [
            'is_active' => '2',
        ];
        update_data($this->tbl, $updateData ,['id' => $request->id ]);
        $request->existing_img = isset($request->existing_img) ? $request->existing_img : '0';
        DeleteFile($request->existing_img, constants('dir_name.bill'));
        return response()->json($request->id);
    }
    



  






}
