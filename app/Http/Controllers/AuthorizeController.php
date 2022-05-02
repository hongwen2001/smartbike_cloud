<?php

namespace App\Http\Controllers;

use http\Cookie;
use Illuminate\Http\Request;

class AuthorizeController extends Controller
{
    //
    public function redirect(Request $request){
        $response=http_build_query(['client_id'=>'8','redirect_uri'=>'http://172.18.8.158:8082/authorize2/callback','response_type'=>'code']);
        return redirect('http://172.18.8.158:8082/oauth/authorize?'.$response);
    }
    public function callback(Request $request){

        return '成功';
    }
}
