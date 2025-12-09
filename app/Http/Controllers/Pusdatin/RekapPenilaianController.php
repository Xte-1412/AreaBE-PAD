<?php

namespace App\Http\Controllers\Pusdatin;

use App\Http\Controllers\Controller;
use App\Services\RekapPenilaianService;
use Illuminate\Http\Request;

class RekapPenilaianController extends Controller
{
    protected $rekapService;
    
    public function __construct(RekapPenilaianService $rekapService)
    {
        $this->rekapService = $rekapService;
    }
    
    /**
     * Get rekap penilaian untuk tahun tertentu
     */
    public function index(Request $request, $year)
    {
        $rekap = \App\Models\Pusdatin\RekapPenilaian::where('year', $year)
            ->with('dinas')
            ->orderBy('peringkat')
            ->get();
        
        if ($rekap->isEmpty()) {
            return response()->json([
                'message' => 'Belum ada rekap penilaian untuk tahun ' . $year
            ], 404);
        }
        
        return response()->json([
            'year' => $year,
            'total' => $rekap->count(),
            'data' => $rekap
        ], 200);
    }
    
    /**
     * Get rekap penilaian untuk satu dinas
     */
    public function show(Request $request, $year, $idDinas)
    {
        $rekap = $this->rekapService->getHistoryDinas($idDinas, $year);
        
        if (!$rekap) {
            return response()->json([
                'message' => 'Rekap penilaian untuk dinas ini belum tersedia'
            ], 404);
        }
        
        return response()->json([
            'year' => $year,
            'id_dinas' => $idDinas,
            'data' => $rekap
        ], 200);
    }
}
