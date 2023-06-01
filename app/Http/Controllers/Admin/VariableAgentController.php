<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Interface\RegencyInterface;
use App\Repositories\Interface\SampleInterface;
use Illuminate\Http\Request;

class VariableAgentController extends Controller
{
    private $sample;
    private $regency;

    public function __construct(SampleInterface $sample, RegencyInterface $regency)
    {
        $this->sample = $sample;
        $this->regency = $regency;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return datatables()
                ->of($this->sample->getAllRegency())
                ->addColumn('regency', function ($data) {
                    return $data['regency'];
                })
                // ->addColumn('location', function ($data) {
                //     return $data['location'];
                // })
                ->addColumn('count', function ($data) {
                    return $data['count'] ?? 0;
                })
                ->addColumn('type', function ($data) {
                    return view('admin.variable-agent.column.type', compact('data'));
                })
                ->addColumn('action', function ($data) {
                    return view('admin.variable-agent.column.action', compact('data'));
                })
                ->addIndexColumn()
                ->make(true);
        }

        return view('admin.variable-agent.index');
    }

    public function show($id, Request $request)
    {
        // dd($this->sample->getAllGroupByDistrict($id));
        if ($request->ajax()) {
            return datatables()
                ->of($this->sample->getAllGroupByDistrict($id))
                ->addColumn('district', function ($data) {
                    return $data['district'];
                })
                ->addColumn('location', function ($data) {
                    return $data['location'];
                })
                ->addColumn('count', function ($data) {
                    return $data['count'] ?? 0;
                })
                ->addColumn('type', function ($data) {
                    return view('admin.variable-agent.column.type', compact('data'));
                })
                // ->addColumn('action', function ($data) {
                //     return view('admin.variable-agent.column.action', compact('data'));
                // })
                ->addIndexColumn()
                ->make(true);
        }

        return view('admin.variable-agent.show', [
            'regency' => $this->regency->getById($id),
            'id' => $id,
        ]);
    }
}
