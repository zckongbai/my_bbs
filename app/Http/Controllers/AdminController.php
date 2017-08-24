<?php namespace App\Http\Controllers;

class AdminController extends Controller {



    public function index()
    {
        return __CLASS__;
    }

    public function login()
    {
        session()->put('user_id', 3);
        return redirect('admin');
    }
}
