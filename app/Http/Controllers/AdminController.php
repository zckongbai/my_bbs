<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller {



    public function index()
    {
        return __CLASS__;
    }

    public function login()
    {
        session()->put('backUrl', url('home'));
        return redirect('home');
    }

    public function doLogin(Request $request)
    {

    }

    public static function checkUserIsLogin()
    {

    }

}
