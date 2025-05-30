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
        $table->string('revisi_laporan')->nullable();
        $table->string('laporan_akhir')->nullable();
        $table->string('lembar_konsultasi')->nullable();
        $table->string('hasil_sidang')->nullable();
        $table->enum('status', ['Menunggu', 'Revisi', 'Disetujui'])->default('Menunggu');
        $table->text('catatan_dosen')->nullable();

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
