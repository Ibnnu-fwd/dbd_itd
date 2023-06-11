<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\Interface\LarvaeInterface;
use App\Repositories\Interface\SampleInterface;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    private $sample;
    private $larva;

    public function __construct(SampleInterface $sample, LarvaeInterface $larva) {
        $this->sample = $sample;
        $this->larva = $larva;
    }

    public function __invoke(Request $request)
    {
        return view('admin.dashboard.index', [
            'samplePerYear' => $this->sample->getSamplePerYear(date('Y')),
            'usersCount' => User::all()->count(),
            'totalSample' => $this->sample->getTotalSample(),
            'totalMosquito' => $this->sample->getTotalMosquito(),
            'totalLarva' => $this->larva->getTotalLarva()
        ]);
    }
}
