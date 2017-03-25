<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\SMS;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
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
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $phone = $request->input('phone');
        $verify = $request->input('verify');
        $user = User::where("phone", $phone)->first();
        $data = SMS::select("verify")
            ->where("mobile", $phone)
            ->where("status", 1)
            ->where("type", 3)
            ->where("created_at", ">=", time()-1800)
            ->orderBy("created_at", "desc")
            ->first();

        if (!$data || $data->verify != $verify) {
            echo json_encode(array('code' => 1, 'msg' => '验证码错误'));
            return ;
        }
        else {
            $validator = $this->validator($request->all())->validate();
            event(new Registered($user = $this->create($request->all())));
            $this->guard()->login($user);
            echo json_encode(array('code' => 0, 'redirect' => $this->redirectTo));
            return ;
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
            'name' => 'required|string|between:2,20',
            'phone' => 'required|between:11,11|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'phone' => $data['phone'],
            'password' => bcrypt($data['password']),
        ]);
    }
}
