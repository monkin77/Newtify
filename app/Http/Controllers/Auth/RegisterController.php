<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Country;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/cards';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:authenticated_user',
            'password' => 'required|string|min:6|confirmed',
            'birthDate' => 'required|string|date_format:d-m-Y|before:'.date('d-m-Y'), // before today
            'country' => 'required|string|exists:country,name',
            'avatar' => 'nullable|file|max:5000', // max 5MB
            // TODO: File upload
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $countryId = Country::getIdByName($data['country']);
        $timestamp = strtotime($data['birthDate']);

        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'birth_date' => gmdate('Y-m-d H:i:s', $timestamp),
            'country_id' => $countryId,
            'avatar' => 'https://picsum.photos/200/200'
        ]);
    }
}
