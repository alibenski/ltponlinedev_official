<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\PasswordExpiredRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class FirstTimeLoginController extends Controller
{
    public function expired()
    {
        return view('auth.passwords.expired');
    }

    public function postExpired(PasswordExpiredRequest $request)
    {
        // Checking current password
        if (!Hash::check($request->current_password, $request->user()->password)) {
            return redirect()->back()->withErrors(['current_password' => 'Current password is not correct.']);
        }

        $request->user()->update([
            'password' => bcrypt($request->password),
            // 'password_changed_at' => Carbon::now()->toDateTimeString()
            'must_change_password' => 0,
        ]);
        return redirect()->back()->with(['status' => 'Password changed successfully']);
    }
}
