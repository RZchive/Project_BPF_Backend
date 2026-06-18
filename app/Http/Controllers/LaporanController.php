<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Laporan;
use App\Models\User;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index()
    {
        $data = Laporan::with('user')->paginate(10);
        return view('laporan.index', compact('data'));
    }

    public function create()
    {
        $users = User::orderBy('nama')->get();
        return view('laporan.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'jenis_laporan' => 'required|string|max:255',
            'format_file' => 'required|in:pdf,excel',
        ]);

        Laporan::create($validated);
        return redirect()->route('laporan.index')->with('success', 'Laporan berhasil dibuat');
    }

    public function edit($id)
    {
        $item = Laporan::findOrFail($id);
        $users = User::orderBy('nama')->get();
        return view('laporan.edit', compact('item', 'users'));
    }

    public function update(Request $request, $id)
    {
        $item = Laporan::findOrFail($id);
        $validated = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'jenis_laporan' => 'required|string|max:255',
            'format_file' => 'required|in:pdf,excel',
        ]);

        $item->update($validated);
        return redirect()->route('laporan.index')->with('success', 'Laporan berhasil diperbarui');
    }

    public function destroy($id)
    {
        Laporan::findOrFail($id)->delete();
        return redirect()->route('laporan.index')->with('success', 'Laporan berhasil dihapus');
    }
}
