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
        Schema::create('validasi_2_parsed', function (Blueprint $table) {
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
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('validasi_2_parsed');
    }
};
