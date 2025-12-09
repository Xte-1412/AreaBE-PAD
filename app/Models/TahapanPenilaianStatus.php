<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TahapanPenilaianStatus extends Model
{
    protected $table = 'tahapan_penilaian_status';
    protected $fillable = [
        'year',
        'tahap_aktif',
        'pengumuman_terbuka',
        'keterangan',
        'tahap_mulai_at',
        'tahap_selesai_at'
    ];

    protected $casts = [
        'year' => 'integer',
        'pengumuman_terbuka' => 'boolean',
        'tahap_mulai_at' => 'datetime',
        'tahap_selesai_at' => 'datetime'
    ];

    /**
     * Urutan tahapan penilaian
     */
    const URUTAN_TAHAP = [
        'submission',
        'penilaian_slhd',
        'penilaian_penghargaan',
        'validasi_1',
        'validasi_2',
        'wawancara',
        'selesai'
    ];

    /**
     * Cek apakah tahap tertentu sudah selesai
     */
    public function isTahapSelesai($tahap): bool
    {
        $indexAktif = array_search($this->tahap_aktif, self::URUTAN_TAHAP);
        $indexCheck = array_search($tahap, self::URUTAN_TAHAP);
        
        return $indexCheck !== false && $indexAktif !== false && $indexCheck < $indexAktif;
    }

    /**
     * Cek apakah pengumuman terbuka untuk tahap tertentu
     */
    public function isPengumumanTerbuka($tahap): bool
    {
        return $this->pengumuman_terbuka && $this->isTahapSelesai($tahap);
    }

    /**
     * Get tahap berikutnya
     */
    public function getTahapBerikutnya(): ?string
    {
        $indexAktif = array_search($this->tahap_aktif, self::URUTAN_TAHAP);
        
        if ($indexAktif === false || $indexAktif >= count(self::URUTAN_TAHAP) - 1) {
            return null;
        }
        
        return self::URUTAN_TAHAP[$indexAktif + 1];
    }

    /**
     * Get tahap sebelumnya
     */
    public function getTahapSebelumnya(): ?string
    {
        $indexAktif = array_search($this->tahap_aktif, self::URUTAN_TAHAP);
        
        if ($indexAktif === false || $indexAktif <= 0) {
            return null;
        }
        
        return self::URUTAN_TAHAP[$indexAktif - 1];
    }

    /**
     * Pindah ke tahap berikutnya
     */
    public function pindahKeTahapBerikutnya($bukaPengumuman = false, $keterangan = null): bool
    {
        $tahapBerikutnya = $this->getTahapBerikutnya();
        
        if (!$tahapBerikutnya) {
            return false;
        }

        $this->update([
            'tahap_aktif' => $tahapBerikutnya,
            'pengumuman_terbuka' => $bukaPengumuman,
            'keterangan' => $keterangan,
            'tahap_mulai_at' => now(),
            'tahap_selesai_at' => null
        ]);

        return true;
    }

    /**
     * Kembali ke tahap sebelumnya (untuk unfinalize)
     */
    public function kembaliKeTahapSebelumnya($keterangan = null): bool
    {
        $tahapSebelumnya = $this->getTahapSebelumnya();
        
        if (!$tahapSebelumnya) {
            return false;
        }

        $this->update([
            'tahap_aktif' => $tahapSebelumnya,
            'pengumuman_terbuka' => false,
            'keterangan' => $keterangan,
            'tahap_selesai_at' => null
        ]);

        return true;
    }

    /**
     * Set pengumuman terbuka/tutup
     */
    public function setPengumuman($terbuka, $keterangan = null): void
    {
        $this->update([
            'pengumuman_terbuka' => $terbuka,
            'keterangan' => $keterangan ?? $this->keterangan
        ]);
    }

    /**
     * Mark tahap selesai
     */
    public function markTahapSelesai(): void
    {
        $this->update([
            'tahap_selesai_at' => now()
        ]);
    }
}
