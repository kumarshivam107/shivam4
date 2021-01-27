<?php

namespace App\Http\Controllers\Settings;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('settings.profile');
    }

    public function updateProfile(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'old_password' => 'nullable|string|min:6',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $user = User::find(Auth::user()->id);

        $user->name = $request->get('name');
        $user->email = $request->get('email');
        if($request->has('password') && !empty($request->get('password'))){
            //Verify old password
            if(Hash::check($request->get('old_password'), $user->password)){
                $user->password = bcrypt($request->get('password'));
            }else{
                return redirect()->back()->with('error', __('The old password was Incorrect!'));
            }
        }
        $user->save();

        return redirect()->back()->with('success', __('Profile edited successfully!'));
    }
}
