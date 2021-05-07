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
        $code = '91';
        $number = '91'.$request->get('number');
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
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.msg91.com/api/v5/flow/",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "{\"flow_id\":\"6091334eebc7d77fbf241c93\",\"mobiles\":\"$number\",\"VAR1\":\"VALUE1\",\"VAR2\":\"VALUE2\"}",
        CURLOPT_HTTPHEADER => array(
            "authkey: 296861AdNa7wurCB60911821P1",
            "content-type: application/JSON"
        ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } 
        // else {
        //     echo $response;
        // }
        //End od SMS

        //sending mail
        Mail::to($email)->send(new WelcomeMail());
        
        return $selected_district;
    }
}
