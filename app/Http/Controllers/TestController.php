<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Repo;
use Session;

class TestController extends Controller
{
    public function testQuery()
    {
        $term = '209';
        $code = 'FAG1-15-209-1';
        $form = Repo::withTrashed()
            ->where('CodeClass', $code)
            ->where('Term', $term)
            ->join('users', 'ltp_pashqtcur.INDEXID', '=', 'users.indexno')
            ->orderBy('users.nameLast', 'asc')
            ->select('ltp_pashqtcur.*')
            ->get()
            ->take(10);

        dd($form);

        $order = 'asc';
        $users = User::join('roles', 'users.role_id', '=', 'roles.id')->orderBy('roles.label', $order)->select('users.*')->paginate(10);
    }
}
