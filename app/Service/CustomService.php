<?php

namespace App\Service;
use App\Models\Order;
use Illuminate\Support\Facades\Log;
date_default_timezone_set('Asia/Kolkata');
use Illuminate\Support\Facades\Validator;



class CustomService
{

    public function getGoogleMapDistanceMatrixApi($latlngArray,$anotherVariable='')
        {
        try {

        $ch = curl_init();
        //$url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=".$latlngArray['origins_latitude'].",".$latlngArray['origins_longitude']."&destinations=side_of_road:".$latlngArray['destinations_latitude'].",".$latlngArray['destinations_longitude']."&key=".constants('googleMapApiKey');

        //$url = "https://api.distancematrix.ai/maps/api/distancematrix/json?origins=".$latlngArray['origins_latitude'].",".$latlngArray['origins_longitude']."&destinations=".$latlngArray['destinations_latitude'].",".$latlngArray['destinations_longitude']."&key=bWZvfj6KPlDZ28Fv1cks545z6Jg94";

        //$url = env('CLIENTBASE_URL')."admin_assets/maps.json";

        /*curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt( $ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        $result = curl_exec($ch);
        curl_close($ch);*/

        $result = '{
  "destination_addresses": [
    "Simada Naher Junction BRTS, Shiv Darshan Society, Yoginagar Society, Surat, Gujarat 395006, India"
  ],
  "origin_addresses": [
    "Navagam Dindoli, Dindoli, Surat, Gujarat 394210, India"
  ],
  "rows": [
    {
      "elements": [
        {
          "distance": {
            "text": "8.8 km",
            "value": '.mt_rand(1000,15000).'
          },
          "duration": {
            "text": "18 min",
            "value": '.mt_rand(200,800).'
          },
          "status": "OK"
        }
      ]
    }
  ],
  "status": "OK"
}';


        $resultArray = json_decode($result, true);

        


        if(isset($resultArray['error_message'])) {
            return ['success' => 0, 'data' => $resultArray, 'msg' => $resultArray['error_message'] ];
        }
        else
        {
            return ['success' => 1, 'data' => $resultArray , 'msg' => '' ];
        }

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return ['success' => 0, 'data' => [], 'msg' => $e->getMessage() ];
        }

    }

    






}
