<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tahapan_penilaian_status', function (Blueprint $table) {
            $table->id();
            $table->year('year')->unique();
            $table->enum('tahap_aktif', [
                'submission',
                'penilaian_slhd',
                'penilaian_penghargaan',
                'validasi_1',
                'validasi_2',
                'wawancara',
                'selesai'
            ])->default('submission');
            $table->boolean('pengumuman_terbuka')->default(false);
            $table->text('keterangan')->nullable();
            $table->timestamp('tahap_mulai_at')->nullable();
            $table->timestamp('tahap_selesai_at')->nullable();
            $table->timestamps();

            $table->index(['year', 'tahap_aktif']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tahapan_penilaian_status');
    }
};
