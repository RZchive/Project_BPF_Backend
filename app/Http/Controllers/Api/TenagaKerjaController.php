<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TenagaKerja;
use Illuminate\Http\Request;

class TenagaKerjaController extends Controller
{
    public function index()
    {
        $data = TenagaKerja::paginate(10);
        return response()->json(['success' => true, 'data' => $data]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nik'                 => 'required|unique:tenaga_kerja',
            'nama'                => 'required',
            'email'               => 'required|email',
            'no_hp'               => 'required',
            'jenis_kelamin'       => 'required|in:L,P',
            'tanggal_lahir'       => 'required|date',
            'alamat'              => 'required',
            'pendidikan_terakhir' => 'required',
            'status_pekerjaan'    => 'required',
        ]);

        $tk = TenagaKerja::create($validated);
        return response()->json(['success' => true, 'data' => $tk], 201);
    }

    public function show($id)
    {
        $tk = TenagaKerja::with(['sertifikasi', 'tracerStudy', 'pemagangan', 'pesertaPelatihan'])->findOrFail($id);
        return response()->json(['success' => true, 'data' => $tk]);
    }

    public function update(Request $request, $id)
    {
        $tk = TenagaKerja::findOrFail($id);
        $tk->update($request->all());
        return response()->json(['success' => true, 'data' => $tk]);
    }

    public function destroy($id)
    {
        TenagaKerja::findOrFail($id)->delete();
        return response()->json(['success' => true, 'message' => 'Data berhasil dihapus']);
    }
}