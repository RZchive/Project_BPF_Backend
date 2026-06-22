<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Lpk;
use App\Models\TenagaKerja;
use App\Models\Pelatihan;
use App\Models\PesertaPelatihan;
use App\Models\TracerStudy;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Default Users (Admin, LPK, Staf)
        $admin = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'nama' => 'Admin Disnaker',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'status' => 'aktif',
            ]
        );

        $staf = User::updateOrCreate(
            ['email' => 'staf@example.com'],
            [
                'nama' => 'Staf Disnaker',
                'password' => Hash::make('password123'),
                'role' => 'staf',
                'status' => 'aktif',
            ]
        );

        $userLpk1 = User::updateOrCreate(
            ['email' => 'riautech@example.com'],
            [
                'nama' => 'User LPK Riau Tech',
                'password' => Hash::make('password123'),
                'role' => 'lpk',
                'status' => 'aktif',
            ]
        );

        $userLpk2 = User::updateOrCreate(
            ['email' => 'otomotif@example.com'],
            [
                'nama' => 'User LPK Otomotif',
                'password' => Hash::make('password123'),
                'role' => 'lpk',
                'status' => 'aktif',
            ]
        );

        // 2. Create LPK Mitra
        $lpk1 = Lpk::updateOrCreate(
            ['email' => 'riautech@example.com'],
            [
                'user_id' => $userLpk1->id,
                'nama_lpk' => 'LPK Riau Tech Academy',
                'alamat' => 'Jl. Sudirman No. 120, Pekanbaru',
                'bidang_keahlian' => 'Teknik Informasi',
                'kontak' => '081234567890',
                'status_aktif' => true,
            ]
        );

        $lpk2 = Lpk::updateOrCreate(
            ['email' => 'otomotif@example.com'],
            [
                'user_id' => $userLpk2->id,
                'nama_lpk' => 'LPK Otomotif Riau Sejahtera',
                'alamat' => 'Jl. Soebrantas No. 45, Pekanbaru',
                'bidang_keahlian' => 'Teknik Otomotif',
                'kontak' => '081277665544',
                'status_aktif' => true,
            ]
        );

        // 3. Create Tenaga Kerja (Trainees / Alumni)
        $tkData = [
            [
                'nik' => '1471011212950001',
                'nama' => 'Budi Santoso',
                'email' => 'budi@example.com',
                'no_hp' => '081211223344',
                'jenis_kelamin' => 'L',
                'tanggal_lahir' => '1995-12-12',
                'alamat' => 'Jl. Kamboja No. 5, Pekanbaru',
                'pendidikan_terakhir' => 'SMK Teknik',
                'status_pekerjaan' => 'Belum Bekerja',
            ],
            [
                'nik' => '1471012304980002',
                'nama' => 'Siti Aminah',
                'email' => 'siti@example.com',
                'no_hp' => '081255667788',
                'jenis_kelamin' => 'P',
                'tanggal_lahir' => '1998-04-23',
                'alamat' => 'Jl. Melur No. 12, Pekanbaru',
                'pendidikan_terakhir' => 'SMA IPA',
                'status_pekerjaan' => 'Belum Bekerja',
            ],
            [
                'nik' => '1471011508960003',
                'nama' => 'Rian Hidayat',
                'email' => 'rian@example.com',
                'no_hp' => '081388990011',
                'jenis_kelamin' => 'L',
                'tanggal_lahir' => '1996-08-15',
                'alamat' => 'Jl. Tulip No. 2, Pekanbaru',
                'pendidikan_terakhir' => 'D3 Komputer',
                'status_pekerjaan' => 'Bekerja',
            ],
            [
                'nik' => '1471010101970004',
                'nama' => 'Dewi Lestari',
                'email' => 'dewi@example.com',
                'no_hp' => '082133445566',
                'jenis_kelamin' => 'P',
                'tanggal_lahir' => '1997-01-01',
                'alamat' => 'Jl. Dahlia No. 18, Pekanbaru',
                'pendidikan_terakhir' => 'S1 Teknik',
                'status_pekerjaan' => 'Wirausaha',
            ],
            [
                'nik' => '1471011909990005',
                'nama' => 'Andi Wijaya',
                'email' => 'andi@example.com',
                'no_hp' => '085299887766',
                'jenis_kelamin' => 'L',
                'tanggal_lahir' => '1999-09-19',
                'alamat' => 'Jl. Melati No. 4, Pekanbaru',
                'pendidikan_terakhir' => 'SMK Otomotif',
                'status_pekerjaan' => 'Belum Bekerja',
            ],
        ];

        $tks = [];
        foreach ($tkData as $tk) {
            $tks[] = TenagaKerja::updateOrCreate(['nik' => $tk['nik']], $tk);
        }

        // 4. Create Pelatihan
        $p1 = Pelatihan::updateOrCreate(
            ['nama_pelatihan' => 'Pelatihan Pemrograman Web Laravel'],
            [
                'lpk_id' => $lpk1->id,
                'jenis_pelatihan' => 'APBD',
                'jurusan' => 'Teknik Informasi',
                'deskripsi' => 'Pelatihan intensif 1 bulan tentang dasar-dasar pemrograman web menggunakan framework Laravel untuk menyiapkan junior programmer.',
                'kuota' => 20,
                'status' => 'aktif',
                'tanggal_mulai' => '2026-06-01',
                'tanggal_selesai' => '2026-06-30',
            ]
        );

        $p2 = Pelatihan::updateOrCreate(
            ['nama_pelatihan' => 'Pelatihan Montir Sepeda Motor Dasar'],
            [
                'lpk_id' => $lpk2->id,
                'jenis_pelatihan' => 'APBN',
                'jurusan' => 'Teknik Otomotif',
                'deskripsi' => 'Pelatihan montir sepeda motor dari dasar hingga pemecahan masalah mesin ringan dan servis berkala.',
                'kuota' => 15,
                'status' => 'selesai',
                'tanggal_mulai' => '2026-05-01',
                'tanggal_selesai' => '2026-05-25',
            ]
        );

        // 5. Create Peserta Pelatihan
        // Pelatihan 1 (Laravel) - Peserta: Budi, Siti, Rian
        PesertaPelatihan::updateOrCreate(
            ['tenaga_kerja_id' => $tks[0]->id, 'pelatihan_id' => $p1->id],
            ['status_peserta' => 'aktif']
        );
        PesertaPelatihan::updateOrCreate(
            ['tenaga_kerja_id' => $tks[1]->id, 'pelatihan_id' => $p1->id],
            ['status_peserta' => 'aktif']
        );
        PesertaPelatihan::updateOrCreate(
            ['tenaga_kerja_id' => $tks[2]->id, 'pelatihan_id' => $p1->id],
            ['status_peserta' => 'aktif']
        );

        // Pelatihan 2 (Otomotif) - Peserta: Dewi, Andi (Keduanya Lulus)
        PesertaPelatihan::updateOrCreate(
            ['tenaga_kerja_id' => $tks[3]->id, 'pelatihan_id' => $p2->id],
            ['status_peserta' => 'lulus']
        );
        PesertaPelatihan::updateOrCreate(
            ['tenaga_kerja_id' => $tks[4]->id, 'pelatihan_id' => $p2->id],
            ['status_peserta' => 'lulus']
        );

        // 6. Create Tracer Study for graduates (Dewi & Andi)
        TracerStudy::updateOrCreate(
            ['tenaga_kerja_id' => $tks[3]->id],
            [
                'status_alumni' => 'membuka_usaha',
                'nama_perusahaan' => 'Bengkel Mandiri Dewi',
                'jabatan' => 'Owner',
                'gaji' => 'Rp 5.000.000',
                'keterangan' => 'Sukses membuka bengkel mandiri di rumah.',
                'tanggal_update' => '2026-06-10',
            ]
        );

        TracerStudy::updateOrCreate(
            ['tenaga_kerja_id' => $tks[4]->id],
            [
                'status_alumni' => 'bekerja_sesuai_bidang',
                'nama_perusahaan' => 'PT Astra International Riau',
                'jabatan' => 'Junior Mechanic',
                'gaji' => 'Rp 3.500.000',
                'keterangan' => 'Diterima bekerja 2 minggu pasca pelatihan selesai.',
                'tanggal_update' => '2026-06-15',
            ]
        );
    }
}
