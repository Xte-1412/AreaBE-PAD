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
        Schema::create('deadlines', function (Blueprint $table) {
            $table->id();
            $table->year('year');
            $table->enum('stage', [
                'submission',           // Upload dokumen dinas
                'penilaian_slhd',      // Penilaian SLHD
                'penilaian_penghargaan', // Penilaian Penghargaan
                'validasi_1',          // Validasi 1
                'validasi_2'           // Validasi 2
            ]);
            $table->dateTime('deadline_at');
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->text('catatan')->nullable();
            $table->timestamps();
            
            // Unique constraint: satu stage hanya bisa punya satu deadline aktif per tahun
            $table->unique(['year', 'stage', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deadlines');
    }
};
