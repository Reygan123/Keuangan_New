<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SlipGaji extends Model
{
    protected $fillable = [
        'usaha_id', 'karyawan_id', 'nomor_slip', 'periode',
        // Upah & Pendapatan
        'gaji_pokok', // Berfungsi sebagai Upah Tetap (Gaji Pokok + Porsi KPI)
        'tunjangan_jabatan', 'tunjangan_transport',
        'tunjangan_makan', 'tunjangan_komunikasi', 'tunjangan_lainnya',
        'lembur', 'bonus', 'insentif', // insentif = Insentif KPI
        'reimbursement',
        // Potongan Karyawan
        'pph21', 'bpjs_kesehatan', 'bpjs_jht', 'bpjs_jp',
        'potongan_lainnya', 'keterangan_potongan_lainnya',
        // Kontribusi Perusahaan
        'jht_perusahaan', 'jkk_perusahaan', 'jkm_perusahaan',
        'jp_perusahaan', 'jkp_perusahaan', 'bpjs_kesehatan_perusahaan',
        // Tanggal & Info Payroll Tambahan
        'tanggal_insentif_kpi', 'tanggal_lembur_reimburse', 'cutoff_lembur',
        'status_kpi', 'laporan_finance',
        // Tanda Tangan
        'tempat_ttd', 'tanggal_ttd', 'nama_penandatangan', 'jabatan_penandatangan',
        'nama_penerima_ttd',
        'status',
    ];

    protected $casts = [
        'periode' => 'date',
        'tanggal_ttd' => 'date',
        'tanggal_insentif_kpi' => 'date',
        'tanggal_lembur_reimburse' => 'date',
        'cutoff_lembur' => 'date',
    ];

    public function usaha(): BelongsTo
    {
        return $this->belongsTo(Usaha::class);
    }

    public function karyawan(): BelongsTo
    {
        return $this->belongsTo(Karyawan::class);
    }

    /**
     * Pendapatan Bruto = Upah Tetap (gaji_pokok) + Insentif KPI (insentif)
     */
    public function getPendapatanBrutoAttribute(): float
    {
        return (float) ($this->gaji_pokok + $this->insentif);
    }

    /**
     * Total Dana Masuk = Pendapatan Bruto + Lembur + Reimbursement
     */
    public function getTotalDanaMasukAttribute(): float
    {
        return (float) ($this->pendapatan_bruto + $this->lembur + $this->reimbursement);
    }

    /**
     * Total Pendapatan (backward compatible, mencakup tunjangan lama jika ada)
     */
    public function getTotalPendapatanAttribute(): float
    {
        return (float) (
            $this->total_dana_masuk
            + $this->tunjangan_jabatan
            + $this->tunjangan_transport
            + $this->tunjangan_makan
            + $this->tunjangan_komunikasi
            + $this->tunjangan_lainnya
            + $this->bonus
        );
    }

    /**
     * Total Potongan Karyawan
     */
    public function getTotalPotonganAttribute(): float
    {
        return (float) (
            $this->pph21
            + $this->bpjs_kesehatan
            + $this->bpjs_jht
            + $this->bpjs_jp
            + $this->potongan_lainnya
        );
    }

    /**
     * Bersih Diterima Karyawan (Take Home Pay) = Total Dana Masuk - Total Potongan
     */
    public function getTakeHomePayAttribute(): float
    {
        return (float) ($this->total_dana_masuk - $this->total_potongan);
    }

    /**
     * Total Kontribusi / Beban Perusahaan
     */
    public function getTotalBebanPerusahaanAttribute(): float
    {
        return (float) (
            $this->jht_perusahaan
            + $this->jkk_perusahaan
            + $this->jkm_perusahaan
            + $this->jp_perusahaan
            + $this->jkp_perusahaan
            + $this->bpjs_kesehatan_perusahaan
        );
    }
}