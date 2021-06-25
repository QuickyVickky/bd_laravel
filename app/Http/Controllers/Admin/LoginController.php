<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
date_default_timezone_set('Asia/Kolkata');
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Session;
use App\Models\Admin;


class LoginController extends Controller
{
	private $tbl = "admins";
	private $tbl2 = "tbl_role_management";

	public function index(){
		if(Session::get('adminid')!=''){
			return redirect()->route('dashboard');
		}
		else
		{
			return view('admin.login');	
		}
	}

    public function login(Request $request){
    	try {

    	if(!isset($request->hid) OR !is_numeric($request->hid) OR !isset($request->username) OR $request->username=='' OR !isset($request->password) OR $request->password==''){
            Session::flash('msg', 'Invalid Value !!');
            return redirect()->route('loginpage');
        }

        $sql = 'SELECT a.id,a.password,a.email,a.fullname,a.role,a.assigned_role_management_ids,a.notassigned_role_management_ids FROM '.$this->tbl.' a where (a.email="'.trim($request->username).'" OR ( a.mobile="'.trim($request->username).'" and a.mobile!="")) and a.is_active="'.constants('is_active_yes').'" LIMIT 1';

		$user = qry($sql);


		if(!empty($user)){
			if (!password_verify($request->password, $user[0]->password)) 
			{
    			Session::flash('msg', 'Email or Password is wrong !');
            	return redirect()->route('loginpage');
			}
			else
			{
				$notassigned_role_management_ids = isset($user[0]->notassigned_role_management_ids) ? $user[0]->notassigned_role_management_ids : 0;
				$sql = 'select rm.remove_class from '.$this->tbl2.' rm where rm.is_active="0" and rm.remove_class!="" and rm.id IN('.$notassigned_role_management_ids.')   ';
				$toremoveclassessArray = qry($sql);

				$aSql = ''; $notaSql = ' and 1!=1';
				if($user[0]->role!='A'){
					$aSql = ' and rm.id IN('.$user[0]->assigned_role_management_ids.') ';
					$notaSql = ' and rm.id IN('.$user[0]->notassigned_role_management_ids.') ';
				}

				$sql = 'select rm.main_url from '.$this->tbl2.' rm where rm.is_active="0" and rm.main_url!="" '.$aSql.' ORDER BY rm.id,rm.any_order ASC ';
				$assigned_role_management_ids_routes = qry($sql);

				$sql = 'select rm.main_url from '.$this->tbl2.' rm where rm.is_active="0" and rm.main_url!="" '.$notaSql.' ORDER BY rm.id,rm.any_order ASC ';
				$notassigned_role_management_ids_routes = qry($sql);

				$activesession = bcrypt(time().random_text(8).uniqid());
				Session::put('adminid', $user[0]->id);
				Session::put('fullname', $user[0]->fullname);
				Session::put('email', $user[0]->email);
				Session::put('adminrole', $user[0]->role);
				Session::put('admin_assigned_ids', explode(',', $user[0]->assigned_role_management_ids) );
				Session::put('admin_notassigned_ids', explode(',', $user[0]->notassigned_role_management_ids));
				Session::put('admin_assigned_route', array_values(array_column($assigned_role_management_ids_routes, 'main_url')));
				Session::put('admin_notassigned_route', array_values(array_column($notassigned_role_management_ids_routes, 'main_url')));
				Session::put('toremoveclassessArray', array_values(array_column($toremoveclassessArray, 'remove_class')) );

				Session::put('adminactivesession', $activesession);

				$updateData = [
						'active_session' => $activesession,
						'ipaddress' => getIPAddress(), 
						'updated_at' => date('Y-m-d H:i:s'),
				];
        		update_data($this->tbl, $updateData ,['id' => $user[0]->id ]);

        		if($user[0]->role!="A"){
        			if(count(Session::get("admin_assigned_route"))>0){
        				return redirect()->route(Session::get("admin_assigned_route")[0]);
        			}
        			else
        			{
        				echo "you are not assigned any menu";
        				die;
        			}
        			return redirect()->route('dashboard');
        		}
        		else
        		{
        			return redirect()->route('dashboard');
        		}
				
			}
		}
		else
		{
			Session::flash('msg', 'User not found !');
            return redirect()->route('loginpage');
		}

		} catch (\Exception $e) {
            Log::error($e);
            Session::flash('msg', 'Error, Something Went Wrong.');
            Session::flash('cls', 'danger');
            return redirect()->back();
        }
	}











}
