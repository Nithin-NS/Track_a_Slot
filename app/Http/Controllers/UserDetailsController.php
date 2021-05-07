<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeMail;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UserDetailsController extends Controller
{
    public function saveDetails(Request $request){
        $this->validate($request,[
            'number' => 'required|unique:userdetails,number|numeric|digits:10',
            'email' => 'required|unique:userdetails,email|email',
            'state' => 'required',
            'district' => 'required',
            'age_group' => 'required'
        ]);
        $number = $request->get('number');
        $state = $request->get('state');
        $district = $request->get('district');
        $email = $request->get('email');
        $age_group = $request->get('age_group');

        DB::table('userdetails')->insert([
            'number' => $number,
            'email' => $email,
            'state' => $state,
            'district' => $district,
            'age_group' => $age_group,
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon::now()->toDateTimeString()   
        ]);
        
        $api_districts = http::get('https://cdn-api.co-vin.in/api/v2/admin/location/districts/'.$state)->json();
        $data = $api_districts['districts'];
        foreach($data as $dis){
            if($dis['district_id'] === $district){
                $selected_district = $dis['district_name'];
            }
        }

        //Send SMS
        //Your authentication key
        $authKey = "296861AdNa7wurCB60911821P1";
        // $authKey = "6048bf459d8e2e5bdf244bca";

        //Multiple mobiles numbers separated by comma
        // $mobileNumber = $number;

        //Sender ID,While using route4 sender id should be 6 characters long.
        $senderId = "POWRUP";

        //Your message to send, Add URL encoding here.
        $message = urlencode("Hi, Your trackmyshot registration is successful. You will now receive alerts on vaccine slot vaccancies near you.");
        // $message = urlencode("Hi, Your ChargeMOD verification code is 1990");

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

        // return $response;

        //End od SMS

        //sending mail
        Mail::to($email)->send(new WelcomeMail());
        
        return $selected_district;
    }
}
