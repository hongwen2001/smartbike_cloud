<?php

namespace App\Http\Controllers;

use App\Models\User;
use http\Cookie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Laravel\Passport\Http\Controllers\ClientController;
use function Livewire\str;

class AuthorizeController extends ClientController
{
    //
    public function redirect(Request $request){
        return $request;
        global  $response;
        $user=Auth::user();
        if ($user->client_id==null && $user->secret==null) {
            $request = $request->replace(['name' => Auth::user()->account, 'redirect' => 'http://172.18.8.158:8080/authorize2/callback']);
            $sec = $this->store($request);
            DB::table('users')->where('id','=',$user->id)->update(['secret'=>$sec->secret,'client_id'=>$sec->id]);
            $response=http_build_query(['client_id'=>$sec->id,'redirect_uri'=>'http://172.18.8.158:8080/authorize2/callback','response_type'=>'code','scope'=>'','clients'=>$sec]);
            return redirect('http://127.0.0.1:8080/oauth/authorize?'.$response);
        }

//        $response=http_build_query(['client_id'=>'1','redirect_uri'=>'http://127.0.0.1:8080/authorize2/callback','response_type'=>'code','scope'=>'']);

        return redirect()->route('dashboard');
//        return $sec;
    }
    public function again_Authorize(Request $request){
        $user=User::find(Auth::user()->id);
        $user->update(['secret'=>null,'client_id'=>null]);
        return redirect()->action('App\Http\Controllers\AuthorizeController@redirect',$request);
    }
    public function App_login(Request $request){
        return redirect()->to('login')->with(['action'=>'App_login']);
    }
    public function login_judge(Request $request){
        if ($request->input('action')=='App_login'){

        }else if ($request->input('action')==null){

        }
    }
    public function callback(Request $request){
        $user=Auth::user();
        if ($request->has('code')){
            $user_data=DB::table('users')->where('id','=',$user->id)->first();
            return  redirect()->to('?code='.$request->code);
        }
        if ($request->has('error')){
            $user_data=DB::table('users')->where('id','=',$user->id)->first();
            $this->destroy($request,str($user_data->client_id));
            $user_data->update(['secret'=>null,'client_id'=>null]);
//            return $this->redirect()->to("/dashboard");
            return "倒置";
        }

    }
}
