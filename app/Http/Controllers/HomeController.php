<?php

namespace App\Http\Controllers;

use App\Models\Section;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{

	public function __construct()
	{
		$this->middleware('auth');

	}

	 public function index(Request $request)
	{
	    $sections = Section::all();

        return view('home.index', ['sections'=>$sections]);
	}

	/**
	 * 搜索
	 */
	public function search()
	{

	}


}