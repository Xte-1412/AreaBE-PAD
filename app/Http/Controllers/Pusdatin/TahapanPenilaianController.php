<?php

namespace App\Http\Controllers\Pusdatin;

use App\Http\Controllers\Controller;
use App\Models\TahapanPenilaianStatus;
use App\Services\TahapanPenilaianService;
use Illuminate\Http\Request;

class TahapanPenilaianController extends Controller
{
    protected $tahapanService;

    public function __construct(TahapanPenilaianService $tahapanService)
    {
        $this->tahapanService = $tahapanService;
    }

    /**
     * Get status tahapan saat ini
     */
    public function index(Request $request, $year = null)
    {
        $year = $year ?? date('Y');
        $tahapan = $this->tahapanService->getStatusTahapan($year);

        return response()->json([
            'year' => $tahapan->year,
            'tahap_aktif' => $tahapan->tahap_aktif,
            'pengumuman_terbuka' => $tahapan->pengumuman_terbuka,
            'keterangan' => $tahapan->keterangan,
            'tahap_mulai_at' => $tahapan->tahap_mulai_at,
            'tahap_selesai_at' => $tahapan->tahap_selesai_at,
        ]);
    }

    /**
     * Toggle pengumuman (buka/tutup)
     */
    public function togglePengumuman(Request $request, $year)
    {
        $request->validate([
            'terbuka' => 'required|boolean',
            'keterangan' => 'nullable|string'
        ]);

        $success = $this->tahapanService->togglePengumuman(
            $year,
            $request->terbuka,
            $request->keterangan
        );

        if (!$success) {
            return response()->json([
                'message' => 'Tahapan penilaian untuk tahun ' . $year . ' tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'message' => 'Pengumuman berhasil ' . ($request->terbuka ? 'dibuka' : 'ditutup'),
            'data' => $this->tahapanService->getStatusTahapan($year)
        ]);
    }

    /**
     * Manual update tahap (jika diperlukan override)
     */
    public function updateTahap(Request $request, $year)
    {
        $request->validate([
            'tahap_aktif' => 'required|in:submission,penilaian_slhd,penilaian_penghargaan,validasi_1,validasi_2,selesai',
            'pengumuman_terbuka' => 'required|boolean',
            'keterangan' => 'nullable|string'
        ]);

        $tahapan = TahapanPenilaianStatus::where('year', $year)->first();

        if (!$tahapan) {
            return response()->json([
                'message' => 'Tahapan penilaian untuk tahun ' . $year . ' tidak ditemukan'
            ], 404);
        }

        $tahapan->update([
            'tahap_aktif' => $request->tahap_aktif,
            'pengumuman_terbuka' => $request->pengumuman_terbuka,
            'keterangan' => $request->keterangan,
            'tahap_mulai_at' => now()
        ]);

        return response()->json([
            'message' => 'Tahapan berhasil diupdate',
            'data' => $tahapan
        ]);
    }
}
