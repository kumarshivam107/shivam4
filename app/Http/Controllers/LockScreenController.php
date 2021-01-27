<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;

class LockScreenController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function lock()
    {
        Session::put('locked', true);

        return redirect()->route('lock-screen-view');
    }

    public function viewLockScreen()
    {
        if(Session::has('locked') && Session::get('locked')){
            return view('lock_screen');
        }else{
            return redirect()->route('statistics');
        }
    }

    public function unlock()
    {
        if(Session::has('locked') && !empty(Session::get('locked'))){
            $password = Input::get('password');

            if(Hash::check($password, Auth::user()->password)){
                Session::forget('locked');
                return redirect()->route('statistics');
            }else{
                return redirect()->back()->with('error', __('Password Incorrect!'));
            }
        }else{
            return redirect()->route('statistics');
        }
    }
}
