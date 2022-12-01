<?php

namespace App\Http\Controllers;

use App\Models\User;
use Dotenv\Exception\ValidationException;
use App\Actions\Fortify\PasswordValidationRules;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Laravel\Jetstream\Jetstream;
use App\Http\Controllers\Oauth20LoginController;
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

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function FB_Google_GetToken(Request $request){
        $login = array(
            'account' => $request->account,
            'password' => $request->password
        );
        if (Auth::attempt($login)) {
            $token = Auth::user()->createToken('email')->accessToken;
            return response()->json(['status' => 0, 'message' => 'success','token'=>$token,'id'=>Auth::user()->id]);
        } else {
            return '失敗'.$login;
        }
    }

    public function check_account(Request $request){
        $vaildator=Validator::make($request->toArray(),['account'=>['required']]);
        if ($vaildator->passes()){
            $data=DB::table('users')->where('account','=',$request->account)->first();
            return $data==null?response()->json(['status' => 0, 'message' => "account does not exist"]):response()->json(['status' => 0, 'message' => "account already exists"]);
        }else{
            return response()->json(['status' => 1, 'message' => "Missing or incorrect data"]);
        }
    }
    public function usercreate(Request $request)
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
            $user = DB::table('users')->where('account', 'like', $input['account'])->get()->toArray();
            if ($user == null) {
                $request->replace(['name' => $input['name'], 'scopes' => '']);
                //                $createtoken=new PersonalAccessTokenController($request);
                //                $createtoken->store($request);
                $CreateUser = User::create([
                    'Login_method' => $input['login_method'],
                    'name' => $input['name'],
                    'email' => $input['email'],
                    'account' => $input['account'],
                    'password' => Hash::make($input['password']),
                ]);
                $this->create_usertable($CreateUser->id);

                return response()->json(['status' => 0, 'message' => "create success","data"=>$CreateUser]);
            }
            return response()->json(['status' => 0, 'message' => "existed","data"=>$user]);

        } else {
            return response()->json(['status' => 1, 'message' => $validator->messages()]);
        }


    }
    public function create_usertable($user_id)
    {
        Schema::create('user_HeartRateBloodOxygen' . $user_id, function (Blueprint $table) {
            $table->id();
            $table->dateTime('DataTime');
            $table->string('Calories');
            $table->string('HeartRate');
            $table->string('BloodOxygen');

        });
        Schema::create('user_Maphistore' . $user_id, function (Blueprint $table) {
            $table->id();
            $table->dateTime('DataTime');
            $table->string('Location');
            $table->string('BikeLocation');

        });
        Schema::create('user_SmartBike_Personal' . $user_id, function (Blueprint $table) {
            $table->id();
            $table->string('height')->nullable();
            $table->string('weight')->nullable();
            $table->string('birthday')->nullable();
            $table->string('gender')->nullable();
            $table->string('nowLocationLat')->nullable();
            $table->string('nowLocationLng')->nullable();
        });
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
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



    //    public function refresh_acc
    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
}
