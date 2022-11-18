<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;
use Laravel\Passport\Http\Controllers\ClientController;
use mysql_xdevapi\Exception;



class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array  $input
     * @return \App\Models\User
     */
    public function create(array $input)
    {

        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();
        $CreateUser = User::create([
            'Login_method' => 'smartbike',
            'name' => $input['name'],
            'email' => $input['email'],
            'account' => $input['email'],
            'password' => Hash::make($input['password']),
        ]);
        $this->create_usertable($CreateUser->id);

        return $CreateUser;

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
}
