<?php

namespace App\Http\Controllers;

use App\Aggregates\Users\UserAggregate;
use Illuminate\Http\Request;

class UserAccountController extends Controller
{
    protected $data = [];

    public function __construct()
    {
        $this->middleware(backpack_middleware());
    }

    /**
     * Show the user a form to change their personal information & password.
     */
    public function index()
    {
        $user_aggy = UserAggregate::retrieve(backpack_user()->id);
        $this->data['title'] = trans('backpack::base.my_account');
        $this->data['user'] = $this->guard()->user();

        $this->data['access_token'] = $user_aggy->getAccessToken();

        return view(backpack_view('my_account'), $this->data);
    }

    /**
     * Get the guard to be used for account manipulation.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return backpack_auth();
    }
}
