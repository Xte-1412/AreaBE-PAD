<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Deadline;
use App\Models\Submission;
use App\Models\Pusdatin\PenilaianSLHD;
use App\Models\Pusdatin\PenilaianPenghargaan;
use App\Models\Pusdatin\Validasi1;
use App\Models\Pusdatin\Validasi2;
use App\Services\DocumentFinalizer;
use App\Services\TahapanPenilaianService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AutoFinalizeByDeadline extends Command
{
    protected $signature = 'deadline:auto-finalize';
    protected $description = 'Auto-finalize submissions and evaluations that passed deadline';

    protected $documentFinalizer;
    protected $tahapanService;

    public function __construct(DocumentFinalizer $documentFinalizer, TahapanPenilaianService $tahapanService)
    {
        parent::__construct();
        $this->documentFinalizer = $documentFinalizer;
        $this->tahapanService = $tahapanService;
    }

    public function handle()
    {
        $this->info('Checking for passed deadlines...');

        // Ambil semua deadline yang sudah lewat dan masih aktif
        $passedDeadlines = Deadline::active()
            ->passed()
            ->get();

        if ($passedDeadlines->isEmpty()) {
            $this->info('No passed deadlines found.');
            return 0;
        }

        foreach ($passedDeadlines as $deadline) {
            $this->info("Processing deadline for {$deadline->stage} - Year {$deadline->year}");

            try {
                switch ($deadline->stage) {
                    case 'submission':
                        $this->finalizeSubmissions($deadline->year);
                        break;
                    case 'penilaian_slhd':
                        $this->finalizePenilaianSLHD($deadline->year);
                        break;
                    case 'penilaian_penghargaan':
                        $this->finalizePenilaianPenghargaan($deadline->year);
                        break;
                    case 'validasi_1':
                        $this->finalizeValidasi1($deadline->year);
                        break;
                    case 'validasi_2':
                        $this->finalizeValidasi2($deadline->year);
                        break;
                    case 'wawancara':
                        $this->finalizeWawancara($deadline->year);
                        break;
                }

                // Nonaktifkan deadline setelah diproses
                $deadline->update(['is_active' => false]);
                
                // Update tahapan penilaian status - HANYA untuk submission (sisanya manual finalize)
                if ($deadline->stage === 'submission') {
                    // Cek tahap_aktif sebelum update untuk cegah race condition
                    $tahapanStatus = \App\Models\TahapanPenilaianStatus::where('year', $deadline->year)->first();
                    
                    if (!$tahapanStatus || $tahapanStatus->tahap_aktif === 'submission') {
                        $this->tahapanService->updateSetelahFinalize($deadline->stage, $deadline->year);
                        $this->line("  - Updated tahapan to penilaian_slhd");
                    } else {
                        $this->line("  - Skipped tahapan update (already at {$tahapanStatus->tahap_aktif})");
                    }
                }
                
                $this->info("✓ Completed {$deadline->stage} for year {$deadline->year}");
            } catch (\Exception $e) {
                $this->error("✗ Failed {$deadline->stage} for year {$deadline->year}: {$e->getMessage()}");
                Log::error("Auto-finalize failed for {$deadline->stage} - {$deadline->year}", [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        }

        $this->info('Auto-finalize process completed.');
        return 0;
    }

    private function finalizeSubmissions($year)
    {
        $submissions = Submission::where('tahun', $year)
            ->whereIn('status', ['draft', 'finalized']) // Ambil draft dan finalized (belum approved/rejected)
            ->with(['ringkasanEksekutif', 'laporanUtama', 'tabelUtama', 'iklh'])
            ->get();

        $count = 0;
        foreach ($submissions as $submission) {
            try {
                // Force finalize submission dan set status langsung ke 'approved'
                DB::beginTransaction();
                
                // Force finalize semua dokumen yang ada ke status approved
                if ($submission->ringkasanEksekutif) {
                    $this->documentFinalizer->forceFinalize($submission->ringkasanEksekutif, 'approved');
                }

                if ($submission->laporanUtama) {
                    $this->documentFinalizer->forceFinalize($submission->laporanUtama, 'approved');
                }

                if ($submission->tabelUtama && $submission->tabelUtama->isNotEmpty()) {
                    $this->documentFinalizer->forceFinalizeCollection($submission->tabelUtama, 'approved');
                }

                if ($submission->iklh) {
                    $this->documentFinalizer->forceFinalize($submission->iklh, 'approved');
                }

                // Update submission status jadi approved
                $submission->update(['status' => 'approved']);

                DB::commit();
                
                $count++;
                $this->line("  - Auto-approved submission for dinas ID: {$submission->id_dinas}");
            } catch (\Exception $e) {
                DB::rollBack();
                $this->warn("  - Failed to finalize submission ID {$submission->id}: {$e->getMessage()}");
            }
        }

        $this->info("  Total auto-approved: {$count} submissions");
    }

    private function finalizePenilaianSLHD($year)
    {
        // Cek apakah sudah ada yang finalized
        $alreadyFinalized = PenilaianSLHD::where([
            'year' => $year,
            'is_finalized' => true
        ])->exists();

        if ($alreadyFinalized) {
            $this->info("  Penilaian SLHD for year {$year} already has finalized batch - skipping");
            return;
        }

        // Ambil batch dengan parsed row terbanyak yang status parsed_ok
        $penilaian = PenilaianSLHD::where([
            'year' => $year,
            'status' => 'parsed_ok',
            'is_finalized' => false
        ])->orderBy('parsed_count', 'desc')
          ->first();

        if ($penilaian) {
            try {
                $penilaian->update([
                    'is_finalized' => true,
                    'finalized_at' => now(),
                ]);
                
                $this->line("  - Auto-finalized Penilaian SLHD (batch ID: {$penilaian->id}, rows: {$penilaian->parsed_count})");
            } catch (\Exception $e) {
                $this->warn("  - Failed to finalize Penilaian SLHD: {$e->getMessage()}");
            }
        } else {
            $this->warn("  No parsed_ok Penilaian SLHD found for year {$year}");
        }
    }

    private function finalizePenilaianPenghargaan($year)
    {
        // Cek apakah sudah ada yang finalized
        $alreadyFinalized = PenilaianPenghargaan::where([
            'year' => $year,
            'is_finalized' => true
        ])->exists();

        if ($alreadyFinalized) {
            $this->info("  Penilaian Penghargaan for year {$year} already has finalized batch - skipping");
            return;
        }

        // Ambil batch dengan parsed row terbanyak yang status parsed_ok
        $penilaian = PenilaianPenghargaan::where([
            'year' => $year,
            'status' => 'parsed_ok',
            'is_finalized' => false
        ])->orderBy('parsed_count', 'desc')
          ->first();

        if ($penilaian) {
            try {
                $penilaian->update([
                    'is_finalized' => true,
                    'finalized_at' => now(),
                ]);
                
                $this->line("  - Auto-finalized Penilaian Penghargaan (batch ID: {$penilaian->id}, rows: {$penilaian->parsed_count})");
            } catch (\Exception $e) {
                $this->warn("  - Failed to finalize Penilaian Penghargaan: {$e->getMessage()}");
            }
        } else {
            $this->warn("  No parsed_ok Penilaian Penghargaan found for year {$year}");
        }
    }

    private function finalizeValidasi1($year)
    {
        // Cek apakah sudah ada yang finalized
        $alreadyFinalized = Validasi1::where([
            'year' => $year,
            'is_finalized' => true
        ])->exists();

        if ($alreadyFinalized) {
            $this->info("  Validasi 1 for year {$year} already finalized - skipping");
            return;
        }

        $validasi = Validasi1::where([
            'year' => $year,
            'is_finalized' => false
        ])->first();

        if ($validasi) {
            try {
                // Force finalize tanpa cek status
                $validasi->update([
                    'is_finalized' => true,
                    'finalized_at' => now(),
                ]);
                
                $this->line("  - Force finalized Validasi 1 for year {$year}");
            } catch (\Exception $e) {
                $this->warn("  - Failed to finalize Validasi 1: {$e->getMessage()}");
            }
        } else {
            $this->info("  No Validasi 1 found for year {$year}");
        }
    }

    private function finalizeValidasi2($year)
    {
        // Cek apakah sudah ada yang finalized
        $alreadyFinalized = Validasi2::where([
            'year' => $year,
            'is_finalized' => true
        ])->exists();

        if ($alreadyFinalized) {
            $this->info("  Validasi 2 for year {$year} already finalized - skipping");
            return;
        }

        $validasi = Validasi2::where([
            'year' => $year,
            'is_finalized' => false
        ])->first();

        if ($validasi) {
            try {
                // Force finalize tanpa cek status
                $validasi->update([
                    'is_finalized' => true,
                    'finalized_at' => now(),
                ]);
                
                $this->line("  - Force finalized Validasi 2 for year {$year}");
            } catch (\Exception $e) {
                $this->warn("  - Failed to finalize Validasi 2: {$e->getMessage()}");
            }
        } else {
            $this->info("  No Validasi 2 found for year {$year}");
        }
    }
    
    private function finalizeWawancara($year)
    {
        // Cek apakah sudah ada yang finalized
        $alreadyFinalized = \App\Models\Pusdatin\Wawancara::where([
            'year' => $year,
            'is_finalized' => true
        ])->exists();

        if ($alreadyFinalized) {
            $this->info("  Wawancara for year {$year} already finalized - skipping");
            return;
        }

        $wawancaraList = \App\Models\Pusdatin\Wawancara::where([
            'year' => $year,
            'is_finalized' => false
        ])->get();

        if ($wawancaraList->isNotEmpty()) {
            try {
                // Force finalize semua wawancara
                \App\Models\Pusdatin\Wawancara::where('year', $year)->update([
                    'is_finalized' => true,
                    'status' => 'finalized',
                    'finalized_at' => now(),
                ]);
                
                // Update rekap penilaian (hitung total skor final)
                app(\App\Services\RekapPenilaianService::class)->updateFromWawancara($year);
                
                $this->line("  - Force finalized {$wawancaraList->count()} wawancara for year {$year}");
            } catch (\Exception $e) {
                $this->warn("  - Failed to finalize Wawancara: {$e->getMessage()}");
            }
        } else {
            $this->info("  No Wawancara found for year {$year}");
        }
    }
}
