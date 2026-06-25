<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Laporan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        if ($request->wantsJson() || $request->is('api/*')) {
            $data = Laporan::with('user')->latest()->get();
            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        }

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
        $isApi = $request->wantsJson() || $request->is('api/*');

        $validated = $request->validate([
            'jenis_laporan' => 'required|string|max:255',
            'file'          => 'required|file|mimes:pdf,xls,xlsx|max:10240',
        ]);

        $file = $request->file('file');
        $extension = strtolower($file->getClientOriginalExtension());
        $formatFile = in_array($extension, ['xls', 'xlsx']) ? 'excel' : 'pdf';

        $filePath = $file->store('laporan', 'public');

        $laporan = Laporan::create([
            'user_id'       => $request->user()?->id ?? 1,
            'jenis_laporan' => $validated['jenis_laporan'],
            'format_file'   => $formatFile,
            'file'          => $filePath,
        ]);

        if ($isApi) {
            return response()->json([
                'success' => true,
                'message' => 'Data laporan berhasil ditambahkan',
                'data' => $laporan
            ], 201);
        }

        return redirect()->route('laporan.index')->with('success', 'Laporan berhasil dibuat');
    }

    public function show(Request $request, string $id)
    {
        $laporan = Laporan::with('user')->find($id);

        if (!$laporan) {
            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data laporan tidak ditemukan'
                ], 404);
            }
            abort(404);
        }

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'data' => $laporan
            ]);
        }

        abort(404);
    }

    public function edit(string $id)
    {
        $item = Laporan::findOrFail($id);
        $users = User::orderBy('nama')->get();
        return view('laporan.edit', compact('item', 'users'));
    }

    public function update(Request $request, string $id)
    {
        $laporan = Laporan::find($id);
        $isApi = $request->wantsJson() || $request->is('api/*');

        if (!$laporan) {
            if ($isApi) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data laporan tidak ditemukan'
                ], 404);
            }
            abort(404);
        }

        $validated = $request->validate([
            'jenis_laporan' => 'required|string|max:255',
            'file'          => 'nullable|file|mimes:pdf,xls,xlsx|max:10240',
        ]);

        $updateData = [
            'jenis_laporan' => $validated['jenis_laporan'],
        ];

        if ($request->hasFile('file')) {
            if ($laporan->file) {
                Storage::disk('public')->delete($laporan->file);
            }

            $file = $request->file('file');
            $extension = strtolower($file->getClientOriginalExtension());
            $updateData['format_file'] = in_array($extension, ['xls', 'xlsx']) ? 'excel' : 'pdf';
            $updateData['file'] = $file->store('laporan', 'public');
        }

        $laporan->update($updateData);

        if ($isApi) {
            return response()->json([
                'success' => true,
                'message' => 'Data laporan berhasil diperbarui',
                'data' => $laporan
            ]);
        }

        return redirect()->route('laporan.index')->with('success', 'Laporan berhasil diperbarui');
    }

    public function destroy(Request $request, string $id)
    {
        $laporan = Laporan::find($id);
        $isApi = $request->wantsJson() || $request->is('api/*');

        if (!$laporan) {
            if ($isApi) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data laporan tidak ditemukan'
                ], 404);
            }
            abort(404);
        }

        if ($laporan->file) {
            Storage::disk('public')->delete($laporan->file);
        }

        $laporan->delete();

        if ($isApi) {
            return response()->json([
                'success' => true,
                'message' => 'Data laporan berhasil dihapus'
            ]);
        }

        return redirect()->route('laporan.index')->with('success', 'Laporan berhasil dihapus');
    }
}