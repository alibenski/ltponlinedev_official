<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

use DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Torgan;


class LateRegisterController extends Controller
{
    protected function generateRandomURL()
    {
        $recordId = DB::table('url_generator')->insertGetId(
            ['user_id' => Auth::id()]
        );

        return URL::temporarySignedRoute('late-register', now()->addDays(1), ['transaction' => $recordId]);
    }

    public function lateRegister(Request $request)
    {
        if (!$request->hasValidSignature()) {
            abort(401);
        }
        $org = Torgan::get(["Org Full Name", "Org name"]);
        return view('users_new.new_outside_user_form', compact('org'));
    }
}
