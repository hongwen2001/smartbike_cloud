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
use function Symfony\Component\String\u;

/*    隨著IP不同這裡需要更改
 *
 * */

class AuthorizeController extends ClientController
{
    //
    
    public function redirect(Request $request)
    {
        $user = Auth::user();
        global $response;
        if ($user->secret == null) {
            $request = $request->replace(['name' => Auth::user()->account, 'redirect' => 'http://172.18.26.70:8080/authorize2/callback']);
            $sec = $this->store($request);
            DB::table('users')->where('id', '=', $user->id)->update(['secret' => $sec->secret, 'client_id' => $sec->id]);
            $response = http_build_query(['client_id' => $sec->id, 'redirect_uri' => 'http://172.18.26.70:8080/authorize2/callback', 'response_type' => 'code', 'scope' => '']);
        } else {
            $response = http_build_query(['client_id' => $user->client_id, 'redirect_uri' => 'http://172.18.26.70:8080/authorize2/callback', 'response_type' => 'code', 'scope' => '']);
        }
        return redirect('http://172.18.26.70:8080/oauth/authorize?' . $response);

    }

    public function again_Authorize(Request $request)
    {
        $user = User::find(Auth::user()->id);
        $user->update(['secret' => null, 'client_id' => null]);
        return redirect()->action('App\Http\Controllers\AuthorizeController@redirect', $request);
    }



    public function callback(Request $request)
    {
        $user = Auth::user();
        if ($request->has('code')) {
            return redirect()->to('/dashboard?code=' . $request->code . '&client_id=' . $user->client_id.'&secret='.$user->secret.'&id='.$user->id);
        }
        if ($request->has('error')) {

            $client_id = $user->client_id;
            $this->destroy($request, str($client_id));
            $user->update(['secret' => '', 'client_id' => '']);
            //            return $this->redirect()->to("/dashboard");
            return response()->json(['status' => 1, 'message' => "error"]);
        }
    }
}
