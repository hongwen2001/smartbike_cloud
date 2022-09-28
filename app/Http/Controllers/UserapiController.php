<?php

namespace App\Http\Controllers;

use App\Models\User;
use Dotenv\Exception\ValidationException;
use App\Actions\Fortify\PasswordValidationRules;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Jetstream\Jetstream;
use App\Http\Controllers\Oauth20CreatePersonTokenController;
use Laravel\Passport\Http\Controllers\ClientController;
use Laravel\Passport\Http\Controllers\PersonalAccessTokenController;
use mysql_xdevapi\Exception;
use function MongoDB\Driver\Monitoring\removeSubscriber;

class UserapiController extends ClientController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $login = array(
            'account',
            'email' => $request->email,
            'password' => $request->password
        );
        if (Auth::attempt($login)) {
            $token = Auth::user()->createToken('email')->accessToken;
            return $token;
        } else {
            return '失敗';
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function fastcreate(Request $request)
    {
        $input = $request->toArray();
        //
//        $request->validate([
//            'account'=>['required'],
//            'email'=>['required','email'],
//            'name'=>['required','string'],
//            'password'=>['required','string','max:10']
//            ]);
//
//        $user_data=User::firstOrCreate([
//            'account'=>$request->userid],
//            ['email'=>$request->email,
//            'name'=>$request->name,
//            'password'=>Hash::make($request->password)]);
//        if (!$user_data->wasRecentlyCreated){
//            return response()->json(['status'=>'0','message'=>'帳號已存在']);
//        }
//        return response()->json(['status'=>'1','message'=>'創建成功']);
        $validator = Validator::make($input, [
            'login_method' => ['required'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'account' => ['required'],
            'password' => ['required'],
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ]);
        if ($validator->passes()) {
            $user = DB::table('users')->where('account', 'like', $input['userid'])->first();
            if ($user == null) {
                $request->replace(['name' => $input['account'], 'scopes' => '']);
//                $createtoken=new PersonalAccessTokenController($request);
//                $createtoken->store($request);
                return User::create([
                    'login_method' => $input['login_method'],
                    'name' => $input['name'],
                    'email' => $input['email'],
                    'account' => $input['userid'],
                    'password' => Hash::make($input['password']),
                ]);
            }
            return response()->json(['statis' => 0, 'message' => "existed"]);
        } else {
            return response()->json(['status' => 1, 'message' => $validator->messages()]);
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        try {
            $this->validate($request, [
                $request->userId => "required|string"
            ]);
            $user = DB::table('user')->where('userId', '=', $request->userId);
            $data = $request->del('userId');
            $user->update($data);
            return response()->json(['status' => 1, 'message' => $user]);
        } catch (ValidationException $exception) {
            return response()->json(['status' => 0, 'message' => '錯誤']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    public function fast_GetToken(Request $request)
    {
        $input = $request->toArray();
        $validator = Validator::make($input, [
            'login_method' => ['required'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'account' => ['required'],
            'password' => ['required'],
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ]);
        if ($validator->passes()) {
            try {
                $user = DB::table('users')->where('account', 'like', 'a0968325695@gmail.com');

                $user_modul = User::find($user->first()->id);
                $token = $user_modul->createToken('account')->accessToken;
                return response()->json(['status' => 0, 'message' => 'succeeded', 'accessTokent' => $token]);

            } catch (Exception $exception) {
                return response()->json(['status' => 1, 'message' => 'The data is error']);
            }
        } else {
            return response()->json(['status' => 1, 'message' => $validator->getMessageBag()]);
        }
    }

//    public function refresh_acc
    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */

}
