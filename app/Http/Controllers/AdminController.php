<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller {



    public function index()
    {
        return __CLASS__;
    }

    public function login(Request $request)
    {
        session()->put('backUrl', url('home'));
        return redirect('home');
    }
}
