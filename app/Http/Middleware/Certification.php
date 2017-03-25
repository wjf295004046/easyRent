<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Models\UserDetail;
use Closure;
use Illuminate\Support\Facades\Auth;

class Certification
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = UserDetail::where("user_id", Auth::user()->id)->first();
        if (!$user || $user->real_name == '' || $user->id_card == '' || $user->pic_path == '/common/mrtx.jpg')
            return redirect()->action('CommonController@cert');
        else
            User::where("id", Auth::user()->id)->update(['is_landlord' => 1]);
        return $next($request);
    }
}
