<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\JobFair;
use Illuminate\Http\Request;

class JobFairController extends Controller
{
    public function index()
    {
        $data = JobFair::paginate(10);
        return view('jobfair.index', compact('data'));
    }

    public function create()
    {
        return view('jobfair.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'tanggal' => 'nullable|date',
            'lokasi' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        JobFair::create($validated);
        return redirect()->route('job-fair.index')->with('success', 'Job Fair berhasil dibuat');
    }

    public function edit($id)
    {
        $item = JobFair::findOrFail($id);
        return view('jobfair.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $item = JobFair::findOrFail($id);
        $validated = $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'tanggal' => 'nullable|date',
            'lokasi' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $item->update($validated);
        return redirect()->route('job-fair.index')->with('success', 'Job Fair berhasil diperbarui');
    }

    public function destroy($id)
    {
        JobFair::findOrFail($id)->delete();
        return redirect()->route('job-fair.index')->with('success', 'Job Fair berhasil dihapus');
    }
}
