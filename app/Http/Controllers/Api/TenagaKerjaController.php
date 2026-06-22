<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TenagaKerja;
use Illuminate\Http\Request;

class TenagaKerjaController extends Controller
{
    public function index(Request $request)
    {
        if ($request->wantsJson() || $request->is('api/*')) {
            $data = TenagaKerja::latest()->get();
            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        }

        $data = TenagaKerja::paginate(10);
        return view('tenaga_kerja.index', compact('data'));
    }

    public function create()
    {
        return view('tenaga_kerja.create');
    }

    public function store(Request $request)
    {
        $isApi = $request->wantsJson() || $request->is('api/*');

        $validated = $request->validate([
            'nik' => 'required|string|max:50|unique:tenaga_kerja,nik',
            'nama' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'no_hp' => 'nullable|string|max:50',
            'jenis_kelamin' => 'nullable|in:L,P',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable|string|max:500',
            'pendidikan_terakhir' => 'nullable|string|max:100',
            'status_pekerjaan' => 'nullable|string|max:100',
            'foto' => $isApi ? 'nullable' : 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('fotos', 'public');
        }

        $tenagaKerja = TenagaKerja::create($validated);

        if ($isApi) {
            return response()->json([
                'success' => true,
                'message' => 'Data tenaga kerja berhasil ditambahkan',
                'data' => $tenagaKerja
            ], 201);
        }

        return redirect()->route('tenaga-kerja.index')->with('success', 'Tenaga Kerja berhasil dibuat');
    }

    public function show(Request $request, string $id)
    {
        $data = TenagaKerja::find($id);

        if (!$data) {
            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak ditemukan'
                ], 404);
            }
            abort(404);
        }

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        }

        return abort(404); // Default to 404 as there is no specific web show view in routes/web.php except web-show is excluded
    }

    public function edit(string $id)
    {
        $item = TenagaKerja::findOrFail($id);
        return view('tenaga_kerja.edit', compact('item'));
    }

    public function update(Request $request, string $id)
    {
        $data = TenagaKerja::find($id);
        $isApi = $request->wantsJson() || $request->is('api/*');

        if (!$data) {
            if ($isApi) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak ditemukan'
                ], 404);
            }
            abort(404);
        }

        $validated = $request->validate([
            'nik' => 'required|string|max:50|unique:tenaga_kerja,nik,' . $id,
            'nama' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'no_hp' => 'nullable|string|max:50',
            'jenis_kelamin' => 'nullable|in:L,P',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable|string|max:500',
            'pendidikan_terakhir' => 'nullable|string|max:100',
            'status_pekerjaan' => 'nullable|string|max:100',
            'foto' => $isApi ? 'nullable' : 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('fotos', 'public');
        }

        $data->update($validated);

        if ($isApi) {
            return response()->json([
                'success' => true,
                'message' => 'Data tenaga kerja berhasil diperbarui',
                'data' => $data
            ]);
        }

        return redirect()->route('tenaga-kerja.index')->with('success', 'Tenaga Kerja berhasil diperbarui');
    }

    public function destroy(Request $request, string $id)
    {
        $data = TenagaKerja::find($id);
        $isApi = $request->wantsJson() || $request->is('api/*');

        if (!$data) {
            if ($isApi) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak ditemukan'
                ], 404);
            }
            abort(404);
        }

        $data->delete();

        if ($isApi) {
            return response()->json([
                'success' => true,
                'message' => 'Data tenaga kerja berhasil dihapus'
            ]);
        }

        return redirect()->route('tenaga-kerja.index')->with('success', 'Tenaga Kerja berhasil dihapus');
    }
}