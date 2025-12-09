<?php

namespace App\Http\Controllers\Dinas;

use App\Http\Controllers\Controller;
use App\Models\TahapanPenilaianStatus;
use Illuminate\Http\Request;
use App\Models\Pusdatin\RekapPenilaian;

class PengumumanController extends Controller
{
    /**
     * Get timeline progres penilaian (untuk progress bar)
     */
    public function timeline(Request $request, $year = null)
    {
        $dinas = $request->user()->dinas;
        $year = $year ?? date('Y');
        
        // Cek tahap aktif saat ini (auto-create jika belum ada, default submission)
        $tahapan = TahapanPenilaianStatus::firstOrCreate(
            ['year' => $year],
            [
                'tahap_aktif' => 'submission',
                'pengumuman_terbuka' => false,
                'tahap_mulai_at' => now(),
                'keterangan' => 'Tahap upload dokumen sedang berlangsung.'
            ]
        );
        
        // Ambil rekap penilaian dinas
        // $rekap = RekapPenilaian::where([
        //     'year' => $year,
        //     'id_dinas' => $dinas->id
        // ])->first();
        
        $tahapAktif = $tahapan->tahap_aktif;
        $urutanTahap = TahapanPenilaianStatus::URUTAN_TAHAP;
        $indexAktif = array_search($tahapAktif, $urutanTahap);
        
        $timeline = [];
        
        // Mapping nama tahap untuk display
        $namaTahap = [
            'submission' => 'Upload Dokumen',
            'penilaian_slhd' => 'Penilaian SLHD',
            'penilaian_penghargaan' => 'Penentuan Bobot Antar Penghargaan',
            'validasi_1' => 'Validasi 1',
            'validasi_2' => 'Validasi 2',
            'wawancara' => 'Wawancara'
        ];
        
        foreach ($urutanTahap as $index => $tahap) {
            $status = 'pending'; // Default: belum sampai
            $keterangan = 'Menunggu';
            
            if ($index < $indexAktif) {
                // Tahap sudah selesai
                $status = 'completed';
                $keterangan = 'Selesai';
            } elseif ($index === $indexAktif) {
                // Tahap sedang aktif
                $status = 'active';
                $keterangan = 'Sedang Berlangsung';
            }
            
            // Skip tahap 'selesai' untuk timeline (karena itu status akhir, bukan tahap visual)
            if ($tahap === 'selesai') {
                continue;
            }
            
            $timeline[] = [
                'tahap' => $tahap,
                'nama' => $namaTahap[$tahap] ?? $tahap,
                'status' => $status,
                'keterangan' => $keterangan
            ];
        }
        
        // Tambahkan tahap final jika sudah selesai semua
        if ($tahapAktif === 'selesai') {
            $timeline[] = [
                'tahap' => 'selesai',
                'nama' => 'Perhitungan NT Final',
                'status' => 'completed',
                'keterangan' => 'Selesai'
            ];
        } else {
            $timeline[] = [
                'tahap' => 'selesai',
                'nama' => 'Perhitungan NT Final',
                'status' => 'pending',
                'keterangan' => 'Menunggu'
            ];
        }
        
        return response()->json([
            'year' => $year,
            'tahap_aktif' => $tahapAktif,
            'pengumuman_terbuka' => $tahapan->pengumuman_terbuka,
            'keterangan' => $tahapan->keterangan,
            'timeline' => $timeline
        ]);
    }
    
    /**
     * Lihat hasil pengumuman untuk tahap tertentu (dipanggil saat user klik tahap di timeline)
     */
    public function show(Request $request, $year, $tahap)
    {
        $dinas = $request->user()->dinas;
        
        // Cek status tahapan penilaian
        $tahapan = TahapanPenilaianStatus::where('year', $year)->first();
        
        if (!$tahapan) {
            return response()->json([
                'message' => 'Belum ada penilaian untuk tahun ini'
            ], 404);
        }
        
        // Ambil rekap penilaian dinas
        $rekap = RekapPenilaian::where([
            'year' => $year,
            'id_dinas' => $dinas->id
        ])->first();
        
        // if (!$rekap) {
        //     return response()->json([
        //         'message' => 'Dinas Anda belum terdaftar dalam penilaian tahun ini'
        //     ], 404);
        // }
        
        // Tahap yang diminta dari parameter
        $tahapDiminta = $tahap;
        
        // Validasi tahap yang diminta
        $urutanTahap = TahapanPenilaianStatus::URUTAN_TAHAP;
        if (!in_array($tahapDiminta, $urutanTahap)) {
            return response()->json([
                'message' => 'Tahap tidak valid'
            ], 400);
        }
        
        // Cek apakah tahap sudah selesai (sudah ada pengumuman)
        $indexDiminta = array_search($tahapDiminta, $urutanTahap);
        $indexAktif = array_search($tahapan->tahap_aktif, $urutanTahap);
        
        // Tahap belum dimulai (masih di depan tahap aktif)
        if ($indexDiminta > $indexAktif) {
            return response()->json([
                'message' => 'Tahap ini belum dimulai',
                'tahap_diminta' => $tahapDiminta,
                'tahap_aktif' => $tahapan->tahap_aktif,
                'pengumuman_tersedia' => false
            ]);
        }
        
        // Cek pengumuman terbuka (berlaku untuk tahap aktif dan tahap yang sudah selesai)
        // Pengumuman bisa ditutup manual via toggle meskipun tahap sudah selesai
        if (!$tahapan->pengumuman_terbuka) {
            return response()->json([
                'message' => 'Pengumuman untuk tahap ini belum dibuka atau sedang ditutup sementara',
                'tahap_diminta' => $tahapDiminta,
                'tahap_aktif' => $tahapan->tahap_aktif,
                'pengumuman_tersedia' => false,
                'keterangan' => $tahapan->keterangan
            ]);
        }
        
        // Tahap sudah selesai ATAU tahap aktif dengan pengumuman terbuka
        // Generate dan return hasil
        $hasil = $this->generateHasilByTahap($tahapDiminta, $rekap);
        
        return response()->json([
            'tahap' => $tahapDiminta,
            'pengumuman_tersedia' => true,
            'hasil' => $hasil
        ]);
    }
    
    /**
     * Generate hasil pengumuman berdasarkan tahap
     */
    private function generateHasilByTahap($tahap, $rekap)
    {
        switch ($tahap) {
            case 'submission':
                return [
                    'tahap_diumumkan' => 'Upload Dokumen',
                    'status' => 'SELESAI',
                    'keterangan' => 'Dokumen submission Anda telah diterima dan sedang menunggu proses penilaian SLHD.'
                ];
                
            case 'penilaian_slhd':
                return [
                    'tahap_diumumkan' => 'Penilaian SLHD',
                    'nilai_slhd' => $rekap->nilai_slhd,
                    'status' => $rekap->lolos_slhd ? 'LOLOS' : 'TIDAK LOLOS',
                    'keterangan' => $rekap->lolos_slhd 
                        ? 'Selamat! Anda lolos tahap penilaian SLHD dan berhak mengikuti penilaian penghargaan.'
                        : 'Mohon maaf, nilai SLHD Anda belum memenuhi syarat untuk melanjutkan ke tahap berikutnya.'
                ];
                
            case 'penilaian_penghargaan':
                return [
                    'tahap_diumumkan' => 'Penilaian Penghargaan',
                    'nilai_slhd' => $rekap->nilai_slhd,
                    'nilai_penghargaan' => $rekap->nilai_penghargaan,
                    'status' => $rekap->masuk_penghargaan ? 'MASUK KATEGORI' : 'TIDAK MASUK',
                    'keterangan' => $rekap->masuk_penghargaan
                        ? 'Selamat! Anda masuk dalam kategori penilaian penghargaan.'
                        : 'Mohon maaf, nilai penghargaan Anda belum memenuhi syarat untuk melanjutkan ke validasi.'
                ];
                
            case 'validasi_1':
                return [
                    'tahap_diumumkan' => 'Validasi Tahap 1',
                    'nilai_penghargaan' => $rekap->nilai_penghargaan,
                    'nilai_iklh' => $rekap->nilai_iklh,
                    'total_skor' => $rekap->total_skor_validasi1,
                    'status' => $rekap->lolos_validasi1 ? 'LOLOS' : 'TIDAK LOLOS',
                    'keterangan' => $rekap->lolos_validasi1
                        ? 'Selamat! Anda lolos validasi tahap 1 dan akan diproses ke validasi tahap 2.'
                        : 'Mohon maaf, total skor Anda belum memenuhi syarat untuk melanjutkan ke validasi tahap 2.'
                ];
                
            case 'validasi_2':
                return [
                    'tahap_diumumkan' => 'Validasi Tahap 2',
                    'total_skor' => $rekap->total_skor_validasi1,
                    'kriteria_wtp' => $rekap->kriteria_wtp ? 'Memenuhi' : 'Tidak Memenuhi',
                    'kriteria_kasus_hukum' => $rekap->kriteria_kasus_hukum ? 'Memenuhi' : 'Tidak Memenuhi',
                    'status' => $rekap->lolos_validasi2 ? 'LOLOS' : 'TIDAK LOLOS',
                    'peringkat' => $rekap->peringkat,
                    'keterangan' => $rekap->lolos_validasi2
                        ? "Selamat! Anda lolos validasi tahap 2 dengan peringkat ke-{$rekap->peringkat} dan akan mengikuti tahap wawancara."
                        : 'Mohon maaf, Anda tidak lolos validasi tahap 2.'
                ];
                
            case 'wawancara':
                // Tahap wawancara sedang berlangsung, belum tentu semua dinas dapat nilai
                if ($rekap->nilai_wawancara === null) {
                    return [
                        'tahap_diumumkan' => 'Wawancara',
                        'status' => 'MENUNGGU',
                        'keterangan' => 'Anda lolos validasi tahap 2 dan masuk dalam daftar wawancara. Menunggu jadwal wawancara.'
                    ];
                }
                
                return [
                    'tahap_diumumkan' => 'Wawancara',
                    'nilai_wawancara' => $rekap->nilai_wawancara,
                    'status' => 'SELESAI WAWANCARA',
                    'keterangan' => 'Wawancara Anda telah selesai. Menunggu perhitungan NT Final.'
                ];
                
            case 'selesai':
                // Tahap selesai, semua sudah finalized
                if (!$rekap->lolos_wawancara || $rekap->total_skor_final === null) {
                    return [
                        'tahap_diumumkan' => 'Hasil Final',
                        'status' => 'TIDAK LOLOS',
                        'keterangan' => 'Mohon maaf, Anda tidak masuk dalam daftar wawancara atau tidak lolos tahap wawancara.'
                    ];
                }
                
                return [
                    'tahap_diumumkan' => 'Hasil Final',
                    'nilai_slhd' => $rekap->nilai_slhd,
                    'nilai_wawancara' => $rekap->nilai_wawancara,
                    'total_skor_final' => $rekap->total_skor_final,
                    'peringkat_final' => $rekap->peringkat_final,
                    'status' => 'LOLOS FINAL',
                    'keterangan' => "Selamat! Anda lolos semua tahap penilaian dengan peringkat final ke-{$rekap->peringkat_final}. Total skor final: {$rekap->total_skor_final} (90% SLHD + 10% Wawancara)."
                ];
                
            default:
                return null;
        }
    }
}