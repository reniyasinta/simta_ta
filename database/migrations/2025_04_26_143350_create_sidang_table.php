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
    Schema::create('sidang', function (Blueprint $table) {
        $table->id('id_sidang');
        $table->unsignedBigInteger('id_mhs');
        $table->foreign('id_mhs')->references('id_mhs')->on('mahasiswa')->onDelete('cascade');
        $table->foreignId('id_dosen')->constrained('users')->onDelete('cascade');
        $table->foreignId('id_sempro')->constrained('sempro', 'id_sempro')->onDelete('cascade');
        // Laporan Draft
        $table->string('laporan_TA')->nullable();
        $table->enum('status_draft', ['Menunggu', 'Revisi', 'Disetujui'])->default('Menunggu');

        // Revisi Laporan
        $table->string('revisi_laporan')->nullable();
        $table->enum('status_revisi', ['Menunggu', 'Revisi', 'Disetujui'])->default('Menunggu');

        // Laporan Final
        $table->string('laporan_akhir')->nullable();
        $table->enum('status_final', ['Menunggu', 'Disetujui'])->default('Menunggu');

        $table->string('lembar_konsultasi')->nullable();
        $table->string('hasil_sidang')->nullable();        $table->text('catatan_dosen')->nullable();

        $table->timestamps();
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sidang');
    }
};
