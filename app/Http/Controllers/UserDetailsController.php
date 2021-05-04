<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UserDetailsController extends Controller
{
    public function saveDetails(Request $request){
        $this->validate($request,[
            'number' => 'required|unique:userdetails,number|numeric|digits:10',
            'state' => 'required',
            'district' => 'required',
        ]);
        $number = $request->get('number');
        $state = $request->get('state');
        $district = $request->get('district');
        DB::table('userdetails')->insert([
            'number' => $number,
            'state' => $state,
            'district' => $district,
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon::now()->toDateTimeString()   
        ]);

        //Send SMS
        //Your authentication key
        $authKey = "296861AdNa7wurCB60911821P1";

        //Multiple mobiles numbers separated by comma
        // $mobileNumber = $number;

        //Sender ID,While using route4 sender id should be 6 characters long.
        $senderId = "TRKAST";

        //Your message to send, Add URL encoding here.
        $message = urlencode("Successfully Registerd for Co-WIN Vaccination");

        //Define route 
        // $route = "default";
        //Prepare you post parameters
        $postData = array(
            'authkey' => $authKey,
            'mobiles' => $number,
            'message' => $message,
            'sender' => $senderId,
            // 'route' => $route
        );

        //API URL
        $url="http://api.msg91.com/api/sendhttp.php";

        // init the resource
        $ch = curl_init($url);
        curl_setopt_array($ch, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $postData
            //,CURLOPT_FOLLOWLOCATION => true
        ));


        //Ignore SSL certificate verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);


        //get response
        $response = curl_exec($ch);

        //Print error if any
        if(curl_errno($ch))
        {
            echo 'error:' . curl_error($ch);
        }

        curl_close($ch);

        return $response;

        //End od SMS
        // return "success";
    }
}
