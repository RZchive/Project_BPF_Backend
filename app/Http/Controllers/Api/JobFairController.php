<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\JobFair;
use Illuminate\Http\Request;

class JobFairController extends Controller
{
    public function index(Request $request)
    {
        if ($request->wantsJson() || $request->is('api/*')) {
            $query = JobFair::with('perusahaan.perusahaan');
            if ($request->query('paginate') === 'false') {
                $data = $query->latest()->get();
            } else {
                $data = $query->latest()->paginate(10);
            }
            return response()->json(['success' => true, 'data' => $data]);
        }

        $data = JobFair::with('perusahaan.perusahaan')->paginate(10);
        return view('jobfair.index', compact('data'));
    }

    public function create()
    {
        return view('jobfair.create');
    }

    public function store(Request $request)
    {
        $isApi = $request->wantsJson() || $request->is('api/*');

        $validated = $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'tanggal'       => 'nullable|date',
            'lokasi'        => 'nullable|string|max:255',
            'deskripsi'     => 'nullable|string',
        ]);

        $jobFair = JobFair::create($validated);

        if ($isApi) {
            return response()->json([
                'success' => true,
                'message' => 'Data job fair berhasil ditambahkan',
                'data' => $jobFair
            ], 201);
        }

        return redirect()->route('job-fair.index')->with('success', 'Job Fair berhasil dibuat');
    }

    public function show(Request $request, string $id)
    {
        $jobFair = JobFair::with('perusahaan.perusahaan')->find($id);

        if (!$jobFair) {
            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data job fair tidak ditemukan'
                ], 404);
            }
            abort(404);
        }

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'data' => $jobFair
            ]);
        }

        abort(404);
    }

    public function edit(string $id)
    {
        $item = JobFair::findOrFail($id);
        return view('jobfair.edit', compact('item'));
    }

    public function update(Request $request, string $id)
    {
        $jobFair = JobFair::find($id);
        $isApi = $request->wantsJson() || $request->is('api/*');

        if (!$jobFair) {
            if ($isApi) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data job fair tidak ditemukan'
                ], 404);
            }
            abort(404);
        }

        $validated = $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'tanggal'       => 'nullable|date',
            'lokasi'        => 'nullable|string|max:255',
            'deskripsi'     => 'nullable|string',
        ]);

        $jobFair->update($validated);

        if ($isApi) {
            return response()->json([
                'success' => true,
                'message' => 'Data job fair berhasil diperbarui',
                'data' => $jobFair
            ]);
        }

        return redirect()->route('job-fair.index')->with('success', 'Job Fair berhasil diperbarui');
    }

    public function destroy(Request $request, string $id)
    {
        $jobFair = JobFair::find($id);
        $isApi = $request->wantsJson() || $request->is('api/*');

        if (!$jobFair) {
            if ($isApi) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data job fair tidak ditemukan'
                ], 404);
            }
            abort(404);
        }

        $jobFair->delete();

        if ($isApi) {
            return response()->json([
                'success' => true,
                'message' => 'Data job fair berhasil dihapus'
            ]);
        }

        return redirect()->route('job-fair.index')->with('success', 'Job Fair berhasil dihapus');
    }
}