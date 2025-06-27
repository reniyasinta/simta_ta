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
        Schema::create('jadwals', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal'); // ⬅️ hanya tanggal
            $table->time('jam_mulai'); // ⬅️ waktu mulai
            $table->time('jam_selesai')->nullable(); // ⬅️ waktu selesai
            $table->string('ruangan');
            $table->enum('jenis_acara', ['seminar', 'sidang', 'yudisium']);
            $table->string('judul_ta');
            $table->string('nim');
            $table->string('nama');
            $table->string('prodi');
            $table->string('pembimbing_1');
            $table->string('pembimbing_2')->nullable();
            $table->foreignId('penguji_1_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('penguji_2_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('penguji_3_id')->nullable()->constrained('users')->onDelete('set null');
            $table->unsignedBigInteger('id_kelompok')->nullable();
            $table->foreign('id_kelompok')->references('id_kelompok')->on('kelompok')->nullOnDelete();
            $table->unsignedBigInteger('id_dosen')->nullable();
            $table->foreign('id_dosen')->references('id')->on('users')->onDelete('set null');
            $table->unsignedBigInteger('id_ajuan')->nullable();
            $table->foreign('id_ajuan')->references('id_ajuan')->on('pengajuan_pembimbing')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwals');
    }
};
