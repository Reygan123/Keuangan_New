<?php

namespace Database\Seeders;

use App\Models\Akun;
use App\Models\JenisPajak;
use App\Models\Usaha;
use Illuminate\Database\Seeder;

class JenisPajakSeeder extends Seeder
{
    /**
     * Jalankan otomatis ke SEMUA usaha:
     * php artisan db:seed --class=JenisPajakSeeder
     */
    public function run(): void
    {
        $usahas = Usaha::all();

        foreach ($usahas as $usaha) {
            $this->runForUsaha($usaha->id);
        }

        $this->command?->info('Jenis pajak berhasil di-setup untuk ' . $usahas->count() . ' usaha.');
    }

    /**
     * Jalankan manual untuk 1 usaha spesifik:
     * php artisan tinker
     * (new \Database\Seeders\JenisPajakSeeder)->runForUsaha($usahaId);
     */
    public function runForUsaha(int $usahaId): void
    {
        $daftarPajak = [
            [
                'kode' => 'PPH21',
                'nama' => 'PPh Pasal 21',
                'kategori' => 'WITHHOLDING',
                'arah' => null,
                'tarif_persen' => 5.00,
                'nama_akun' => 'Utang PPh 21',
            ],
            [
                'kode' => 'PPH23',
                'nama' => 'PPh Pasal 23',
                'kategori' => 'WITHHOLDING',
                'arah' => null,
                'tarif_persen' => 2.00,
                'nama_akun' => 'Utang PPh 23',
            ],
            [
                'kode' => 'PPH4A2',
                'nama' => 'PPh Final Pasal 4 ayat (2)',
                'kategori' => 'WITHHOLDING',
                'arah' => null,
                'tarif_persen' => 10.00,
                'nama_akun' => 'Utang PPh Final 4(2)',
            ],
            [
                'kode' => 'PPN_KELUARAN',
                'nama' => 'PPN Keluaran',
                'kategori' => 'PPN',
                'arah' => 'KELUARAN',
                'tarif_persen' => 11.00,
                'nama_akun' => 'PPN Keluaran',
            ],
            [
                'kode' => 'PPN_MASUKAN',
                'nama' => 'PPN Masukan',
                'kategori' => 'PPN',
                'arah' => 'MASUKAN',
                'tarif_persen' => 11.00,
                'nama_akun' => 'PPN Masukan',
            ],
        ];

        foreach ($daftarPajak as $item) {
            $akun = Akun::where('usaha_id', $usahaId)
                ->where('name', $item['nama_akun'])
                ->first();

            if (!$akun) {
                $isAset = $item['kode'] === 'PPN_MASUKAN';

                $akun = Akun::create([
                    'usaha_id' => $usahaId,
                    'name' => $item['nama_akun'],
                    'saldo' => 0,
                    'klasifikasi' => $isAset ? 'ASET' : 'KEWAJIBAN',
                    'sub_klasifikasi' => 'LANCAR',
                    'aktivitas_kas' => 'TIDAK BERLAKU',
                    'kode' => $this->generateKodeAkun($usahaId, $isAset ? 'ASET' : 'KEWAJIBAN'),
                ]);
            }

            JenisPajak::updateOrCreate(
                ['usaha_id' => $usahaId, 'kode' => $item['kode']],
                [
                    'nama' => $item['nama'],
                    'kategori' => $item['kategori'],
                    'arah' => $item['arah'],
                    'tarif_persen' => $item['tarif_persen'],
                    'akun_id' => $akun->id,
                    'aktif' => true,
                ]
            );
        }
    }

    private function generateKodeAkun(int $usahaId, string $klasifikasi): string
    {
        $prefix = $klasifikasi === 'ASET' ? '1' : '2';
        $last = Akun::where('usaha_id', $usahaId)
            ->where('klasifikasi', $klasifikasi)
            ->orderBy('kode', 'desc')
            ->first();

        if ($last && $last->kode) {
            return (string) (intval($last->kode) + 1);
        }

        return $prefix . '9000';
    }
}