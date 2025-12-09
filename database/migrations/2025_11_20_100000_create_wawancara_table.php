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
        Schema::create('wawancara', function (Blueprint $table) {
            $table->id();
            $table->year('year');
            $table->foreignId('id_dinas')->constrained('dinas')->onDelete('cascade');
            $table->decimal('nilai_wawancara', 5, 2)->nullable();
            $table->text('catatan')->nullable();
            $table->enum('status', ['draft', 'finalized'])->default('draft');
            $table->boolean('is_finalized')->default(false);
            $table->timestamp('finalized_at')->nullable();
            $table->foreignId('finalized_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            
            // Unique constraint: satu dinas per tahun
            $table->unique(['year', 'id_dinas']);
            
            // Index untuk query
            $table->index(['year', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wawancara');
    }
};
