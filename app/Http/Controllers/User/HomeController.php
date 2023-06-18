<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Repositories\Interface\KshInterface;
use App\Repositories\Interface\LarvaeInterface;
use App\Repositories\Interface\SampleInterface;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    private $sample;
    private $larvae;
    private $ksh;

    public function __construct(SampleInterface $sample, LarvaeInterface $larvae, KshInterface $ksh) {
        $this->sample = $sample;
        $this->larvae = $larvae;
        $this->ksh = $ksh;
    }

    public function index()
    {
        return view('user.index');
    }

    public function vector()
    {
        dd($this->sample->getAllRegency());
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
