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
use App\Models\Files\Lampiran;
use App\Helpers\MatraConstants;

class SimpleTestingDataSeeder extends Seeder
{
    /**
     * Seed data untuk testing: 2 dinas saja (1 provinsi + 1 kabupaten/kota)
     * - Bikin users untuk 2 dinas tersebut
     * - Bikin submissions + documents untuk 2 dinas
     */
    public function run(): void
    {
        $this->command->info('🚀 Starting Simple Testing Data Seeder...');
        
        DB::beginTransaction();
        try {
            // 1. Create admin & pusdatin users
            $this->command->info('👤 Creating admin & pusdatin users...');
            $this->seedAdminUsers();
            
            // 2. Create users untuk 2 dinas (1 provinsi + 1 kabupaten/kota)
            $this->command->info('👥 Creating users for 2 dinas...');
            $dinasIds = $this->seedDinasUsers();
            
            // 3. Seed Submissions & Documents untuk 2 dinas
            $this->command->info('📄 Creating submissions & documents...');
            $this->seedSubmissionsAndDocuments($dinasIds, 2026);
            
            DB::commit();
            
            $this->command->info('✅ Testing data seeded successfully!');
            $this->command->info("📊 Total users: " . User::count());
            $this->command->info("📊 Submissions created for 2 dinas");
            
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
            ['email' => 'admin2@test.com'],
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
     * Create users untuk 1 provinsi & 1 kabupaten/kota
     * 
     * @return array Array of dinas IDs
     */
    private function seedDinasUsers(): array
    {
        // Ambil 1 dinas tipe provinsi dan 1 tipe kabupaten/kota
        $provinsi = Dinas::whereHas('region', function($query) {
            $query->where('type', 'provinsi');
        })->first();
        
        $kabupaten = Dinas::whereHas('region', function($query) {
            $query->where('type', 'kabupaten/kota');
        })->first();
        
        if (!$provinsi) {
            throw new \Exception("No provinsi dinas found in database");
        }
        if (!$kabupaten) {
            throw new \Exception("No kabupaten/kota dinas found in database");
        }
        
        $dinasArray = [$provinsi, $kabupaten];
        
        foreach ($dinasArray as $index => $dinas) {
            $kodeDinas = str_pad($index + 1, 3, '0', STR_PAD_LEFT);
            
            User::firstOrCreate(
                ['email' => "dlh{$kodeDinas}@test.com"],
                [
                    'password' => Hash::make('password'),
                    'role' => $dinas->region->type,
                    'dinas_id' => $dinas->id,
                    'is_active' => true,
                ]
            );
            
            $this->command->info("✓ Created user for {$dinas->region->type}: dlh{$kodeDinas}@test.com");
        }
        
        return [$provinsi->id, $kabupaten->id];
    }
    
    /**
     * Seed Submissions & Documents untuk 2 dinas
     * 
     * @param array $dinasIds Array of dinas IDs
     * @param int $year Tahun submission
     */
    private function seedSubmissionsAndDocuments(array $dinasIds, int $year): void
    {
        $templateDisk = Storage::disk('templates');
        $dlhDisk = Storage::disk('dlh');
        
        // Source files path
        $sourcePdf = 'slhd/buku1/erd.pdf';
        $sourcePdf2 = 'slhd/buku2/erd.pdf';
        $sourcePdf3 = 'slhd/buku3/erd.pdf';

        // Validasi source PDF exist
        if (!$templateDisk->exists($sourcePdf)) {
            throw new \Exception("Source PDF not found: {$sourcePdf}");
        }
        
        // Scan semua file Excel di folder tabel_utama
        $tabelTemplates = [];
        $templateFiles = $templateDisk->files('tabel_utama');
        
        foreach ($templateFiles as $file) {
            $filename = basename($file);
            // Extract nomor dari nama file
            if (preg_match('/^0*(\d+)[\.\s]/', $filename, $matches)) {
                $nomor = (int) $matches[1];
                if ($nomor >= 1 && $nomor <= 80) {
                    $tabelTemplates[$nomor] = $file;
                }
            }
        }
        
        $this->command->info("📊 Found " . count($tabelTemplates) . " tabel templates");
        
        foreach ($dinasIds as $dinasId) {
            $dinas = Dinas::find($dinasId);
            
            // Create Submission
            $submission = Submission::create([
                'id_dinas' => $dinasId,
                'tahun' => $year,
                'status' => 'finalized',
            ]);
            
            $basePath = "uploads/{$year}/dlh_{$dinasId}";
            
            // 1. Ringkasan Eksekutif
            $ringkasanPath = "{$basePath}/ringkasan_eksekutif/ringkasan_{$dinasId}_{$year}.pdf";
            $dlhDisk->put($ringkasanPath, $templateDisk->get($sourcePdf));
            
            RingkasanEksekutif::create([
                'submission_id' => $submission->id,
                'path' => $ringkasanPath,
                'status' => 'finalized',
            ]);
            
            // 2. Laporan Utama
            $laporanPath = "{$basePath}/laporan_utama/laporan_{$dinasId}_{$year}.pdf";
            $dlhDisk->put($laporanPath, $templateDisk->get($sourcePdf2));
            
            LaporanUtama::create([
                'submission_id' => $submission->id,
                'path' => $laporanPath,
                'status' => 'finalized',
            ]);

            // 3. Lampiran
            $lampiranPath = "{$basePath}/lampiran/lampiran_{$dinasId}_{$year}.pdf";
            $dlhDisk->put($lampiranPath, $templateDisk->get($sourcePdf3));
            Lampiran::create([
                'submission_id' => $submission->id,
                'path' => $lampiranPath,
                'status' => 'finalized',
            ]);
            
            // 4. IKLH
            Iklh::create([
                'submission_id' => $submission->id,
                'status' => 'finalized',
                'indeks_kualitas_air' => rand(70, 100),
                'indeks_kualitas_udara' => rand(70, 100),
                'indeks_kualitas_lahan' => rand(70, 100),
                'indeks_kualitas_pesisir_laut' => rand(70, 100),
                'indeks_kualitas_kehati' => rand(70, 100),
            ]);
            
            // 5. Tabel Utama (80 files)
            $allKodeTabel = MatraConstants::getAllKodeTabel();
            
            foreach ($allKodeTabel as $kodeTabel) {
                $matra = MatraConstants::getMatraByKode($kodeTabel);
                $nomorTabel = MatraConstants::extractNomorTabel($kodeTabel);
                
                // Sanitize for file/folder names
                $matraSanitized = str_replace([' ', ',', '.', '(', ')'], '_', $matra);
                $kodeTabelSanitized = str_replace([' ', '||'], ['_', '-'], $kodeTabel);
                
                $tabelPath = "{$basePath}/tabel_utama/{$matraSanitized}/tabel_{$nomorTabel}_{$dinasId}_{$year}.xlsx";
                
                // Cek apakah ada template untuk nomor ini
                if (isset($tabelTemplates[$nomorTabel])) {
                    // Copy dari template yang sesuai
                    $dlhDisk->put($tabelPath, $templateDisk->get($tabelTemplates[$nomorTabel]));
                } else {
                    continue;
                }
                
                TabelUtama::create([
                    'submission_id' => $submission->id,
                    'kode_tabel' => $kodeTabel,
                    'path' => $tabelPath,
                    'matra' => $matra,
                    'status' => 'finalized',
                ]);
            }
            
            $this->command->info("✓ Created submission & documents for {$dinas->region->type}: {$dinas->nama}");
        }
    }
}
