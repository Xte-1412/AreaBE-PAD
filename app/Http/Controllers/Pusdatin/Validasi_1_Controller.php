<?php

namespace App\Http\Controllers\Pusdatin;

use App\Http\Controllers\Controller;
use App\Models\Pusdatin\PenilaianPenghargaan;
use App\Models\Pusdatin\Validasi1;
use App\Services\ValidasiService;
use Illuminate\Http\Request;

class Validasi_1_Controller extends Controller
{
    protected $validasiService;
    protected $tahapanService;

    public function __construct(ValidasiService $validasiService, \App\Services\TahapanPenilaianService $tahapanService)
    {
        $this->validasiService = $validasiService;
        $this->tahapanService = $tahapanService;
    }
    
    public function index(Request $request, $year)
    {
         $penilaian = PenilaianPenghargaan::where([
        'year' => $year,
        'status' => 'finalized'
    ])->first();

    if (!$penilaian) {
        return response()->json([
            'message' => 'Belum ada Penilaian Penghargaan yang sudah difinalisasi'
        ], 404);
    }
    $validasi1 = $penilaian->Validasi1()->first();

    if (!$validasi1) {
        return response()->json([
            'message' => 'Validasi 1 belum dibuat untuk penilaian ini'
        ], 404);
    }

    // Ambil hasil parsed validasi-1
    $data = $validasi1->Validasi1Parsed()->get();

    return response()->json($data, 200);
    }

    public function finalize(Request $request,$year){
        $validasi1=Validasi1::where('year',$year)->first();
        if(!$validasi1){
            return response()->json([
                'message'=>'Validasi 1 untuk tahun '.$year.' tidak ditemukan'
            ],404); 
        }

        // Safety check: Validasi1 sudah finalized
        if ($validasi1->is_finalized) {
            return response()->json([
                'message'=>'Validasi 1 untuk tahun '.$year.' sudah difinalisasi'
            ],400);
        }

        $validasi1->update([
            'status'=>'finalized',
            'is_finalized'=>true,
            'finalized_at'=>now(),
            'finalized_by'=>$request->user()->id
        ]);
        
        // Update rekap penilaian
        app(\App\Services\RekapPenilaianService::class)->updateFromValidasi1($validasi1);
        
        // Update tahapan penilaian status
        $this->tahapanService->updateSetelahFinalize('validasi_1', $validasi1->year);
         
        $this->validasiService->CreateValidasi2($validasi1);
        return response()->json([
            'message'=>'Validasi 1 untuk tahun '.$year.' berhasil difinalisasi'
        ],200);
    }


    public function unfinalize(Request $request, $year)
    {
        $validasi1 = Validasi1::where('year', $year)->first();
        
        if (!$validasi1) {
            return response()->json([
                'message' => 'Validasi 1 untuk tahun ' . $year . ' tidak ditemukan'
            ], 404);
        }
        
        if (!$validasi1->is_finalized) {
            return response()->json([
                'message' => 'Validasi 1 untuk tahun ' . $year . ' belum difinalisasi'
            ], 400);
        }
        
        $validasi1->update([
            'status' => 'parsed_ok',
            'is_finalized' => false,
            'finalized_at' => null,
            'finalized_by' => null,
            'catatan' => $request->catatan ?? null
        ]);
        
        // Update tahapan penilaian status (kembali ke tahap sebelumnya)
        $this->tahapanService->updateSetelahUnfinalize('validasi_1', $validasi1->year);
        
        return response()->json([
            'message' => 'Validasi 1 untuk tahun ' . $year . ' berhasil dibuka finalisasinya.'
        ], 200);
    }
    
}

 