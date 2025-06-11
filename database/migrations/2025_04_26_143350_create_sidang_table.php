<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sidang', function (Blueprint $table) {
            $table->id('id_sidang');

            // Relasi Mahasiswa
            $table->unsignedBigInteger('id_kelompok');
            $table->foreign('id_kelompok')->references('id_kelompok')->on('kelompok')->onDelete('cascade');

            // Dosen Pembimbing
            $table->foreignId('id_dosen1')->constrained('users', 'id')->onDelete('cascade');
            $table->foreignId('id_dosen2')->nullable()->constrained('users', 'id')->onDelete('set null');

            // Dosen Penguji
            $table->foreignId('penguji_1_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('penguji_2_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('penguji_3_id')->nullable()->constrained('users')->onDelete('set null');

            // Relasi Sempro
            $table->foreignId('id_sempro')->constrained('sempro', 'id_sempro')->onDelete('cascade');

            // Laporan Draft
            $table->string('laporan_TA')->nullable();
            $table->enum('status_draft_dosen1', ['Menunggu', 'Revisi', 'Disetujui'])->default('Menunggu');
            $table->enum('status_draft_dosen2', ['Menunggu', 'Revisi', 'Disetujui'])->default('Menunggu');

            // Revisi Laporan per Penguji
            $table->string('revisi_laporan')->nullable();
            $table->enum('status_revisi_penguji_1', ['Menunggu', 'Revisi', 'Disetujui'])->default('Menunggu');
            $table->enum('status_revisi_penguji_2', ['Menunggu', 'Revisi', 'Disetujui'])->default('Menunggu');
            $table->enum('status_revisi_penguji_3', ['Menunggu', 'Revisi', 'Disetujui'])->default('Menunggu');

            // Laporan Final
            $table->string('laporan_akhir_pdf')->nullable();
            $table->string('laporan_akhir_word')->nullable();
            $table->string('lembar_konsultasi')->nullable();
            $table->string('berita_acara')->nullable();
            $table->string('buku_manual')->nullable();
            $table->string('halaman_pengesahan')->nullable();
            $table->text('catatan_final')->nullable();
            $table->enum('status_final', ['Menunggu', 'Disetujui', 'Ditolak'])->default('Menunggu');

            // Lembar Konsultasi & Hasil Sidang
            $table->string('hasil_sidang')->nullable();

            // Catatan Dosen
            $table->text('catatan_dosen')->nullable();
            $table->text('catatan_revisi1')->nullable();

            // Catatan per Penguji
            $table->text('catatan_penguji_1')->nullable();
            $table->text('catatan_penguji_2')->nullable();
            $table->text('catatan_penguji_3')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sidang');
    }
};
