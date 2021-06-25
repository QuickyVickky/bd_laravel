<?php
//use DB;
//use Storage;
use App\Models\JWT;
use App\Events\AlertEvent;
use App\Events\AlertEventUserWeb;
use Illuminate\Support\Facades\Redirect;
use App\Models\ApiLog;
use App\Models\DeletedLog;
use App\Models\OTP;
use App\Service\TOKEN;
use App\Models\AccessToken;
use App\Models\TempToken;
use App\Models\Driver;
use App\Models\Customer;
use Illuminate\Support\Facades\Log;


function pushNotificationToAdmin($title,$text1,$icon='',$image='',$linkurl='') {
    try {
    event(new AlertEvent($title, $text1,  $icon, $image, $linkurl));
    } catch(\Exception $e) { }
    return true;
}

function pushNotificationToUser($title,$text1,$icon='',$image='',$linkurl='',$user_id=0) {
    try {
    event(new AlertEventUserWeb($title, $text1,  $icon, $image, $linkurl, $user_id));
    } catch(\Exception $e) { }
    return true;
}

function pushNotificationToDriverApp($title,$text1='',$driver_id=0,$data=[]) {
        
        if($title==''){
            return ['success' => false, 'data' => [], 'msg' => ''];
        }
        try {

            $driverData = Driver::where('id',$driver_id)->first(['device_token']);

            $data = [
                "to" => @$driverData->device_token,
                "notification" =>
                    [
                        "title" => $title,
                        "body" => $text1,
                        "sound" => true,
                        
                    ],
                "data" => [
                    "driver_id" => $driver_id,
                    "custom_data" => $data,
                    ],
                ];

                $dataString = json_encode($data);
                

            $headers = [
                'Authorization: key=' . Config::get('constants.FCM_SERVER_KEY') . '',
                'Content-Type: application/json',
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
            $result = curl_exec($ch);
            curl_close($ch);

            CreateApiLogs($data);
            
        
            return ['success' => true, 'data' => ['result' => $result, 'data' => $data ], 'msg' => '' ];
        } catch (\Exception $e) {
            Log::error($e);
            return ['success' => false, 'data' => [], 'msg' => 'Error' ];
        } 
}

function pushNotificationToUserApp($title,$text1='',$customer_id=0,$data=[]) {
        try {
        $customerData = Customer::where('id', $customer_id)->where('is_active', Config::get('constants.is_active_yes'))->first(['device_token']);
        if($title=='' || empty($customerData)){
            return ['success' => false, 'data' => [], 'msg' => ''];
        }
        
            $data = [
                "to" => @$customerData->device_token,
                "notification" =>
                    [
                        "title" => $title,
                        "body" => $text1,
                        "sound" => true,
                        
                    ],
                "data" => [
                    "user_id" => $customer_id,
                    "custom_data" => $data,
                    ],
                ];

                $dataString = json_encode($data);

            $headers = [
                'Authorization: key=' . Config::get('constants.FCM_SERVER_KEY') . '',
                'Content-Type: application/json',
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
            $result = curl_exec($ch);
            curl_close($ch);

            CreateApiLogs($data);
        
            return ['success' => true, 'data' => ['result' => $result, 'data' => $data ], 'msg' => '' ];
        } catch (\Exception $e) {
            Log::error($e);
            return ['success' => false, 'data' => [], 'msg' => 'Error' ];
        } 
}

function dde($arr){
	echo "<pre>"; print_r($arr);die;
}

function random_text($length_of_string = 8) {
    $chr = 'GHIJKLA123MNOSTUVW0XYZPQR456789BCDEF'; 
    $randomString = ''; 
  
    for ($i = 0; $i < $length_of_string; $i++) { 
        $index = rand(0, strlen($chr) - 1); 
        $randomString .= $chr[$index]; 
    }   
    return $randomString; 
}

function verifyGSTno_local($gstNo=''){
    $api_key = '0kpx9iWrZhajiRLjBQ4h2xrlDvo2';

     $json = '{
              "filing": [
                
              ],
              "compliance": {
                "filingFrequency": "quarterly"
              },
              "taxpayerInfo": {
                "ctb": "Proprietorship",
                "pradr": {
                  "ntr": "Retail Business",
                  "addr": {
                    "lt": "",
                    "dst": "Surat",
                    "pncd": "394107",
                    "stcd": "Gujarat",
                    "bnm": "NR.ROYAL SQUARE,VIP CIRCLE",
                    "flno": "SILVER BUSINESS POINT",
                    "st": "UTRAN",
                    "city": "",
                    "lg": "",
                    "bno": "SHOP NO. 4039, 4TH FLOOR",
                    "loc": "SURAT"
                  }
                },
                "stj": "Ghatak 66 (Surat)",
                "ctjCd": "VC0103",
                "lgnm": "JAYDEEPKUMAR BHUPATBHAI SHYARA",
                "ctj": "RANGE-III",
                "rgdt": "01/07/2017",
                "dty": "Regular",
                "nba": [
                  "Retail Business"
                ],
                "errorMsg": null,
                "tradeNam": "DREAMWORLD SOLUTIONS",
                "sts": "Active",
                "frequencyType": "QUARTERLY",
                "gstin": "24BYFPS3216N1Z5",
                "adadr": [
                  
                ],
                "cxdt": "",
                "stjCd": "GJ066",
                "panNo": "BYFPS3216N"
              }
            }';

        return ['success' => true, 'data' => json_decode($json) , 'msg' => 'Valid GST Number' ];

       
       /*
        if($gstNo=='' OR strlen($gstNo)!=15){
            return ['success' => false, 'data' => NULL, 'msg' => 'InValid GST Number'];
        }
        try {
            $url = 'https://appyflow.in/api/verifyGST?gstNo='.$gstNo.'&key_secret='.$api_key;
            $cSession = curl_init();
            curl_setopt($cSession, CURLOPT_URL, $url);
            curl_setopt($cSession, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($cSession, CURLOPT_HTTPHEADER, array('Content-Type' => 'application/x-www-form-urlencoded' ) );
            $result = curl_exec($cSession);
            curl_close($cSession);

            $resultArray = json_decode($result, true);

            if(isset($resultArray['error']) && $resultArray['error']==true) {
                return ['success' => false, 'data' => NULL , 'msg' => $resultArray['message'] ];
                
            }
            else
            {
                return ['success' => true, 'data' => $resultArray , 'msg' => 'Valid GST Number' ];
            }
            
        }
        catch (\Exception $e) {
            return ['success' => false, 'data' => $e->getMessage() , 'msg' => 'Error' ];
        } 
        */ 
}

function verifyGSTno($gstNo=''){
        $api_key = '0kpx9iWrZhajiRLjBQ4h2xrlDvo2';

        
        if($gstNo=='' OR strlen($gstNo)!=15){
            return ['success' => false, 'data' => NULL, 'msg' => 'InValid GST Number'];
        }
        try {
            $url = 'https://appyflow.in/api/verifyGST?gstNo='.$gstNo.'&key_secret='.$api_key;
            $cSession = curl_init();
            curl_setopt($cSession, CURLOPT_URL, $url);
            curl_setopt($cSession, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($cSession, CURLOPT_HTTPHEADER, array('Content-Type' => 'application/x-www-form-urlencoded' ) );
            $result = curl_exec($cSession);
            curl_close($cSession);

            $resultArray = json_decode($result, true);

            if(isset($resultArray['error']) && $resultArray['error']==true) {
                return ['success' => false, 'data' => NULL , 'msg' => $resultArray['message'] ];
                
            }
            else
            {
                return ['success' => true, 'data' => $resultArray , 'msg' => 'Valid GST Number' ];
            }
            
        }
        catch (\Exception $e) {
            return ['success' => false, 'data' => $e->getMessage() , 'msg' => 'Error' ];
        }  
}

function random_number($length_of_string = 8) {
    $chr = '1234506789'; 
    $randomString = ''; 
    for ($i = 0; $i < $length_of_string; $i++) { 
        $index = rand(0, strlen($chr) - 1); 
        $randomString .= $chr[$index]; 
    }   
    return '9'.$randomString; 
}

function qry($str){
	$data = DB::select($str);
	return $data;
}

function insert_data($tbl,$data){
	DB::table($tbl)->insert($data);
}

function insert_data_id($tbl,$data){
	$id = DB::table($tbl)->insertGetId($data);
	return $id;
}

function update_data($tbl,$data,$con){
	$affected_id = DB::table($tbl)->where($con)->update($data);
	return $affected_id;
}


function UploadImage($file, $dir,$filename_prefix='') {
    $percent = 0.6;
    $fileName = $filename_prefix.uniqid().time() . '.' . $file->getClientOriginalExtension();
    $file->move(public_path('storage'. '/'. $dir), $fileName);
    $urlFile = public_path('/storage') . '/' . $dir . '/' . $fileName;

    $filesize = filesize($urlFile)/1024;

    if($filesize>512 && (in_array(strtolower($file->getClientOriginalExtension()), Config::get('constants.image_extension')))){
        list($width, $height) = getimagesize($urlFile);
        $newwidth = $width * $percent;
        $newheight = $height * $percent;
        $imgThumb = Image::make($urlFile)->resize($newwidth, $newheight, function ($constraint) {
            $constraint->aspectRatio();
        });
        $imgThumb->save($urlFile);
    }
    return $fileName;
}

function UploadImageFromBase64String($base64StringImage, $dir, $filename_prefix='') {
    $fileName = '';
    try {
        if(strlen($base64StringImage)>50){
            $folderPath = public_path('/storage') . '/' . $dir . '/';
            $image_parts = explode(";base64,", $base64StringImage);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];
            $image_base64 = base64_decode($image_parts[1]);
            $fileName = $filename_prefix.uniqid().time() . '.' . $image_type;
            $file = $folderPath.$fileName;
            file_put_contents($file, $image_base64);
            return $fileName;
        }
    } catch(\Exception $e) { Log::error($e); return $fileName; }
    return $fileName;
}

function ResponseMediaLink($file, $dirfolder) {
        $fileResponseLink = '';
        if ($file != "" || $file != NULL) {
            $fileResponseLink = asset('/') . '/' . $dirfolder . '/' . $file;
        }
        return $fileResponseLink;
}

function DeleteFile($filename, $dir) {
    $existImage = public_path('storage/'.$dir.'/'.$filename);
        if (File::exists($existImage)) {
            File::delete($existImage);
        }
    return 1;
}

function DeleteTempFile($minuteAgo=10) {
    foreach(glob(public_path('storage/temp_files/*.*')) as $file) {
        if (is_file($file)) {
            if (time() - filemtime($file) >= 60 * $minuteAgo) { 
                if (File::exists($file)) {
                        File::delete($file);
                }
            }
        }
    }
    return 1;
}

function sendMail($html, $useremail, $username, $subject, $data =[]){
    return 1;
    
    Mail::send('client.mail.msg', $data, function ($message) use ($useremail,$username,$subject) {
            $message->from('xyz@gmail.com', 'Bigdaddy')
                ->to($useremail, $username)
                ->subject($subject);
    });
    return 1;
}

function createOrderLogs($text,$order_id,$type=0){
    $data = [
        "logs" => $text,
        "order_id" => $order_id,
        'created_at' => date('Y-m-d H:i:s'),
        "type" => $type,
    ];
    $id = DB::table('tbl_order_logs')->insertGetId($data);
    return $id;
}

function createAdminNotificationLogs($title,$notification_text,$notification_link=''){
    $data = [
        "title" => $title,
        "notification_text" => $notification_text,
        "notification_link" => $notification_link,
        "created_at" => date('Y-m-d H:i:s'),
    ];
    $id = DB::table('tbl_admin_notifications')->insertGetId($data);
    return $id;
}

function createCustomerNotificationLogs($notification_text,$user_id=0,$order_id=0,$classhtml=NULL){
    $data = [
        "notification_text" => $notification_text,
        "user_id" => $user_id,
        "order_id" => $order_id,
        'created_at' => date('Y-m-d H:i:s'),
        "classhtml" => $classhtml,
    ];
    $id = DB::table('tbl_customer_notifications')->insertGetId($data);
    return $id;
}

function createDriverNotificationLogs($title='',$notification_text,$driver_id=0,$order_id=0){
    $from = date('Y-m-d', strtotime(date('Y-m-d') . ' -30 day'));
    $to = date('Y-m-d', strtotime(date('Y-m-d') . ' +2 day'));
    $data = [
        "title" => $title,
        "notification_text" => $notification_text,
        "driver_id" => $driver_id,
        "order_id" => $order_id,
        'created_at' => date('Y-m-d H:i:s'),
    ];
    $id = DB::table('tbl_driver_notifications')->insertGetId($data);
    DB::table('tbl_driver_notifications')->whereNotBetween('created_at', [$from, $to])->delete();
    return $id;
}

function createBigDaddyLrNumber(){
    $data = DB::select("SELECT id,bigdaddy_lr_number FROM `tbl_orders` where bigdaddy_lr_number!='' and bigdaddy_lr_number>0 ORDER BY bigdaddy_lr_number desc limit 1");
    if(!empty($data)){
        $LrNumber = $data[0]->bigdaddy_lr_number + 1;
        /*$LrNumber = str_repeat("0",6-strlen($number)).$number;*/
    }
    else
    {
        $LrNumber = '1001';
    }
    return intval($LrNumber);
}

function constants($key=''){
    if(trim($key=='')){
        return 0;
    }
    else
    {
        return Config::get('constants.'.$key);
    }
}

function sendPath(){
    return asset('storage').'/';
}


function getLastNDays($days, $format = 'd/m'){
    $m = date("m"); $de= date("d"); $y= date("Y");
    $dateArray = array();
    for($i=0; $i<=$days-1; $i++){
        $dateArray[] = date($format, mktime(0,0,0,$m,($de-$i),$y)); 
    }
    return array_reverse($dateArray);
}

function getAllMenus(){
    $sql = "select rm.has_submenu,rm.id,rm.name,rm.main_url,rm.one_url,rm.details,rm.level,rm.path_to,rm.any_svg,rm.any_html,rm.any_order,rm.remove_class from tbl_role_management rm WHERE rm.level IN(0,1) and rm.is_active='0' ORDER BY rm.any_order ASC LIMIT 600";
    $data = qry($sql);
    return $data;
}


function is_allowedHtml($htmlclass='') {
    if((Session::get("adminrole")!="A" && !in_array($htmlclass, Session::get("toremoveclassessArray"))) || $htmlclass=="anything"){
        return true;
    }
    else if(Session::get("adminrole")=="A"){
      return true;  
    }
    return false;
}

function is_allowedRoute($routename) {
    if(Session::get("adminrole")!="A" && in_array($routename, Session::get("admin_notassigned_route")) ){
        echo ""; die;
    }
}

function if_allowedRoute($routename) {
    if(Session::get("adminrole")!="A" && !in_array($routename, Session::get("admin_assigned_route")) ){
        echo "You are not Assigned to View This Page, Ask Admin For Permission."; die;
    }
}


function verifyPincode($pincode=''){
    $pincode = intval(trim($pincode));
        if($pincode=='' OR strlen($pincode)!=6){
            return ['success' => false, 'data' => NULL, 'msg' => 'InValid pincode Number'];
        }
        try {
            $url = 'https://api.postalpincode.in/pincode/'.$pincode;
            $cSession = curl_init();
            curl_setopt($cSession, CURLOPT_URL, $url);
            curl_setopt($cSession, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($cSession, CURLOPT_HTTPHEADER, array('Content-Type' => 'application/x-www-form-urlencoded' ) );
            $result = curl_exec($cSession);
            curl_close($cSession);
            $resultArray = json_decode($result, true);

            if(isset($resultArray[0]['Status']) && $resultArray[0]['Status']=='Success') {
                $data = isset($resultArray[0]['PostOffice'][0]) ? $resultArray[0]['PostOffice'][0] : NULL; 
                return ['success' => true, 'data' => $data, 'msg' => 'Valid pincode Number' ];
            }
            else
            {
                return ['success' => false, 'data' => NULL , 'msg' => $resultArray[0]['Message'] ];
            }
        } catch (\Exception $e) {
            return ['success' => false, 'data' => $e->getMessage(), 'msg' => 'Api Not Working' ];
        } 
}


function GeneratetNewToken() {
    error_reporting(0);
    $header  = '{"typ":"JWT","alg":"HS256"}';
    $payload = "Bigdaddy_api";
    $JWT     = new JWT;
    return $JWT->encode($header, $payload, date('Y-m-d H:i:s'));
}

function jwtCustomTokenEncode($data) {
    return TOKEN::generate($data);
}

function jwtCustomTokenDecode($token) {
    return TOKEN::decode($token);
}

function CreateApiLogs($dataApi=[]){
        try {
    if(Config::get('constants.is_enable_api_log')==true){
        $e = new ApiLog;
        $e->data = json_encode($dataApi);
        $e->save();
        $from = date('Y-m-d', strtotime(date('Y-m-d') . ' -15 day'));
        $to = date('Y-m-d', strtotime(date('Y-m-d') . ' +1 day'));
        ApiLog::whereNotBetween('created_at', [$from, $to])->delete();
    }
            } catch(\Exception $e) { }
}

function CreateTempToken($usertype=1,$devicetype=1,$user_id=0){
    $token = jwtCustomTokenEncode(["devicetype" => $devicetype, "currentdatetime" => time(), "usertype" => $usertype, "user_id" => $user_id, "uniqid" => uniqid()]);
    TempToken::create(["usertype" => $usertype,"devicetype" => $devicetype,"user_id" => $user_id,"token" => $token,"created_at" => date('Y-m-d H:i:s')]);
    $from = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . ' -30 minutes'));
    $to = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . ' +1 minutes'));
    TempToken::whereNotBetween('created_at', [$from, $to])->delete();
    return $token;
}

function CheckTempToken($usertype=1,$devicetype=1,$user_id=0,$temptoken){
    $from = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . ' -35 minutes'));
    $to = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . ' +1 minutes'));
    TempToken::whereNotBetween('created_at', [$from, $to])->delete();
    $count = TempToken::where('usertype', $usertype)->where('devicetype', $devicetype)->where('user_id', $user_id)->where('token', $temptoken)->count();
    if($count>0){
        return true;
    }
    return false;
}

function CreateDeletedDataLogs($dataDeleted=[]){
        $e = new DeletedLog;
        $e->data = json_encode($dataDeleted);
        $e->save();
        $from = date('Y-m-d', strtotime(date('Y-m-d') . ' -30 day'));
        $to = date('Y-m-d', strtotime(date('Y-m-d') . ' +1 day'));
        DeletedLog::whereNotBetween('created_at', [$from, $to])->delete();
}

function DeleteOTP(){
    $from = date('Y-m-d', strtotime(date('Y-m-d') . ' -10 day'));
    $to = date('Y-m-d', strtotime(date('Y-m-d') . ' +1 day'));
    OTP::whereNotBetween('created_at', [$from, $to])->delete();
}

function DeleteAccessToken(){
    $fromForLast2Days = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . ' -2 day'));
    $fromForLast7Days = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . ' -7 day'));
    $fromForLast30Days = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . ' -30 day'));
    $fromForLast100Days = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . ' -100 day'));
    $toNext1Days = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . ' +1 day'));
    AccessToken::where('usertype', Config::get('constants.usertype.customer'))->where('devicetype', Config::get('constants.devicetype.web'))->whereNotBetween('updated_at', [$fromForLast2Days, $toNext1Days])->delete();
    AccessToken::where('usertype', Config::get('constants.usertype.driver'))->whereNotBetween('created_at', [$fromForLast30Days, $toNext1Days])->whereNotBetween('updated_at', [$fromForLast7Days, $toNext1Days])->delete();
    AccessToken::whereNotBetween('updated_at', [$fromForLast30Days, $toNext1Days])->delete();
}

function valid_mobile($mobile='') {
    $mobileregex = "/^[6-9][0-9]{9}$/";  
    if(strlen($mobile)==10 && preg_match($mobileregex, $mobile) === 1)
    {
        return true;
    }
    return false;
}

function valid_id($id='') {
    if(is_numeric($id) && $id>0)
    {
        return true;
    }
    return false;
}

function valid_gstNo($gstNo='') {
    if(strlen(trim($gstNo))==15)
    {
        return true;
    }
    return false;
}

function valid_panNo($panNo='') {
    if(strlen(trim($panNo))==10)
    {
        return true;
    }
    return false;
}

function valid_password($password='') {
    if(strlen(trim($password))>=6 && strlen(trim($password))<25)
    {
        return true;
    }
    return false;
}

function valid_email($email='') {
    if($email!='' && filter_var($email, FILTER_VALIDATE_EMAIL)){
        return true;
    }
    return false;
}

function createOTP($number=6) {
    return 1111;
    return mt_rand(str_repeat(1,$number),str_repeat(9,$number));
}


function sendMsg($text='', $mobile=''){
    return true;

   /* $apikey = '5898b41e-7ce4-11eb-a9bc-0200cd936042';
        $mobile = intval(trim($mobile));

        if($text=='' OR strlen($mobile)<10){
            return ['success' => false, 'data' => NULL, 'msg' => 'InValid'];
        }
        try {

            $dataPOST = [
                'From' => 'BIGDDY',
                'To' => $mobile,
                'Msg' => $text,
            ];

            $url = 'http://2factor.in/API/V1/'.$apikey.'/ADDON_SERVICES/SEND/TSMS';
            $cSession = curl_init();

            curl_setopt($cSession, CURLOPT_URL, $url);
            curl_setopt($cSession, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($cSession, CURLOPT_POST, true);
            curl_setopt($cSession, CURLOPT_HTTPHEADER, array('Content-Type' => 'application/x-www-form-urlencoded' ) );
            curl_setopt($cSession, CURLOPT_POSTFIELDS, $dataPOST);
            $result = curl_exec($cSession);
            curl_close($cSession);
            $resultArray = json_decode($result, true);

            CreateApiLogs($dataPOST);

            if(isset($resultArray['Status']) && $resultArray['Status']=='Success') {
                return ['success' => true, 'data' => $resultArray['Details'], 'msg' => 'SMS Sent.' ];
            }
            else
            {   
                return ['success' => false, 'data' => $result , 'msg' => 'SMS Not Sent.' ];
            }
        }
        catch (\Exception $e) {
            return ['success' => false, 'data' => $e->getMessage(), 'msg' => 'SMS Api Not Working' ];
        } 
    /*-----sms api call here------*/
}


function getIPAddress() {  
    if(!empty($_SERVER['HTTP_CLIENT_IP'])) {  
        $ip = $_SERVER['HTTP_CLIENT_IP'];  
        }  
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {  
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];  
     }  
    else{  
        $ip = $_SERVER['REMOTE_ADDR'];  
     }  
     return $ip;  
}  
function get_file_extension($file_name) {
    return substr(strrchr($file_name,'.'),1);
}

function file_download($dir="", $fileName="", $urlFile="") {
    if($urlFile==""){
        $urlFile = public_path('/storage') . '/' . $dir . '/' . $fileName;
    }
    else
    {
        $fileName = basename($urlFile); 
    }
    file_put_contents($fileName,file_get_contents($urlFile));
    return true;
}


function secToHR($seconds=0) {
      $hoursText = ''; $minutesText = ''; $secondsText = '';
      $hours = floor($seconds / 3600);
      $minutes = floor(($seconds / 60) % 60);
      $seconds = $seconds % 60;

  if($hours>=1){
        $hoursText = $hours." Hour(s)";
  }
  else if($minutes>=1){
        $minutesText = $minutes." Minute(s)";
  }
  else if($seconds>=1){
        $secondsText = $seconds." Seconds";
  }
  return $hoursText.$minutesText.$secondsText;
  
}



function secondsToTimings($seconds=0) {
  $t = round($seconds);
  return sprintf('%02d:%02d:%02d', ($t/3600),($t/60%60), $t%60);
}
