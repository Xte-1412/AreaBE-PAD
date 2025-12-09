<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\Region;
use App\Models\Dinas;
use App\Models\User;
use App\Models\Submission;
use App\Models\Files\RingkasanEksekutif;
use App\Models\Files\LaporanUtama;
use App\Models\Files\TabelUtama;
use App\Models\Files\Iklh;

class TestingDataSeeder extends Seeder
{
    /**
     * Seed data untuk testing: menggunakan dinas existing (576 dinas)
     * - Bikin users untuk semua dinas
     * - Bikin submissions + documents untuk N dinas (configurable)
     */
    public function run(): void
    {
        $this->command->info('🚀 Starting Testing Data Seeder...');
        
        DB::beginTransaction();
        try {
            // 1. Create admin & pusdatin users
            $this->command->info('👤 Creating admin & pusdatin users...');
            $this->seedAdminUsers();
            
            // 2. Create users untuk semua dinas existing
            $this->command->info('👥 Creating users for all dinas...');
            $this->seedDinasUsers();
            
            // 3. Seed Submissions & Documents untuk N dinas
            $this->command->info('📄 Creating submissions & documents...');
            $dinasCount = $this->seedSubmissionsAndDocuments(77, 2025); // 77 dinas pertama
            
            DB::commit();
            
            $this->command->info('✅ Testing data seeded successfully!');
            $this->command->info("📊 Total users: " . User::count());
            $this->command->info("📊 Submissions created for {$dinasCount} dinas");
            
        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('❌ Seeding failed: ' . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Create admin & pusdatin users
     */
    private function seedAdminUsers(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@test.com'],
            [
                'password' => Hash::make('password'),
                'role' => 'admin',
                'is_active' => true,
            ]
        );

        User::firstOrCreate(
            ['email' => 'pusdatin@test.com'],
            [
                'password' => Hash::make('password'),
                'role' => 'pusdatin',
                'is_active' => true,
            ]
        );
    }
    
    /**
     * Create users untuk semua dinas existing
     */
    private function seedDinasUsers(): void
    {
        $allDinas = Dinas::with('region')->get();
        
        $progressBar = $this->command->getOutput()->createProgressBar($allDinas->count());
        $progressBar->start();
        
        foreach ($allDinas as $index => $dinas) {
            $kodeDinas = str_pad($index + 1, 3, '0', STR_PAD_LEFT);
            
            User::firstOrCreate(
                ['email' => "dlh{$kodeDinas}@test.com"],
                [
                    'password' => Hash::make('password'),
                    'role' => $dinas->region->type, // 'provinsi' or 'kabupaten/kota'
                    'dinas_id' => $dinas->id,
                    'is_active' => true,
                ]
            );
            
            $progressBar->advance();
        }
        
        $progressBar->finish();
        $this->command->newLine();
    }
    
    /**
     * Seed Submissions & Documents untuk N dinas pertama
     * 
     * @param int $count Jumlah dinas yang akan dibuatkan submission
     * @param int $year Tahun submission
     * @return int Jumlah dinas yang berhasil dibuatkan submission
     */
    private function seedSubmissionsAndDocuments(int $count, int $year): int
    {
        $templateDisk = Storage::disk('templates');
        $dlhDisk = Storage::disk('dlh');
        
        // Source files path (relative dari disk 'templates')
        $sourcePdf = 'slhd/buku1/erd.pdf';
        $sourceExcel = 'tabel_utama/Keanekaragaman_Hayati/Tabel_9.xlsx';
        
        // Validasi source files exist
        if (!$templateDisk->exists($sourcePdf)) {
            throw new \Exception("Source PDF not found: {$sourcePdf}");
        }
        if (!$templateDisk->exists($sourceExcel)) {
            throw new \Exception("Source Excel not found: {$sourceExcel}");
        }
        
        // Ambil N dinas pertama (ordered by id)
        $dinasIds = Dinas::orderBy('id')->limit($count)->pluck('id');
        
        $progressBar = $this->command->getOutput()->createProgressBar($dinasIds->count());
        $progressBar->start();
        
        foreach ($dinasIds as $dinasId) {
            // Create Submission
            $submission = Submission::create([
                'id_dinas' => $dinasId,
                'tahun' => $year,
                'status' => 'finalized', // Auto-finalized untuk testing
            ]);
            
            $basePath = "uploads/{$year}/dlh_{$dinasId}";
            
            // 1. Ringkasan Eksekutif (copy PDF dari templates ke dlh)
            $ringkasanPath = "{$basePath}/ringkasan_eksekutif/ringkasan_{$dinasId}_{$year}.pdf";
            $dlhDisk->put($ringkasanPath, $templateDisk->get($sourcePdf));
            
            RingkasanEksekutif::create([
                'submission_id' => $submission->id,
                'path' => $ringkasanPath,
                'status' => 'finalized',
            ]);
            
            // 2. Laporan Utama (copy PDF dari templates ke dlh)
            $laporanPath = "{$basePath}/laporan_utama/laporan_{$dinasId}_{$year}.pdf";
            $dlhDisk->put($laporanPath, $templateDisk->get($sourcePdf));
            
            LaporanUtama::create([
                'submission_id' => $submission->id,
                'path' => $laporanPath,
                'status' => 'finalized',
            ]);
            
            // 3. IKLH (TIDAK PERLU FILE, hanya indeks)
            Iklh::create([
                'submission_id' => $submission->id,
                'status' => 'finalized',
                'indeks_kualitas_air' => rand(50, 100),
                'indeks_kualitas_udara' => rand(50, 100),
                'indeks_kualitas_lahan' => rand(50, 100),
                'indeks_kualitas_pesisir_laut' => rand(50, 100),
                'indeks_kualitas_kehati' => rand(50, 100),
            ]);
            
            // 4. Tabel Utama (78 files, copy Excel dari templates ke dlh)
            // Enum values from migration (must match exactly)
            $matras = [
                'Keanekaragaman Hayati',
                'Kualitas Air',
                'Laut, Pesisir, dan Pantai',
                'Kualitas Udara',
                'Lahan dan Hutan'
            ];
            
            for ($i = 1; $i <= 78; $i++) {
                $kodeTabel = "Tabel {$i}";
                $tabelPath = "{$basePath}/tabel_utama/tabel_{$i}_{$dinasId}_{$year}.xlsx";
                
                $dlhDisk->put($tabelPath, $templateDisk->get($sourceExcel));
                
                TabelUtama::create([
                    'submission_id' => $submission->id,
                    'kode_tabel' => $kodeTabel,
                    'path' => $tabelPath,
                    'matra' => $matras[$i % 5],
                    'status' => 'finalized',
                ]);
            }
            
            $progressBar->advance();
        }
        
        $progressBar->finish();
        $this->command->newLine();
        
        return $dinasIds->count();
    }
}
