<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Repositories\Interface\SampleInterface;
use Illuminate\Http\Request;

class VectorController extends Controller
{
    private $sample;

    public function __construct(SampleInterface $sample) {
        $this->sample = $sample;
    }

    public function index()
    {
        return view('user.vector', [
            'samples' => $this->sample->getAllForUser(),
            'samplePerYear' => $this->sample->getSamplePerYear(date('Y')),
            'samplePerDistrict' => $this->sample->getHighestSampleInDistrictPerYear(date('Y')),
        ]);
    }

    public function filterYear(Request $request)
    {
        return response()->json($this->sample->getSamplePerYear($request->year));
    }
}
