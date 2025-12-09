<?php

namespace App\Services;

use App\Models\TahapanPenilaianStatus;
use Illuminate\Support\Facades\Log;

class TahapanPenilaianService
{
    /**
     * Update tahap setelah finalize
     */
    public function updateSetelahFinalize($stage, $year): void
    {
        try {
            $tahapanStatus = TahapanPenilaianStatus::firstOrCreate(
                ['year' => $year],
                [
                    'tahap_aktif' => 'submission',
                    'pengumuman_terbuka' => false,
                    'tahap_mulai_at' => now()
                ]
            );

            // Map stage deadline ke tahap berikutnya
            $mapping = [
                'submission' => [
                    'tahap' => 'penilaian_slhd',
                    'pengumuman' => false,
                    'keterangan' => 'Tahap submission selesai. Menunggu penilaian SLHD.'
                ],
                'penilaian_slhd' => [
                    'tahap' => 'penilaian_penghargaan',
                    'pengumuman' => true,
                    'keterangan' => 'Hasil penilaian SLHD sudah tersedia untuk dilihat.'
                ],
                'penilaian_penghargaan' => [
                    'tahap' => 'validasi_1',
                    'pengumuman' => true,
                    'keterangan' => 'Hasil penilaian penghargaan sudah tersedia untuk dilihat.'
                ],
                'validasi_1' => [
                    'tahap' => 'validasi_2',
                    'pengumuman' => true,
                    'keterangan' => 'Hasil validasi tahap 1 sudah tersedia untuk dilihat.'
                ],
                'validasi_2' => [
                    'tahap' => 'wawancara',
                    'pengumuman' => true,
                    'keterangan' => 'Hasil validasi tahap 2 sudah tersedia. Menunggu tahap wawancara.'
                ],
                'wawancara' => [
                    'tahap' => 'selesai',
                    'pengumuman' => true,
                    'keterangan' => 'Penilaian selesai. Hasil final sudah tersedia untuk dilihat.'
                ]
            ];

            if (isset($mapping[$stage])) {
                $config = $mapping[$stage];
                
                $tahapanStatus->update([
                    'tahap_aktif' => $config['tahap'],
                    'pengumuman_terbuka' => $config['pengumuman'],
                    'keterangan' => $config['keterangan'],
                    'tahap_mulai_at' => now(),
                    'tahap_selesai_at' => now()
                ]);

                Log::info("Tahapan penilaian diupdate", [
                    'year' => $year,
                    'stage' => $stage,
                    'tahap_baru' => $config['tahap'],
                    'pengumuman' => $config['pengumuman']
                ]);
            }
        } catch (\Exception $e) {
            Log::error("Gagal update tahapan penilaian setelah finalize", [
                'stage' => $stage,
                'year' => $year,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Update tahap setelah unfinalize (kembali ke tahap sebelumnya)
     */
    public function updateSetelahUnfinalize($stage, $year): void
    {
        try {
            $tahapanStatus = TahapanPenilaianStatus::where('year', $year)->first();

            if (!$tahapanStatus) {
                return;
            }

            // Map stage yang di-unfinalize ke tahap yang harus dikembalikan
            // Pengumuman mengikuti status tahap yang dikembalikan (tahap aktif kembali)
            $mapping = [
                'submission' => null, // Tidak ada tahap sebelumnya
                'penilaian_slhd' => [
                    'tahap' => 'submission',
                    'pengumuman' => false, // Submission belum ada pengumuman
                    'keterangan' => 'Penilaian SLHD dibatalkan. Kembali ke tahap submission.'
                ],
                'penilaian_penghargaan' => [
                    'tahap' => 'penilaian_slhd',
                    'pengumuman' => true, // SLHD aktif kembali, pengumuman tetap terbuka
                    'keterangan' => 'Penilaian penghargaan dibatalkan. Kembali ke tahap penilaian SLHD.'
                ],
                'validasi_1' => [
                    'tahap' => 'penilaian_penghargaan',
                    'pengumuman' => true, // Penghargaan aktif kembali, pengumuman tetap terbuka
                    'keterangan' => 'Validasi 1 dibatalkan. Kembali ke tahap penilaian penghargaan.'
                ],
                'validasi_2' => [
                    'tahap' => 'validasi_1',
                    'pengumuman' => true, // Validasi 1 aktif kembali, pengumuman tetap terbuka
                    'keterangan' => 'Validasi 2 dibatalkan. Kembali ke tahap validasi 1.'
                ],
                'wawancara' => [
                    'tahap' => 'validasi_2',
                    'pengumuman' => true, // Validasi 2 aktif kembali, pengumuman tetap terbuka
                    'keterangan' => 'Wawancara dibatalkan. Kembali ke tahap validasi 2.'
                ]
            ];
            
            if (isset($mapping[$stage])) {
                $config = $mapping[$stage];
                
                $tahapanStatus->update([
                    'tahap_aktif' => $config['tahap'],
                    'pengumuman_terbuka' => $config['pengumuman'], // Ikuti konfigurasi tahap yang dikembalikan
                    'keterangan' => $config['keterangan'],
                    'tahap_selesai_at' => null
                ]);

                Log::info("Tahapan penilaian dikembalikan setelah unfinalize", [
                    'year' => $year,
                    'stage' => $stage,
                    'tahap_dikembalikan' => $config['tahap'],
                    'pengumuman_terbuka' => $config['pengumuman']
                ]);
            }
        } catch (\Exception $e) {
            Log::error("Gagal update tahapan penilaian setelah unfinalize", [
                'stage' => $stage,
                'year' => $year,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Toggle pengumuman untuk tahap saat ini
     */
    public function togglePengumuman($year, $terbuka = true, $keterangan = null): bool
    {
        try {
            $tahapanStatus = TahapanPenilaianStatus::where('year', $year)->first();

            if (!$tahapanStatus) {
                return false;
            }

            $tahapanStatus->setPengumuman($terbuka, $keterangan);

            Log::info("Pengumuman toggled", [
                'year' => $year,
                'terbuka' => $terbuka,
                'tahap' => $tahapanStatus->tahap_aktif
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error("Gagal toggle pengumuman", [
                'year' => $year,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Get status tahapan untuk year tertentu
     */
    public function getStatusTahapan($year)
    {
        return TahapanPenilaianStatus::firstOrCreate(
            ['year' => $year],
            [
                'tahap_aktif' => 'submission',
                'pengumuman_terbuka' => false,
                'tahap_mulai_at' => now()
            ]
        );
    }
}
