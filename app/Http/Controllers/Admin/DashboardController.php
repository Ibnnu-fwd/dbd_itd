<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Regency;
use App\Models\Sample;
use App\Models\User;
use App\Repositories\Interface\AbjInterface;
use App\Repositories\Interface\LarvaeInterface;
use App\Repositories\Interface\RegencyInterface;
use App\Repositories\Interface\SampleInterface;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    private $sample;

    private $larva;

    private $larvae;

    private $abj;

    private $regency;

    public function __construct(
        SampleInterface $sample,
        LarvaeInterface $larva,
        AbjInterface $abj,
        LarvaeInterface $larvae,
        RegencyInterface $regency
    ) {
        $this->sample = $sample;
        $this->larva = $larva;
        $this->abj = $abj;
        $this->larvae = $larvae;
        $this->regency = $regency;
    }

    public function __invoke(Request $request)
    {
        // return $this->sample->getSampleAndAbjGroupByDistrict($request->regency_id ?? 3501);
        return view('admin.dashboard.index', [
            'samplePerYear' => $this->sample->getSamplePerYear(date('Y')),
            'usersCount' => User::all()->count(),
            'totalSample' => $this->sample->getTotalSample(),
            'totalMosquito' => $this->sample->getTotalMosquito(),
            'totalLarva' => $this->larva->getTotalLarva(),
            'abj' => $this->abj->getAllGroupByDistrict(),
            'larvae' => $this->larvae->getAll(),
            'sample' => $this->sample->getAll(),
            'sampleAndAbj' => $this->sample->getSampleAndAbjGroupByDistrict($request->regency_id ?? 3578),
            'regencies' => $this->regency->getAll(),
        ]);
    }

    public function getSampleAndAbjByDistrict(Request $request)
    {
        return response()->json($this->sample->getSampleAndAbjGroupByDistrict($request->regency_id));
    }
}
