<?php

namespace App\Http\Controllers\Pusdatin;

use App\Http\Controllers\Controller;
use App\Models\Submission;
use App\Services\ReviewService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReviewController extends Controller
{
    protected $reviewService;
    public function __construct(ReviewService $reviewService){
        $this->reviewService = $reviewService;
    }

    public function index(Request $request, $year = null){
        $year = empty($year) ? now()->year : $year;
        
        // Query dasar dengan relasi minimal untuk list view
        // Hanya tampilkan submission yang punya minimal 1 dokumen finalized/approved
        $query = Submission::with([
            'dinas:id,nama_dinas,kode_dinas,region_id',
            'dinas.region:id,nama_region,type,parent_id,kategori',
            'dinas.region.parent:id,nama_region,type',
            'ringkasanEksekutif:id,submission_id,status',
            'laporanUtama:id,submission_id,status',
            'iklh:id,submission_id,status'
        ])
        ->withCount([
            'tabelUtama',
            'tabelUtama as tabel_utama_finalized_count' => fn($q) => $q->where('status', 'finalized')
        ])
        ->where(['tahun'=> $year])
        ->where(function($q) {
            // Submission muncul jika ada minimal 1 dokumen yang finalized/approved
            $q->whereHas('ringkasanEksekutif', fn($sq) => $sq->whereIn('status', ['finalized', 'approved']))
              ->orWhereHas('laporanUtama', fn($sq) => $sq->whereIn('status', ['finalized', 'approved']))
              ->orWhereHas('iklh', fn($sq) => $sq->whereIn('status', ['finalized', 'approved']))
              ->orWhereHas('tabelUtama', fn($sq) => $sq->whereIn('status', ['finalized', 'approved']));
        });
        
        // Filter berdasarkan region_id (bisa provinsi atau kabupaten/kota)
        if ($request->has('region_id')) {
            $query->whereRelation('dinas', 'region_id', $request->region_id);
            // $query->whereHas('dinas', function($q) use ($request) {
            //     $q->where('region_id', $request->region_id);
            // });
        }
        
        // Filter berdasarkan provinsi (parent region)
        if ($request->has('provinsi_id')) {
            $query->whereRelation('dinas.region', 'parent_id', $request->provinsi_id)
      ->orWhereRelation('dinas.region', 'id', $request->provinsi_id);
        }
        
        // Filter berdasarkan status submission
        // if ($request->has('status')) {
        //     $query->where('status', $request->status);
        // }
        
        // Search berdasarkan nama dinas atau nama region
        // if ($request->has('search')) {
        //     $search = $request->search;
        //     $query->where(function($q) use ($search) {
        //         $q->whereHas('dinas', function($subQ) use ($search) {
        //             $subQ->where('nama_dinas', 'like', "%{$search}%")
        //                  ->orWhere('kode_dinas', 'like', "%{$search}%");
        //         })
        //         ->orWhereHas('dinas.region', function($subQ) use ($search) {
        //             $subQ->where('nama_region', 'like', "%{$search}%");
        //         });
        //     });
        // }
        
        // Pagination dengan per_page yang bisa dikustomisasi
        $perPage = $request->input('per_page', 15);
        $submissions = $query->paginate($perPage);
        
        // Transform response untuk format yang lebih clean
        $submissions->getCollection()->transform(function($submission) {
            $dinas = $submission->dinas;
            $region = $dinas?->region;
            
            // Tentukan provinsi dan kabupaten/kota berdasarkan type region
            $provinsi = null;
            $kabupatenKota = null;
            
            if ($region) {
                if ($region->type === 'provinsi') {
                    $provinsi = $region->nama_region;
                } else {
                    // Jika type kabupaten/kota, ambil parent sebagai provinsi
                    $kabupatenKota = $region->nama_region;
                    $provinsi = $region->parent?->nama_region;
                }
            }
            
            return [
                'submission_id' => $submission->id,
                'id' => $submission->id, // Backward compatibility
                'tahun' => $submission->tahun,
                'status' => $submission->status,
                'dinas' => $dinas ? [
                    'id' => $dinas->id,
                    'nama' => $dinas->nama_dinas,
                    'kode' => $dinas->kode_dinas,
                    'region_id' => $dinas->region_id,
                    'provinsi' => $provinsi,
                    'kabupaten_kota' => $kabupatenKota,
                    'tipologi' => $region?->kategori,
                ] : null,
                // Status dokumen untuk monitoring
                'buku_i' => $submission->ringkasanEksekutif ? $submission->ringkasanEksekutif->status : 'Belum Upload',
                'buku_ii' => $submission->laporanUtama ? $submission->laporanUtama->status : 'Belum Upload',
                'tabel_utama' => $submission->tabel_utama_count === 0 ? 'Belum Upload' :
                                ($submission->tabel_utama_finalized_count === $submission->tabel_utama_count ? 'finalized' : 'draft'),
                'iklh' => $submission->iklh ? $submission->iklh->status : 'Belum Upload',
                'created_at' => $submission->created_at,
                'updated_at' => $submission->updated_at,

            ];
        });
        
        return response()->json($submissions);
    }
    public function show(Submission $submission){
        // Hanya load dokumen yang sudah finalized/approved
        $submission->load([
            'ringkasanEksekutif' => fn($q) => $q->whereIn('status', ['finalized', 'approved']),
            'laporanUtama' => fn($q) => $q->whereIn('status', ['finalized', 'approved']),
            'tabelUtama' => fn($q) => $q->whereIn('status', ['finalized', 'approved']),
            'iklh' => fn($q) => $q->whereIn('status', ['finalized', 'approved'])
        ]);
        return response()->json($submission);
    }
    
    /**
     * Get detail Ringkasan Eksekutif untuk review
     */
    public function showRingkasanEksekutif(Submission $submission){
        $ringkasan = $submission->ringkasanEksekutif;
        
        if (!$ringkasan) {
            return response()->json([
                'message' => 'Ringkasan Eksekutif belum diupload untuk submission ini.'
            ], 404);
        }
        
        // Hanya dokumen yang finalized/approved bisa direview
        if (!in_array($ringkasan->status, ['finalized', 'approved'])) {
            return response()->json([
                'message' => 'Dokumen ini belum dapat direview karena masih dalam status draft.'
            ], 403);
        }
        
        // Metadata file lengkap
        $filePath = $ringkasan->path;
        $fileExists = Storage::exists($filePath);
        
        return response()->json([
            'submission_id' => $submission->id,
            'dinas' => [
                'nama' => $submission->dinas->nama_dinas ?? null,
                'jenis' => $submission->dinas->region->type ?? null
            ],
            'tahun' => $submission->tahun,
            'document' => [
                'id' => $ringkasan->id,
                'jenis_dokumen' => 'SLHD Buku I (Ringkasan Eksekutif)',
                'status' => $ringkasan->status,
                'catatan_admin' => $ringkasan->catatan_admin,
                'nama_file' => basename($filePath),
                'ukuran_file' => $fileExists ? round(Storage::size($filePath) / 1048576, 2) . ' MB' : null,
                'format_file' => strtoupper(pathinfo($filePath, PATHINFO_EXTENSION)),
                'tanggal_upload' => $ringkasan->created_at,
                'download_url' => $fileExists ? Storage::url($filePath) : null,
            ]
        ]);
    }
    
    /**
     * Get detail Laporan Utama untuk review
     */
    public function showLaporanUtama(Submission $submission){
        $laporan = $submission->laporanUtama;
        
        if (!$laporan) {
            return response()->json([
                'message' => 'Laporan Utama belum diupload untuk submission ini.'
            ], 404);
        }
        
        // Hanya dokumen yang finalized/approved bisa direview
        if (!in_array($laporan->status, ['finalized', 'approved'])) {
            return response()->json([
                'message' => 'Dokumen ini belum dapat direview karena masih dalam status draft.'
            ], 403);
        }
        
        // Metadata file lengkap
        $filePath = $laporan->path;
        $fileExists = Storage::exists($filePath);
        
        return response()->json([
            'submission_id' => $submission->id,
            'dinas' => [
                'nama' => $submission->dinas->nama_dinas ?? null,
                'jenis' => $submission->dinas->region->type ?? null
            ],
            'tahun' => $submission->tahun,
            'document' => [
                'id' => $laporan->id,
                'jenis_dokumen' => 'SLHD Buku II (Laporan Utama)',
                'status' => $laporan->status,
                'catatan_admin' => $laporan->catatan_admin,
                'nama_file' => basename($filePath),
                'ukuran_file' => $fileExists ? round(Storage::size($filePath) / 1048576, 2) . ' MB' : null,
                'format_file' => strtoupper(pathinfo($filePath, PATHINFO_EXTENSION)),
                'tanggal_upload' => $laporan->created_at,
                'download_url' => $fileExists ? Storage::url($filePath) : null,
            ]
        ]);
    }
    
    /**
     * Get detail Tabel Utama (collection) untuk review
     */
    public function showTabelUtama(Submission $submission){
        // Hanya load tabel yang finalized/approved
        $submission->load(['tabelUtama' => fn($q) => $q->whereIn('status', ['finalized', 'approved'])]);
        $tabelUtama = $submission->tabelUtama;
        
        if ($tabelUtama->isEmpty()) {
            return response()->json([
                'message' => 'Tabel Utama yang dapat direview belum tersedia untuk submission ini.'
            ], 404);
        }
        
        // Transform dengan metadata file
        $documents = $tabelUtama->map(function($tabel) {
            $filePath = $tabel->path;
            $fileExists = Storage::exists($filePath);
            
            return [
                'id' => $tabel->id,
                'kode_tabel' => $tabel->kode_tabel,
                'matra' => $tabel->matra,
                'status' => $tabel->status,
                'catatan_admin' => $tabel->catatan_admin,
                'nama_file' => basename($filePath),
                'ukuran_file' => $fileExists ? round(Storage::size($filePath) / 1048576, 2) . ' MB' : null,
                'format_file' => strtoupper(pathinfo($filePath, PATHINFO_EXTENSION)),
                'tanggal_upload' => $tabel->created_at,
                'download_url' => $fileExists ? Storage::url($filePath) : null,
            ];
        });
        
        return response()->json([
            'submission_id' => $submission->id,
            'dinas' => [
                'nama' => $submission->dinas->nama_dinas ?? null,
                'jenis' => $submission->dinas->region->type ?? null
            ],
            'tahun' => $submission->tahun,
            'total_tabel' => $tabelUtama->count(),
            'documents' => $documents
        ]);
    }
    
    /**
     * Get IKLH data untuk review (per submission)
     * Menampilkan dokumen IKLH yang sudah finalized
     */
    public function indexIKLH(Request $request, $year = null){
        $year = empty($year) ? now()->year : $year;
        
        $query = Submission::with([
            'dinas:id,nama_dinas,kode_dinas,region_id',
            'dinas.region:id,nama_region,type,parent_id,kategori',
            'dinas.region.parent:id,nama_region,type',
            'iklh' // load relasi iklh
        ])
        ->whereHas('iklh', function($q) {
            $q->where('status', 'finalized'); // hanya yang sudah finalized
        })
        ->where('tahun', $year);
        
        // Filter by provinsi
        if ($request->has('provinsi_id')) {
            $query->whereHas('dinas.region', function($q) use ($request) {
                $q->where('parent_id', $request->provinsi_id)
                  ->orWhere('id', $request->provinsi_id);
            });
        }
        
        // Filter by status dokumen IKLH
        if ($request->has('status')) {
            $query->whereHas('iklh', function($q) use ($request) {
                $q->where('status', $request->status);
            });
        }
        
        $perPage = $request->input('per_page', 15);
        $submissions = $query->paginate($perPage);
        
        $submissions->getCollection()->transform(function($submission) {
            $dinas = $submission->dinas;
            $region = $dinas?->region;
            $iklh = $submission->iklh;
            
            $provinsi = null;
            $kabupatenKota = null;
            $pembagiandaerah = null;
            
            if ($region) {
                if ($region->type === 'provinsi') {
                    $provinsi = $region->nama_region;
                    $pembagiandaerah = 'Provinsi';
                } else {
                    $kabupatenKota = $region->nama_region;
                    $provinsi = $region->parent?->nama_region;
                    $pembagiandaerah = $region->kategori;
                }
            }
            
            return [
                'id' => $submission->id,
                'provinsi' => $provinsi,
                'kabupaten_kota' => $kabupatenKota ?? '-',
                'jenis_dlh' => $pembagiandaerah,
                'tipologi' => $region?->kategori ?? 'Daratan',
                'indeks_kualitas_air' => $iklh?->indeks_kualitas_air,
                'indeks_kualitas_udara' => $iklh?->indeks_kualitas_udara,
                'indeks_kualitas_lahan' => $iklh?->indeks_kualitas_lahan,
                'indeks_kualitas_pesisir_laut' => $iklh?->indeks_kualitas_pesisir_laut,
                'indeks_kualitas_kehati' => $iklh?->indeks_kualitas_kehati,
                'status' => $iklh?->status,
                'catatan_admin' => $iklh?->catatan_admin,
            ];
        });
        
        return response()->json($submissions);
    }

    public function reviewDocument(Request $request, Submission $submission, $documentType){
        // Implementasi untuk review dokumen standalone berdasarkan tipe dokumen
        

        $submission->load($documentType);
        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
            'catatan_admin' => 'nullable|string|max:1000',
        ],[
            'status.required' => 'Status review harus diisi.',
            'status.in' => 'Status review harus berupa approved atau rejected.',
            'catatan_admin.max' => 'Catatan admin maksimal 1000 karakter.',
        ]);

        $result = $this->reviewService->evaluateDocument($submission, $documentType, $validated, $request->user()->id);
        return response()->json(['message'=>'Document reviewed successfully.','document'=>$result]);
    }
    
    /**
     * Review IKLH document untuk submission tertentu
     */
    public function reviewIKLH(Request $request, Submission $submission){
        return $this->reviewDocument($request, $submission, 'iklh');
    }



 
}
