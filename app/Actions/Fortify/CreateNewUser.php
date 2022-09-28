<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
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
        try {
            Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();

            return User::create([
                'Login_method'=>'smartbike',
                'name' => $input['name'],
                'email' => $input['email'],
                'account'=>$input['email'],
                'password' => Hash::make($input['password']),
            ]);
        }catch (Exception $exception){
            return response()->json(['statue'=>'201','message'=>'data fail']);
        }
    }
}
