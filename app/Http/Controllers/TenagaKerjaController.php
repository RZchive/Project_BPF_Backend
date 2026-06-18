<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\TenagaKerja;
use Illuminate\Http\Request;

class TenagaKerjaController extends Controller
{
    public function index()
    {
        $data = TenagaKerja::paginate(10);
        return view('tenaga_kerja.index', compact('data'));
    }

    public function create()
    {
        return view('tenaga_kerja.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nik' => 'required|string|max:50',
            'nama' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'no_hp' => 'nullable|string|max:50',
            'jenis_kelamin' => 'nullable|in:L,P',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable|string|max:500',
            'pendidikan_terakhir' => 'nullable|string|max:100',
            'status_pekerjaan' => 'nullable|string|max:100',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('fotos', 'public');
        }

        TenagaKerja::create($validated);
        return redirect()->route('tenaga-kerja.index')->with('success', 'Tenaga Kerja berhasil dibuat');
    }

    public function edit($id)
    {
        $item = TenagaKerja::findOrFail($id);
        return view('tenaga_kerja.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $item = TenagaKerja::findOrFail($id);

        $validated = $request->validate([
            'nik' => 'required|string|max:50',
            'nama' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'no_hp' => 'nullable|string|max:50',
            'jenis_kelamin' => 'nullable|in:L,P',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable|string|max:500',
            'pendidikan_terakhir' => 'nullable|string|max:100',
            'status_pekerjaan' => 'nullable|string|max:100',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('fotos', 'public');
        }

        $item->update($validated);
        return redirect()->route('tenaga-kerja.index')->with('success', 'Tenaga Kerja berhasil diperbarui');
    }

    public function destroy($id)
    {
        TenagaKerja::findOrFail($id)->delete();
        return redirect()->route('tenaga-kerja.index')->with('success', 'Tenaga Kerja berhasil dihapus');
    }
}
