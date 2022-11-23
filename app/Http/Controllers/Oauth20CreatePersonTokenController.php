<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Oauth20CreatePersonTokenController extends Controller
{
    //
    public function createClient(Request $request){
        if (DB::table('user')->select(['secret','client_id'])->where('id','=',Auth::id())->first()!=null){
            return route('passport.clients.store',['name',]);
        }
        return response()->json(['status'=>200,'message'=>'existed']);
    }
}
