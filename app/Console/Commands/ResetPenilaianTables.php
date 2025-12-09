<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ResetPenilaianTables extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'penilaian:reset-tables {--force : Force the operation without confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Drop dan recreate semua table yang berkaitan dengan penilaian';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!$this->option('force')) {
            if (!$this->confirm('⚠️  Ini akan menghapus SEMUA data penilaian. Lanjutkan?')) {
                $this->info('Operation cancelled.');
                return 0;
            }
        }

        $this->info('🔄 Dropping tables...');

        // Disable foreign key checks
        Schema::disableForeignKeyConstraints();

        // Daftar table yang akan di-drop (urutan penting karena foreign key)
        $tables = [
            'rekap_penilaian',
            'validasi_2_parsed',
            'validasi_2',
            'validasi_1_parseds',
            'validasi_1',
            'penilaian_penghargaan_parsed',
            'penilaian_penghargaan',
            'penilaian_slhd_parsed',
            'penilaian_slhd__parseds', // Typo version
            'penilaian_slhd',
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                Schema::dropIfExists($table);
                $this->line("✓ Dropped table: {$table}");
            } else {
                $this->line("⊗ Table not found: {$table}");
            }
        }

        $this->info('✓ All tables dropped successfully!');
        $this->newLine();
        $this->info('🔧 Creating tables...');

        // Re-enable foreign key checks
        Schema::enableForeignKeyConstraints();

        // Run migrations untuk table penilaian DENGAN URUTAN YANG BENAR
        $migrations = [
            '2025_11_03_114148_create_penilaian_slhd_table.php',
            '2025_11_05_111113_create_penilaian_slhd__parseds_table.php',
            '2025_11_03_114304_create_penilaian_penghargaans_table.php',
            '2025_11_13_103531_create_penilaian_penghargaan__parseds_table.php',
            '2025_11_13_221216_create_validasi1s_table.php',
            '2025_11_13_220837_create_validasi1_parseds_table.php',
            '2025_11_18_000001_create_validasi_2_table.php',
            '2025_11_18_000002_create_validasi_2_parsed_table.php',
            '2025_11_18_000003_create_rekap_penilaian_table.php',
        ];

        foreach ($migrations as $migration) {
            $migrationPath = database_path("migrations/{$migration}");
            if (file_exists($migrationPath)) {
                $this->line("→ Running: {$migration}");
                $this->call('migrate', [
                    '--path' => "database/migrations/{$migration}",
                    '--force' => true
                ]);
            } else {
                $this->warn("⊗ Migration not found: {$migration}");
            }
        }

        $this->newLine();
        $this->info('✅ Tables reset successfully!');
        
        return 0;
    }
}
