<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return view('user.index');
    }

    public function vector()
    {
        return view('user.vector');
    }

    public function larvae()
    {
        return view('user.larvae');
    }

    public function ksh()
    {
        return view('user.ksh');
    }
}
