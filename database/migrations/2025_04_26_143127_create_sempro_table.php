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
        Schema::create('sempro', function (Blueprint $table) {
            $table->id('id_sempro');
            $table->foreignId('id_ajuan')->constrained('pengajuan_pembimbing','id_ajuan');
            $table->string('laporan_sempro')->nullable();
            $table->string('form_persetujuan_sempro')->nullable();
            $table->string('berita_acara_sempro')->nullable();
            $table->enum('status_laporan_ta_dospem1', ['Menunggu', 'Revisi', 'Disetujui'])->default('Menunggu');
            $table->enum('status_laporan_ta_dospem2', ['Menunggu', 'Revisi', 'Disetujui'])->default('Menunggu');
            $table->text('catatan_dospem1')->nullable();
            $table->text('catatan_dospem2')->nullable();
            $table->enum('status_pengajuan', ['Belum Diajukan', 'Diajukan'])->default('Belum Diajukan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sempro');
    }
};
