<?php

namespace App\Http\Controllers;

class RedirectController extends Controller
{
    public function check()
    {
        if (auth()->user()->role == 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif (auth()->user()->role == 'ksh') {
            return redirect()->route('ksh.index');
        }
    }
}
