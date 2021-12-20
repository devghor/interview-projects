<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Services\User\UserService;
use App\Values\StatusValue;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

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
    protected $redirectTo = RouteServiceProvider::HOME;

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
     * Handle a registration request for the application.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {

        try {
            $response = [];
            $validator = Validator::make($request->all(), [
                'first_name' => 'required',
                'last_name' => 'required',
                'role' => 'required',
                'email' => "unique:users,email",
                'password' => 'required',
            ]);

            if ($validator->fails()) {
                $response['errors'] = $validator->errors()->all();
                throw new \Exception("Validation errors", StatusValue::HTTP_UNPROCESSABLE_ENTITY);
            }

            $findUser = User::where('email', $request->email)->first();
            if ($findUser) {
                $response['errors'] = ['This email already has existed'];
                throw new \Exception("", StatusValue::HTTP_UNPROCESSABLE_ENTITY);
            }


            $userService = new UserService();

            $user = new User;
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->email = $request->email;
            $user->user_name = $userService->generateUserName($user->first_name, $user->last_name);
            $user->password = $userService->generatePassword($request->password);
            $user->save();

            if ($user->save()) {
                $response['message'] = 'Successfully registered.';
            }

            return response($response, StatusValue::HTTP_OK);
        } catch (\Exception $e) {
            $response['message'] = $e->getMessage();
            return response($response, $e->getCode());
        }
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
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
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }
}
