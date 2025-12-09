<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreatePenilaianTables extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'penilaian:create-tables {--force : Force the operation without confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create semua table penilaian dengan urutan yang benar';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!$this->option('force')) {
            if (!$this->confirm('Create semua table penilaian. Lanjutkan?')) {
                $this->info('Operation cancelled.');
                return 0;
            }
        }

        $this->info('🔧 Creating tables...');

        // Disable foreign key checks
        Schema::disableForeignKeyConstraints();

        try {
            // 1. Table penilaian_slhd
            $this->createPenilaianSLHD();
            
            // 2. Table penilaian_slhd_parsed
            $this->createPenilaianSLHDParsed();
            
            // 3. Table penilaian_penghargaan
            $this->createPenilaianPenghargaan();
            
            // 4. Table penilaian_penghargaan_parsed
            $this->createPenilaianPenghargaanParsed();
            
            // 5. Table validasi_1
            $this->createValidasi1();
            
            // 6. Table validasi_1_parseds
            $this->createValidasi1Parsed();
            
            // 7. Table validasi_2
            $this->createValidasi2();
            
            // 8. Table validasi_2_parsed
            $this->createValidasi2Parsed();
            
            // 9. Table rekap_penilaian
            $this->createRekapPenilaian();

            $this->newLine();
            $this->info('✅ All tables created successfully!');
            
        } catch (\Exception $e) {
            $this->error('❌ Error: ' . $e->getMessage());
            return 1;
        } finally {
            // Re-enable foreign key checks
            Schema::enableForeignKeyConstraints();
        }
        
        return 0;
    }

    private function createPenilaianSLHD()
    {
        if (Schema::hasTable('penilaian_slhd')) {
            $this->line('⊗ Table penilaian_slhd already exists');
            return;
        }

        Schema::create('penilaian_slhd', function ($table) {
            $table->id();
            $table->year('year');
            $table->enum('status', ['uploaded', 'parsing', 'parsed_ok','parsed_failed', 'finalized'])->default('uploaded');
            $table->foreignId('uploaded_by')->constrained('users')->onDelete('cascade');
            $table->string('file_path');
            $table->timestamp('uploaded_at')->nullable();
            $table->timestamp('finalized_at')->nullable();
            $table->boolean('is_finalized')->default(false);
            $table->text('catatan')->nullable();
            $table->text('error_messages')->nullable();
            $table->timestamps();
        });

        $this->line('✓ Created table: penilaian_slhd');
    }

    private function createPenilaianSLHDParsed()
    {
        if (Schema::hasTable('penilaian_slhd_parsed')) {
            $this->line('⊗ Table penilaian_slhd_parsed already exists');
            return;
        }

        Schema::create('penilaian_slhd_parsed', function ($table) {
            $table->id();
            $table->foreignId('penilaian_slhd_id')->constrained('penilaian_slhd')->onDelete('cascade');
            $table->integer('id_dinas');
            $table->string('nama_dinas')->default('tidak diketahui')->nullable();
            $table->unsignedTinyInteger('Bab_1')->nullable();
            $table->unsignedTinyInteger('Jumlah_Pemanfaatan_Pelayanan_Laboratorium')->nullable();
            $table->unsignedTinyInteger('Daya_Dukung_dan_Daya_Tampung_Lingkungan_Hidup')->nullable();
            $table->unsignedTinyInteger('Kajian_Lingkungan_Hidup_Strategis')->nullable();
            $table->unsignedTinyInteger('Keanekaragaman_Hayati')->nullable();
            $table->unsignedTinyInteger('Kualitas_Air')->nullable();
            $table->unsignedTinyInteger('Laut_Pesisir_dan_Pantai')->nullable();
            $table->unsignedTinyInteger('Kualitas_Udara')->nullable();
            $table->unsignedTinyInteger('Pengelolaan_Sampah_dan_Limbah')->nullable();
            $table->unsignedTinyInteger('Lahan_dan_Hutan')->nullable();
            $table->unsignedTinyInteger('Perubahan_Iklim')->nullable();
            $table->unsignedTinyInteger('Risiko_Bencana')->nullable();
            $table->unsignedTinyInteger('Penetapan_Isu_Prioritas')->nullable();
            $table->unsignedTinyInteger('Bab_3')->nullable();
            $table->unsignedTinyInteger('Bab_4')->nullable();
            $table->unsignedTinyInteger('Bab_5')->nullable();
            $table->float('Total_Skor')->nullable();
            $table->enum('status', ['parsed_ok', 'parsed_error', 'finalized'])->default('parsed_ok');
            $table->json('error_messages')->nullable();
            $table->timestamps();
        });

        $this->line('✓ Created table: penilaian_slhd_parsed');
    }

    private function createPenilaianPenghargaan()
    {
        if (Schema::hasTable('penilaian_penghargaan')) {
            $this->line('⊗ Table penilaian_penghargaan already exists');
            return;
        }

        Schema::create('penilaian_penghargaan', function ($table) {
            $table->id();
            $table->foreignId('penilaian_slhd_ref_id')->constrained('penilaian_slhd')->onDelete('cascade');
            $table->year('year');
            $table->enum('status', ['uploaded', 'parsing', 'parsed_ok','parsed_failed', 'finalized'])->default('uploaded');
            $table->foreignId('uploaded_by')->constrained('users')->onDelete('cascade');
            $table->string('file_path');
            $table->timestamp('uploaded_at')->nullable();
            $table->timestamp('finalized_at')->nullable();
            $table->boolean('is_finalized')->default(false);
            $table->text('catatan')->nullable();
            $table->text('error_messages')->nullable();
            $table->timestamps();
        });

        $this->line('✓ Created table: penilaian_penghargaan');
    }

    private function createPenilaianPenghargaanParsed()
    {
        if (Schema::hasTable('penilaian_penghargaan_parsed')) {
            $this->line('⊗ Table penilaian_penghargaan_parsed already exists');
            return;
        }

        Schema::create('penilaian_penghargaan_parsed', function ($table) {
            $table->id();
            $table->foreignId('penilaian_penghargaan_id')->constrained('penilaian_penghargaan')->onDelete('cascade');
            $table->integer('id_dinas');
            $table->string('nama_dinas')->default('tidak diketahui')->nullable();
            
            // Adipura
            $table->integer('Adipura_Jumlah_Wilayah')->nullable()->default(0);
            $table->integer('Adipura_Skor_Max')->nullable()->default(0);
            $table->integer('Adipura_Skor')->nullable()->default(0);
            
            // Adiwiyata
            $table->integer('Adiwiyata_Jumlah_Sekolah')->nullable()->default(0);
            $table->integer('Adiwiyata_Skor_Max')->nullable()->default(0);
            $table->integer('Adiwiyata_Skor')->nullable()->default(0);
            
            // Proklim
            $table->integer('Proklim_Jumlah_Desa')->nullable()->default(0);
            $table->integer('Proklim_Skor_Max')->nullable()->default(0);
            $table->integer('Proklim_Skor')->nullable()->default(0);
            
            // Proper
            $table->integer('Proper_Jumlah_Perusahaan')->nullable()->default(0);
            $table->integer('Proper_Skor_Max')->nullable()->default(0);
            $table->integer('Proper_Skor')->nullable()->default(0);
            
            // Kalpataru
            $table->integer('Kalpataru_Jumlah_Penerima')->nullable()->default(0);
            $table->integer('Kalpataru_Skor_Max')->nullable()->default(0);
            $table->integer('Kalpataru_Skor')->nullable()->default(0);
            
            // Persentase
            $table->float('Adipura_Persentase')->nullable();
            $table->float('Adiwiyata_Persentase')->nullable();
            $table->float('Proklim_Persentase')->nullable();
            $table->float('Proper_Persentase')->nullable();
            $table->float('Kalpataru_Persentase')->nullable();
            
            $table->float('Total_Skor')->nullable();
            $table->json('error_messages')->nullable();
            $table->enum('status', ['parsed_ok', 'parsed_error','finalized'])->default('parsed_ok');
            $table->timestamps();
        });

        $this->line('✓ Created table: penilaian_penghargaan_parsed');
    }

    private function createValidasi1()
    {
        if (Schema::hasTable('validasi_1')) {
            $this->line('⊗ Table validasi_1 already exists');
            return;
        }

        Schema::create('validasi_1', function ($table) {
            $table->id();
            $table->foreignId('penilaian_penghargaan_ref_id')->constrained('penilaian_penghargaan')->onDelete('cascade');
            $table->year('year');
            $table->enum('status', ['parsed_ok', 'finalized'])->default('parsed_ok');
            $table->boolean('is_finalized')->default(false);
            $table->timestamp('finalized_at')->nullable();
            $table->text('catatan')->nullable();
            $table->text('error_messages')->nullable();
            $table->foreignId('finalized_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

        $this->line('✓ Created table: validasi_1');
    }

    private function createValidasi1Parsed()
    {
        if (Schema::hasTable('validasi_1_parseds')) {
            $this->line('⊗ Table validasi_1_parseds already exists');
            return;
        }

        Schema::create('validasi_1_parseds', function ($table) {
            $table->id();
            $table->foreignId('validasi_1_id')->constrained('validasi_1')->onDelete('cascade');
            $table->integer('id_dinas');
            $table->string('nama_dinas')->default('tidak diketahui')->nullable();
            $table->float('Total_Skor')->nullable();
            $table->float('Nilai_IKLH')->nullable();
            $table->float('Nilai_Penghargaan')->nullable();
            $table->enum('status', ['parsed_ok', 'parsed_error', 'finalized'])->default('parsed_ok');
            $table->enum('status_result',['lulus','tidak_lulus'])->nullable();
            $table->json('error_messages')->nullable();
            $table->timestamps();
        });

        $this->line('✓ Created table: validasi_1_parseds');
    }

    private function createValidasi2()
    {
        if (Schema::hasTable('validasi_2')) {
            $this->line('⊗ Table validasi_2 already exists');
            return;
        }

        Schema::create('validasi_2', function ($table) {
            $table->id();
            $table->foreignId('validasi_1_id')->constrained('validasi_1')->onDelete('cascade');
            $table->year('year');
            $table->enum('status', ['parsed_ok', 'finalized'])->default('parsed_ok');
            $table->boolean('is_finalized')->default(false);
            $table->timestamp('finalized_at')->nullable();
            $table->text('catatan')->nullable();
            $table->text('error_messages')->nullable();
            $table->foreignId('finalized_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            
            $table->unique(['validasi_1_id', 'year']);
        });

        $this->line('✓ Created table: validasi_2');
    }

    private function createValidasi2Parsed()
    {
        if (Schema::hasTable('validasi_2_parsed')) {
            $this->line('⊗ Table validasi_2_parsed already exists');
            return;
        }

        Schema::create('validasi_2_parsed', function ($table) {
            $table->id();
            $table->foreignId('validasi_2_id')->constrained('validasi_2')->onDelete('cascade');
            $table->integer('id_dinas');
            $table->string('nama_dinas');
            
            // Copy nilai dari Validasi1
            $table->decimal('Nilai_Penghargaan', 10, 3)->nullable();
            $table->decimal('Nilai_IKLH', 10, 3)->nullable();
            $table->decimal('Total_Skor', 10, 3)->nullable();

            $table->boolean('Kriteria_WTP')->default(false)->comment('Kriteria penilaian WTP');
            $table->boolean('Kriteria_Kasus_Hukum')->default(false)->comment('Kriteria penilaian Kasus Hukum');
            $table->enum('status_validasi', ['pending', 'lolos', 'tidak_lolos'])->default('pending');
            
            $table->text('catatan')->nullable();
            $table->timestamps();
            
            $table->index(['validasi_2_id', 'id_dinas']);
        });

        $this->line('✓ Created table: validasi_2_parsed');
    }

    private function createRekapPenilaian()
    {
        if (Schema::hasTable('rekap_penilaian')) {
            $this->line('⊗ Table rekap_penilaian already exists');
            return;
        }

        Schema::create('rekap_penilaian', function ($table) {
            $table->id();
            $table->year('year');
            $table->integer('id_dinas');
            $table->string('nama_dinas');
            
            // Nilai dari setiap tahap
            $table->decimal('nilai_slhd', 10, 3)->nullable();
            $table->boolean('lolos_slhd')->default(false);
            
            $table->decimal('nilai_penghargaan', 10, 3)->nullable();
            $table->boolean('masuk_penghargaan')->default(false);
            
            $table->decimal('nilai_iklh', 10, 3)->nullable();
            $table->decimal('total_skor_validasi1', 10, 3)->nullable();
            $table->boolean('lolos_validasi1')->default(false);
            
            $table->boolean('kriteria_wtp')->nullable();
            $table->boolean('kriteria_kasus_hukum')->nullable();
            $table->boolean('lolos_validasi2')->default(false);
            
            // Peringkat final
            $table->integer('peringkat')->nullable();
            $table->enum('status_akhir', [
                'tidak_lolos_slhd', 
                'tidak_masuk_penghargaan', 
                'tidak_lolos_validasi1', 
                'tidak_lolos_validasi2', 
                'lolos_final'
            ])->default('tidak_lolos_slhd');
            
            $table->timestamps();
            
            $table->unique(['year', 'id_dinas']);
            $table->index(['year', 'status_akhir']);
            $table->index(['year', 'peringkat']);
        });

        $this->line('✓ Created table: rekap_penilaian');
    }
}
