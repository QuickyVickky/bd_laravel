<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use App\Models\Admin;
use App\Models\ShortHelper;
use App\Models\RoleManage;

date_default_timezone_set('Asia/Kolkata');

class AdminManage extends Controller
{
    public $tbl = "admins";

    public function index(Request $request){
        is_allowedRoute('admins');
        $data = ['tbl' => $this->tbl ];
        return view('admin.admins.list')->with($data);
    }

    public function add_index(Request $request){
        is_allowedRoute('admins');
        $admins_type = ShortHelper::where('type','=','admins_type')->where('is_active','=',0)->orderBy('id', 'DESC')->limit(10)->get();

        $menu_list = RoleManage::where('is_active','=',0)->orderBy('any_order', 'ASC')->limit(2000)->get();
        $data = ['tbl' => $this->tbl, 'control' => 'Admin', 'admins_type' => $admins_type, 'menu_list' => $menu_list ];
        return view('admin.admins.add')->with($data);
    }


    public function view_addedit_index(Request $request){
        is_allowedRoute('admins');
        $admins_type = ShortHelper::where('type','=','admins_type')->where('is_active','=',0)->orderBy('id','DESC')->limit(10)->get();

        $menu_list = RoleManage::where('is_active','=',0)->orderBy('any_order', 'ASC')->limit(2000)->get();
        $Id = isset($_GET['id']) ? trim(intval($_GET['id'])) : 0;

        $one = Admin::where('is_active','!=',2)->where('id','=',$Id)->limit(1)->get();
        $data = ['tbl' => $this->tbl, 'control' => 'Admin', 'one' => $one,  'admins_type' => $admins_type, 'menu_list' => $menu_list  ];
        return view('admin.admins.view')->with($data);
    }

    public function getdata(Request $request){

        $columns = array(          
            0 => 'av.id',
            1 => 'av.fullname',
            2 => 'av.email',
            3 => 'av.is_active',
        );
        $sql = "select av.id,av.fullname,av.email, av.is_active,shat.name as shatname from ".$this->tbl." av 
		LEFT JOIN tbl_short_helper shat ON shat.short=av.role AND shat.type='admins_type'
		WHERE av.is_active!='2' ";
        $query = qry($sql);
        $totalData = count($query);
        $totalFiltered = $totalData;

        if (!empty($request['search']['value'])) {   
            $searchString = "'%" . str_replace(",", "','", $request['search']['value']) . "%'"; 
            $sql .= " and ( av.email  LIKE " . $searchString;
            $sql .= " OR av.mobile  LIKE " . $searchString;
            $sql .= " OR av.fullname  LIKE " . $searchString . ")";
        }

        $query = qry($sql);
        $totalFiltered = count($query);

        $sql .= " ORDER BY " . $columns[$request->order[0]['column']] . "   " . $request->order[0]['dir'] . " LIMIT " . $request->start . " ," . $request->length . "   ";  
        $query = qry($sql);

        $data = array();

        $cnts = $request->start + 1;
        foreach ($query as $row) {
			$admin_typeText = "<br><span class='shadow-none badge badge-info' >".$row->shatname."</span>";
            $nestedData = array();
            $nestedData[] = $cnts;
            $nestedData[] = $row->fullname.$admin_typeText;
            $nestedData[] = $row->email;

            $view = "<a style='cursor: pointer;'  href='".route('view-admin').'?id='.$row->id."' class='btn btn-primary btn-sm editit' data-id='".$row->id."'>View</a>";

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

    public function Add_OR_Update(Request $request) {

        if(!isset($request->hid) OR !is_numeric($request->hid) OR !isset($request->fullname) OR $request->fullname=='' OR !isset($request->email) OR $request->email=='' OR !filter_var($request->email, FILTER_VALIDATE_EMAIL) OR !isset($request->status) OR !is_numeric($request->status) OR !isset($request->admins_type)){
            Session::flash('msg', 'Please Check All Required Fields Properly, Something Went Wrong. Please Try Again!!');
            Session::flash('cls', 'danger');
            return redirect()->back();
        }

        if(($request->admins_type!='A') && !isset($request->menu_to_assign)){
            Session::flash('msg', 'Please Check All Required Fields Properly, Something Went Wrong. Please Try Again!!');
            Session::flash('cls', 'danger');
            return redirect()->back();
        }

        if($request->admins_type=='A'){
           $request->roles_selected_ids = 0;
           $request->roles_notselected_ids = 0;
        }


        if($request->hid==0){

            if(!isset($request->password) OR strlen($request->password)<2){
            Session::flash('msg', 'Please Check All Required Fields Properly, Something Went Wrong. Please Try Again!!');
            Session::flash('cls', 'danger');
            return redirect()->back();
            }

            $sql = "select a.id from ".$this->tbl." a WHERE (a.email='".$request->email."' OR ( a.mobile='".$request->mobile."' AND a.mobile!='')) LIMIT 1 ";
            $count = qry($sql);

            if(!empty($count)){
                Session::flash('msg', 'Please check details Properly, This Email Or Mobile is already Registered !!');
                Session::flash('cls', 'danger');
                return redirect()->back();
            }

            $insertData = [
                'fullname' => $request->fullname,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'password' => bcrypt($request->password),
                'role' => $request->admins_type,
                'is_active' => ($request->status==0) ? 0 : 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'assigned_role_management_ids' => $request->roles_selected_ids,
                'notassigned_role_management_ids' => $request->roles_notselected_ids,
            ];

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
        }
        else if($request->hid>0){

            $sql = "select a.id from ".$this->tbl." a WHERE a.id!=".$request->hid." and ( a.email='".$request->email."' OR ( a.mobile='".$request->mobile."' AND a.mobile!='' )) LIMIT 1 ";

            $count = qry($sql);

            if(!empty($count)){
            Session::flash('msg', 'Please check details Properly, This Email Or Mobile is already Registered !!');
            Session::flash('cls', 'danger');
            return redirect()->back();
            }

            $updateData = [
                'fullname' => $request->fullname,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'role' => $request->admins_type,
                'is_active' => ($request->status==0) ? 0 : 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'assigned_role_management_ids' => $request->roles_selected_ids,
                'notassigned_role_management_ids' => $request->roles_notselected_ids,
            ];

            if(isset($request->password) && strlen($request->password)>1){
                $updateData['password'] = bcrypt($request->password);
                $updateData['active_session'] = NULL;
            }
            update_data($this->tbl, $updateData ,['id' => $request->hid ]);
            Session::flash('msg', ' Updated Successfully!');
            Session::flash('cls', 'success');
        }
        else
        {
           Session::flash('msg', 'Something Went Wrong. Please Try Again!!');
           Session::flash('cls', 'danger');
        }
        return redirect()->back();
    }


  






}
