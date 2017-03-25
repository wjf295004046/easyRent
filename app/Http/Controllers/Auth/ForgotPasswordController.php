<?php

namespace App\Http\Controllers\Auth;

use App\Models\SMS;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\RegistersUsers;
use Validator;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use RegistersUsers;

    protected $redirectTo = '/home';
    /**
     * Display the form to request a password reset link.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLinkRequestForm()
    {
        return view('auth.passwords.mobile');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'phone' => 'required|between:11,11',
            'password' => 'required|confirmed|min:6'
        ]);
    }

    public function reset(Request $request) {
        $phone = $request->input('phone');
        $verify = $request->input('verify');
        $user = User::where("phone", $phone)->first();
        if ($user) {
            $data = SMS::select("verify")
                ->where("mobile", $phone)
                ->where("status", 1)
                ->where("type", 2)
                ->where("created_at", ">=", time()-1800)
                ->orderBy("created_at", "desc")
                ->first();

            if (!$data || $data->verify != $verify) {
                echo json_encode(array('code' => 1, 'msg' => '验证码错误'));
                return ;
            }
            else {
                $validator = $this->validator($request->only("phone", "password", "password_confirmation"));
                $validator->validate();
                if ($validator->passes()) {
                    User::where("phone", $phone)->update(['password' => bcrypt($request->input('password'))] );
                    SMS::select("verify")
                        ->where("mobile", $phone)
                        ->where("status", 1)
                        ->where("type", 2)
                        ->where("verify", $data->verify)
                        ->update(['status' => 2]);
                    $this->guard()->login($user);
                    echo json_encode(array('code' => 0, 'redirect' => url($this->redirectTo)));
                    return;
                }
            }
        }
        else {
            echo json_encode(array('code' => 2, 'msg' => '手机号不存在'));
            return ;
        }
    }
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }
}
