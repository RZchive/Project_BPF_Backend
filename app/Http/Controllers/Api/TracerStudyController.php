<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TracerStudy;
use Illuminate\Http\Request;

class TracerStudyController extends Controller
{
    public function index(Request $request)
    {
        $query = TracerStudy::with('tenagaKerja');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function($q) use ($s) {
                $q->whereHas('tenagaKerja', function($inner) use ($s) {
                    $inner->where('nama', 'like', "%$s%")
                          ->orWhere('nik', 'like', "%$s%");
                })->orWhere('nama_perusahaan', 'like', "%$s%")
                  ->orWhere('status_alumni', 'like', "%$s%");
            });
        }

        $data = $query->paginate(10);
        return response()->json(['success' => true, 'data' => $data]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tenaga_kerja_id' => 'required|exists:tenaga_kerja,id',
            'status_alumni'   => 'required|in:bekerja_sesuai_bidang,membuka_usaha,belum_bekerja',
            'nama_perusahaan' => 'nullable',
            'jabatan'         => 'nullable',
            'gaji'            => 'nullable',
            'keterangan'      => 'nullable',
            'tanggal_update'  => 'nullable|date',
        ]);

        $t = TracerStudy::create($validated);
        return response()->json(['success' => true, 'data' => $t], 201);
    }

    public function show($id)
    {
        $t = TracerStudy::with('tenagaKerja')->findOrFail($id);
        return response()->json(['success' => true, 'data' => $t]);
    }

    public function update(Request $request, $id)
    {
        $t = TracerStudy::findOrFail($id);
        $request->validate([
            'status_alumni'   => 'sometimes|in:bekerja_sesuai_bidang,membuka_usaha,belum_bekerja',
            'nama_perusahaan' => 'nullable',
            'jabatan'         => 'nullable',
            'gaji'            => 'nullable',
            'keterangan'      => 'nullable',
            'tanggal_update'  => 'nullable|date',
        ]);
        $t->update($request->all());
        return response()->json(['success' => true, 'data' => $t]);
    }

    public function destroy($id)
    {
        TracerStudy::findOrFail($id)->delete();
        return response()->json(['success' => true, 'message' => 'Data berhasil dihapus']);
    }
}