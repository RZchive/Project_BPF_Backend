<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pelatihan;
use App\Models\PesertaPelatihan;
use App\Models\Sertifikasi;
use Carbon\Carbon;

use Illuminate\Support\Facades\Storage;
use App\Models\Laporan;

class LaporanController extends Controller
{
    public function index()
    {
        $laporans = Laporan::with('user')->orderBy('id', 'desc')->get();
        return response()->json(['success' => true, 'data' => $laporans]);
    }

    public function show($id)
    {
        $laporan = Laporan::findOrFail($id);
        return response()->json(['success' => true, 'data' => $laporan]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis_laporan' => 'required|string',
            'file' => 'nullable|file|max:10240'
        ]);

        $laporan = new Laporan();
        $laporan->jenis_laporan = $request->jenis_laporan;
        $laporan->user_id = $request->user()->id ?? 1;
        
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/laporan', $filename);
            $laporan->format_file = 'laporan/' . $filename;
        }

        $laporan->save();

        return response()->json(['success' => true, 'data' => $laporan, 'message' => 'Laporan berhasil ditambahkan']);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'jenis_laporan' => 'required|string',
            'file' => 'nullable|file|max:10240'
        ]);

        $laporan = Laporan::findOrFail($id);
        $laporan->jenis_laporan = $request->jenis_laporan;
        
        if ($request->hasFile('file')) {
            if ($laporan->format_file && Storage::exists('public/' . $laporan->format_file)) {
                Storage::delete('public/' . $laporan->format_file);
            }
            $file = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/laporan', $filename);
            $laporan->format_file = 'laporan/' . $filename;
        }

        $laporan->save();

        return response()->json(['success' => true, 'data' => $laporan, 'message' => 'Laporan berhasil diupdate']);
    }

    public function destroy($id)
    {
        $laporan = Laporan::findOrFail($id);
        if ($laporan->format_file && Storage::exists('public/' . $laporan->format_file)) {
            Storage::delete('public/' . $laporan->format_file);
        }
        $laporan->delete();

        return response()->json(['success' => true, 'message' => 'Laporan berhasil dihapus']);
    }

    public function dashboard(Request $request)
    {
        // ── Parameter Filter ───────────────────────────────
        $startDate   = $request->input('start_date');
        $endDate     = $request->input('end_date');
        $pelatihanId = $request->input('pelatihan_id');
        $status      = $request->input('status');

        // ── 1. Query Pelatihan ─────────────────────────────
        // Nama kolom di tabel: kuota, status, tanggal_mulai
        $pelatihanQuery = Pelatihan::withCount([
            'peserta as jumlah_peserta',
            'peserta as jumlah_lulus' => function ($q) {
                $q->where('status_peserta', 'lulus');
            },
        ]);

        if ($startDate && $endDate) {
            $pelatihanQuery->whereBetween('tanggal_mulai', [$startDate, $endDate]);
        }
        if ($pelatihanId) {
            $pelatihanQuery->where('id', $pelatihanId);
        }
        if ($status) {
            $pelatihanQuery->where('status', $status);   // kolom: 'status'
        }

        $pelatihans  = $pelatihanQuery->get();
        $pelatihanIds = $pelatihans->pluck('id')->toArray();

        // ── 2. Query Peserta ────────────────────────────────
        $pesertas = PesertaPelatihan::with(['tenagaKerja', 'pelatihan'])
            ->whereIn('pelatihan_id', $pelatihanIds)
            ->get();

        // ── 3. Query Sertifikasi ────────────────────────────
        $sertifikasiQuery = Sertifikasi::with([
            'pesertaPelatihan.tenagaKerja',
            'pesertaPelatihan.pelatihan',
        ])->whereHas('pesertaPelatihan', function ($q) use ($pelatihanIds) {
            $q->whereIn('pelatihan_id', $pelatihanIds);
        });

        if ($startDate && $endDate) {
            $sertifikasiQuery->whereBetween('tanggal_terbit', [$startDate, $endDate]);
        }

        $sertifikasis = $sertifikasiQuery->get();

        // ── 4. Statistik ────────────────────────────────────
        $totalLulus      = $pesertas->where('status_peserta', 'lulus')->count();
        $totalTidakLulus = $pesertas->where('status_peserta', 'tidak_lulus')->count();

        // ── 5. Data Grafik ──────────────────────────────────
        // Bar chart: peserta per pelatihan
        $grafikPeserta = $pelatihans->map(fn($p) => [
            'nama_pelatihan' => $p->nama_pelatihan,
            'peserta'        => (int) $p->jumlah_peserta,
        ])->values();

        // Pie chart: kelulusan
        $grafikKelulusan = [
            ['name' => 'Lulus',       'value' => $totalLulus],
            ['name' => 'Tidak Lulus', 'value' => $totalTidakLulus],
        ];

        // Line chart: sertifikat per bulan
        $months = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
        $grouped = $sertifikasis->groupBy(fn($s) => Carbon::parse($s->tanggal_terbit)->format('n'));
        $grafikSertifikat = collect(range(1, 12))->map(fn($i) => [
            'bulan'  => $months[$i - 1],
            'jumlah' => isset($grouped[$i]) ? $grouped[$i]->count() : 0,
        ])->values();

        return response()->json([
            'success' => true,
            'stats'   => [
                'total_pelatihan'  => $pelatihans->count(),
                'total_peserta'    => $pesertas->count(),
                'total_lulus'      => $totalLulus,
                'total_sertifikat' => $sertifikasis->count(),
            ],
            'data_pelatihan'   => $pelatihans,
            'data_peserta'     => $pesertas,
            'data_sertifikasi' => $sertifikasis,
            'grafik' => [
                'peserta_per_pelatihan' => $grafikPeserta,
                'tingkat_kelulusan'     => $grafikKelulusan,
                'sertifikat_per_bulan'  => $grafikSertifikat,
            ],
        ]);
    }
}