<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sertifikasi;
use Illuminate\Http\Request;

class SertifikasiController extends Controller
{
    public function index()
    {
        $data = Sertifikasi::with('tenagaKerja')->paginate(10);
        return response()->json(['success' => true, 'data' => $data]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tenaga_kerja_id'    => 'required|exists:tenaga_kerja,id',
            'nama_sertifikasi'   => 'required',
            'lembaga_sertifikasi'=> 'required',
            'nomor_sertifikat'   => 'required',
            'tanggal_terbit'     => 'required|date',
            'masa_berlaku'       => 'required|date|after:tanggal_terbit',
        ]);

        $s = Sertifikasi::create($validated);
        return response()->json(['success' => true, 'data' => $s], 201);
    }

    public function show($id)
    {
        $s = Sertifikasi::with('tenagaKerja')->findOrFail($id);
        return response()->json(['success' => true, 'data' => $s]);
    }

    public function update(Request $request, $id)
    {
        $s = Sertifikasi::findOrFail($id);
        $request->validate([
            'nama_sertifikasi'    => 'sometimes',
            'lembaga_sertifikasi' => 'sometimes',
            'nomor_sertifikat'    => 'sometimes',
            'tanggal_terbit'      => 'sometimes|date',
            'masa_berlaku'        => 'sometimes|date',
        ]);
        $s->update($request->all());
        return response()->json(['success' => true, 'data' => $s]);
    }

    public function destroy($id)
    {
        Sertifikasi::findOrFail($id)->delete();
        return response()->json(['success' => true, 'message' => 'Data berhasil dihapus']);
    }
}