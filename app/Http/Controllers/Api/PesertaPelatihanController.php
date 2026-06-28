<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PesertaPelatihan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PesertaPelatihanController extends Controller
{
    public function index(Request $request)
    {
        $query = PesertaPelatihan::with(['tenagaKerja', 'pelatihan', 'sertifikasi']);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->whereHas('tenagaKerja', function ($q) use ($s) {
                $q->where('nama', 'like', "%$s%")
                  ->orWhere('nik', 'like', "%$s%");
            });
        }

        if ($request->filled('pelatihan_id')) {
            $query->where('pelatihan_id', $request->pelatihan_id);
        }

        if ($request->filled('status_peserta')) {
            $query->where('status_peserta', $request->status_peserta);
        }

        if ($request->query('paginate') === 'false') {
            $data = $query->latest('id')->get();
        } else {
            $data = $query->latest('id')->paginate(10);
        }

        return response()->json(['success' => true, 'data' => $data]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tenaga_kerja_id' => 'required|exists:tenaga_kerja,id',
            'pelatihan_id'    => 'required|exists:pelatihan,id',
            'status_peserta'  => 'nullable|in:aktif,lulus,tidak_lulus',
            'nilai'           => 'nullable|integer|min:0|max:100',
            'foto'            => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('peserta', 'public');
        }

        $p = PesertaPelatihan::create($validated);
        $p->load(['tenagaKerja', 'pelatihan', 'sertifikasi']);

        return response()->json(['success' => true, 'data' => $p], 201);
    }

    public function show($id)
    {
        $p = PesertaPelatihan::with(['tenagaKerja', 'pelatihan', 'sertifikasi'])->findOrFail($id);
        return response()->json(['success' => true, 'data' => $p]);
    }

    public function update(Request $request, $id)
    {
        $p = PesertaPelatihan::findOrFail($id);

        $request->validate([
            'status_peserta'  => 'sometimes|in:aktif,lulus,tidak_lulus',
            'nilai'           => 'nullable|integer|min:0|max:100',
            'foto'            => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only(['status_peserta', 'nilai']);

        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($p->foto) {
                Storage::disk('public')->delete($p->foto);
            }
            $data['foto'] = $request->file('foto')->store('peserta', 'public');
        }

        $p->update($data);
        $p->load(['tenagaKerja', 'pelatihan', 'sertifikasi']);

        return response()->json(['success' => true, 'data' => $p]);
    }

    public function destroy($id)
    {
        $p = PesertaPelatihan::findOrFail($id);

        if ($p->foto) {
            Storage::disk('public')->delete($p->foto);
        }

        $p->delete();
        return response()->json(['success' => true, 'message' => 'Data berhasil dihapus']);
    }

    public function importPreview(Request $request)
    {
        $request->validate([
            'pelatihan_id' => 'required|exists:pelatihan,id',
            'data'         => 'required|array',
        ]);

        $pelatihanId = $request->pelatihan_id;
        $inputRows   = $request->data;

        $processed = [];
        $seenNiks  = [];
        $validCount = 0;
        $errorCount = 0;

        foreach ($inputRows as $row) {
            $nik = trim($row['nik'] ?? $row['NIK'] ?? '');
            $nilaiStr = trim($row['nilai'] ?? $row['Nilai'] ?? '');
            $statusStr = trim($row['status'] ?? $row['Status'] ?? 'aktif');

            $nama = '-';
            $isValid = true;
            $errorMessage = '';
            $tenagaKerjaId = null;

            // 1. Cek NIK kosong
            if (empty($nik)) {
                $isValid = false;
                $errorMessage = 'NIK tidak boleh kosong';
            } else {
                // 2. Cek NIK ganda di file
                if (in_array($nik, $seenNiks)) {
                    $isValid = false;
                    $errorMessage = 'NIK ganda terdeteksi dalam file';
                } else {
                    $seenNiks[] = $nik;
                }
            }

            // 3. Cek NIK di database
            if ($isValid) {
                $worker = \App\Models\TenagaKerja::where('nik', $nik)->first();
                if (!$worker) {
                    $isValid = false;
                    $errorMessage = 'NIK tidak ditemukan pada data tenaga kerja';
                } else {
                    $nama = $worker->nama;
                    $tenagaKerjaId = $worker->id;

                    // 4. Cek apakah sudah terdaftar di pelatihan ini
                    $exists = PesertaPelatihan::where('pelatihan_id', $pelatihanId)
                        ->where('tenaga_kerja_id', $tenagaKerjaId)
                        ->exists();
                    if ($exists) {
                        $isValid = false;
                        $errorMessage = 'Sudah terdaftar pada program pelatihan ini';
                    }
                }
            }

            // 5. Cek Nilai
            $nilai = null;
            if ($isValid && $nilaiStr !== '') {
                if (!is_numeric($nilaiStr) || $nilaiStr < 0 || $nilaiStr > 100) {
                    $isValid = false;
                    $errorMessage = 'Nilai harus berupa angka antara 0-100';
                } else {
                    $nilai = (int) $nilaiStr;
                }
            }

            // 6. Cek Status
            $status = 'aktif';
            if ($isValid) {
                $checkStatus = strtolower(str_replace(' ', '_', $statusStr));
                if ($checkStatus === 'aktif' || $checkStatus === 'lulus' || $checkStatus === 'tidak_lulus') {
                    $status = $checkStatus;
                } else {
                    $isValid = false;
                    $errorMessage = 'Status harus Aktif, Lulus, atau Tidak Lulus';
                }
            }

            if ($isValid) {
                $validCount++;
            } else {
                $errorCount++;
            }

            $processed[] = [
                'nik'             => $nik,
                'nama'            => $nama,
                'nilai'           => $nilaiStr === '' ? null : $nilaiStr,
                'status_peserta'  => $status,
                'is_valid'        => $isValid,
                'error_message'   => $errorMessage,
                'tenaga_kerja_id' => $tenagaKerjaId,
            ];
        }

        return response()->json([
            'success' => true,
            'preview' => $processed,
            'summary' => [
                'total' => count($processed),
                'valid' => $validCount,
                'error' => $errorCount,
            ]
        ]);
    }

    public function importCommit(Request $request)
    {
        $request->validate([
            'pelatihan_id' => 'required|exists:pelatihan,id',
            'filename'     => 'required|string',
            'data'         => 'required|array',
        ]);

        $pelatihanId = $request->pelatihan_id;
        $filename    = $request->filename;
        $rows        = $request->data;
        $userId      = $request->user()->id;

        $inserted = 0;

        \Illuminate\Support\Facades\DB::transaction(function () use ($pelatihanId, $filename, $rows, $userId, &$inserted) {
            foreach ($rows as $row) {
                // Double check if already registered
                $exists = PesertaPelatihan::where('pelatihan_id', $pelatihanId)
                    ->where('tenaga_kerja_id', $row['tenaga_kerja_id'])
                    ->exists();

                if (!$exists) {
                    PesertaPelatihan::create([
                        'pelatihan_id'    => $pelatihanId,
                        'tenaga_kerja_id' => $row['tenaga_kerja_id'],
                        'status_peserta'  => $row['status_peserta'] ?? 'aktif',
                        'nilai'           => $row['nilai'] ?? null,
                    ]);
                    $inserted++;
                }
            }

            // Create riwayat_import entry
            \App\Models\RiwayatImport::create([
                'user_id'      => $userId,
                'pelatihan_id' => $pelatihanId,
                'filename'     => $filename,
                'total_rows'   => count($rows),
                'valid_rows'   => $inserted,
            ]);
        });

        return response()->json([
            'success' => true,
            'message' => "Berhasil mengimpor {$inserted} data peserta.",
            'inserted' => $inserted
        ]);
    }

    public function importHistory()
    {
        $history = \App\Models\RiwayatImport::with(['user', 'pelatihan'])
            ->latest('id')
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $history
        ]);
    }
}