<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StateController extends Controller
{
    public function getStates()
    {
        $path = "../json/states.json";

        $json = json_decode(file_get_contents($path), true);

        return $json;
    }
}
