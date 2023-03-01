<?php

namespace App\Http\Controllers;

use App\Models\Visitors;
use Illuminate\Http\Request;
use App\Helpers\Helper;

class WelcomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->saveVisitor();
        
        return view('welcome');
    }

    public function saveVisitor(){
        $data = Helper::visitor();
        $store = Visitors::create([
            'userip' => $data['ip'],
            'referrer' => $data['referrer'],
            'useragent' => $data['useragent'],
            'datetime' => $data['timestamp'],
            'city' => $data['city'],
            'country' => $data['country'],
            'countryCode' => $data['countryCode'],
            'continent' => $data['continent'],
            'continentCode' => $data['continentCode'],
            'timezone' => $data['timezone'],
            'latitude' => $data['latitude'],
            'longitude' => $data['longitude'],
        ]);
        if($store){
            return true;
        }else{
            return false;
        }
    }
}
