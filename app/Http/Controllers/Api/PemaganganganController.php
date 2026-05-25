<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pemagangan;
use Illuminate\Http\Request;

class PemaganganganController extends Controller
{
    public function index()
    {
        $data = Pemagangan::with(['tenagaKerja', 'perusahaan'])->paginate(10);
        return response()->json(['success' => true, 'data' => $data]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tenaga_kerja_id' => 'required|exists:tenaga_kerja,id',
            'perusahaan_id'   => 'required|exists:perusahaan_mitra,id',
            'bidang'          => 'required',
            'durasi'          => 'required',
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'status'          => 'nullable|in:berjalan,selesai',
        ]);

        $p = Pemagangan::create($validated);
        return response()->json(['success' => true, 'data' => $p], 201);
    }

    public function show($id)
    {
        $p = Pemagangan::with(['tenagaKerja', 'perusahaan'])->findOrFail($id);
        return response()->json(['success' => true, 'data' => $p]);
    }

    public function update(Request $request, $id)
    {
        $p = Pemagangan::findOrFail($id);
        $request->validate([
            'bidang'          => 'sometimes',
            'durasi'          => 'sometimes',
            'tanggal_mulai'   => 'sometimes|date',
            'tanggal_selesai' => 'sometimes|date',
            'status'          => 'sometimes|in:berjalan,selesai',
        ]);
        $p->update($request->all());
        return response()->json(['success' => true, 'data' => $p]);
    }

    public function destroy($id)
    {
        Pemagangan::findOrFail($id)->delete();
        return response()->json(['success' => true, 'message' => 'Data berhasil dihapus']);
    }
}