<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\DB;

class UserDetailsController extends Controller
{
    public function saveDetails(Request $request){
        $validator = Validator::make($request->all(), [
            $request->get('number') => 'required',
            $request->get('state') => 'required',
            $request->get('district') => 'required',
        ]);
        $number = $request->get('number');
        $state = $request->get('state');
        $district = $request->get('district');
        DB::table('userdetails')->insert([
            'number' => $number,
            'state' => $state,
            'district' => $district,
        ]);
        
        return "success";
    }
}
