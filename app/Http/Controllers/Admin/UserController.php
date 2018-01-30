<?php

namespace App\Http\Controllers\Admin;

use Auth;
use Session;
use App\Models\Role;
use App\Models\User;
use App\Models\RegisterFormField;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    const REDIRECT_SESSION = "USER_REDIRECT_URL";

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function index(Request $request)
    {
        // User for redirection after update user
        $this->saveFormRedirect($request->fullUrl());

        // Check has query
        $resultPerPage = 15;
        if ($request->has('query')) {
            $users = User::userOnly()->search($request->get('query'))->distinct();
        }
        else {
            $users = User::userOnly();
        }

        $userPaginate = $users->paginate($resultPerPage);

        return view('admin.user.registration', [
            'totalUser' => User::userOnly()->get()->count(),
            'users' => $userPaginate
        ]);
    }

    public function viewProfile($id, Request $request)
    {
        // User for redirection after update user
        $this->saveFormRedirect($request->fullUrl());

        $user = User::findOrFail($id);
        $fields = RegisterFormField::orderBy('order', 'asc')->get();

        return view('admin.user.user_profile', ['user' => $user, 'fields' => $fields]);
    }

    /**
    * Helpers
    */
    public function saveFormRedirect($url)
    {
        Session::put(UserController::REDIRECT_SESSION, $url);
    }
}
