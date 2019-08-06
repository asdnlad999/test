<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function loginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'password' => 'required'
        ], [
            'name.required' => '用户名必填',
            'password.required' => '密码必填'
        ]);
        $credentials = $request->only(['name','password']);
        $remember = $request->get('remember',0);
        $login_ret = Auth::attempt($credentials,$remember);
        if (!$login_ret){
            return redirect()->back()->withErrors(['用户名或密码错误']);
        }
        return redirect(route('dashboard'));
    }

    public function logout(){
        Auth::logout();
        return redirect(route('login'));
    }

}
