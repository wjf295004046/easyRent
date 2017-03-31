<?php

namespace App\Http\Controllers\Auth;

use App\Models\SMS;
use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use Validator;


class QuickLoginController extends Controller
{
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
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'phone' => 'required|between:11,11',
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
            'name' => "ey_" . $data['phone'],
            'phone' => $data['phone'],
            'password' => bcrypt($data['verify'] . rand(100, 9999)),
        ]);
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function quickLogin(Request $request)
    {
        $phone = $request->input('phone');
        $verify = $request->input('verify');
        $data = SMS::select("verify")
            ->where("mobile", $phone)
            ->where("status", 1)
            ->where("type", 1)
            ->where("created_at", ">=", time()-1800)
            ->orderBy("created_at", "desc")
            ->first();

        if (!$data || $data->verify != $verify) {
            echo json_encode(array('code' => 1, 'msg' => '验证码错误'));
            return ;
        }
        else {
            SMS::select("verify")
                ->where("mobile", $phone)
                ->where("status", 1)
                ->where("type", 1)
                ->where("verify", $data->verify)
                ->update(['status' => 2]);
        }
        $user = User::where("phone", $phone)->first();
        if ($user) {
            $this->guard()->login($user);
            echo json_encode(array('code' => 0, 'redirect' => url($this->redirectTo)));
            return;
        }
        else {
            $validator = $this->validator($request->all());
            if ($validator->passes()) {
                event(new Registered($user = $this->create($request->all())));
                $user_detail = new UserDetail();
                $user_detail->user_id = $user->id;
                $user_detail->save();
                echo json_encode(array('code' => 0, 'redirect' => url($this->redirectTo)));
                $this->guard()->login($user);
                return;
            }
            else {
                $errors = $validator->messages();
                echo json_encode(array('code' => 2, 'msg' => $errors->first("phone")));
                return;
            }

        }

    }
}
