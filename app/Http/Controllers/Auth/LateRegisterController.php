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

        return URL::temporarySignedRoute('late-new-user-form', now()->addDays(1), ['transaction' => $recordId]);
    }

    public function lateNewUserForm(Request $request)
    {
        if (!$request->hasValidSignature()) {
            abort(401);
        }
        $org = Torgan::get(["Org Full Name", "Org name"]);
        return view('users_new.late_new_user_form', compact('org'));
    }

    public function lateRegister(Request $request)
    {
        dd($request);
    }
}
