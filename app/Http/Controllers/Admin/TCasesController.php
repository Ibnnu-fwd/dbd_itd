<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Interface\RegencyInterface;
use App\Models\TCases;
use App\Repositories\Interface\TCasesInterface;

class TCasesController extends Controller
{
    private $regency;
    private $TCases;

    public function __construct(RegencyInterface $regency, TCasesInterface $TCases)
    {
        $this->TCases = $TCases;
        $this->regency = $regency;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return datatables()
                ->of(TCases::query()->where('is_active', true)) // Gunakan query builder dari model TCases
                ->addColumn('date', function ($data) {
                    return $data->date;
                })
                ->addColumn('regency', function ($data) {
                    return ucwords(strtolower($data->regency->name));
                })
                ->addColumn('district', function ($data) {
                    return ucwords(strtolower($data->district->name));
                })
                ->addColumn('village', function ($data) {
                    return ucwords(strtolower($data->village->name));
                })
                ->addColumn('vector_type', function ($data) {
                    return $data->vector_type;
                })
                ->addColumn('cases_total', function ($data) {
                    return $data->cases_total;
                })
                ->addColumn('action', function ($data) {
                    return view('admin.tcases.column.action', compact('data'));
                })
                ->addIndexColumn()
                ->make(true);
        }
        $tcases = TCases::where('is_active', true)->get();
        $data = $tcases->map(function ($item) {
            return [
                'date' => $item->date,
                'regency' => ucwords(strtolower($item->regency->name)),
                'district' => ucwords(strtolower($item->district->name)),
                'village' => ucwords(strtolower($item->village->name)),
                'regency_id' => $item->regency_id,
                'district_id' => $item->district_id,
                'village_id' => $item->village_id,
                'vector_type' => $item->vector_type,
                'cases_total' => $item->cases_total,
            ];
        });

        return view('admin.tcases.index', ['tcases' => $data]);
    }


    public function create()
    {
        return view('admin.tcases.create', [
            'regencies' => $this->regency->getAll(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            // 'date' => 'required|date_format:Y-F-d',
            'regency_id' => 'required|string',
            'district_id' => 'required|string',
            'regency_id' => 'required|string',
            'village_id' => 'required|string',
            'cases_total' => 'required|numeric',
        ]);

        try {
            $this->TCases->create($request->all());
            return redirect()->route('admin.tcases.index')->with('success', 'Data berhasil disimpan');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function edit($id)
    {
        return view('admin.tcases.edit', [
            'tcases' => $this->TCases->getById($id),
            'regencies' => $this->regency->getAll(),
        ]);
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            // 'date' => 'required|date_format:Y-F-d',
            'regency_id' => 'required|string',
            'district_id' => 'required|string',
            'regency_id' => 'required|string',
            'village_id' => 'required|string',
            'cases_total' => 'required|numeric',
        ]);

        try {
            $this->TCases->update($id, $request->all());
            return redirect()->route('admin.tcases.index')->with('success', 'Data berhasil disimpan');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }


    public function destroy(string $id)
    {
        try {
            $this->TCases->delete($id);
            return response()->json([
                'status' => true,
                'message' => 'Data berhasil dihapus'
            ]);
        } catch (\Throwable $th) {
            dd($th->getMessage());
        }
    }
}
