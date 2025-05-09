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
            $table->dateTime('tanggal_mulai');
            $table->dateTime('tanggal_selesai')->nullable();
            $table->string('tempat');
            $table->enum('jenis_acara', ['sosialisasi', 'seminar_proposal', 'sidang_ta']);
            $table->string('judul_ta');
            $table->string('nim');
            $table->string('nama');
            $table->string('prodi');
            $table->string('kelas');
            $table->string('pembimbing_1');
            $table->string('pembimbing_2')->nullable();
            $table->foreignId('penguji_1_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('penguji_2_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('penguji_3_id')->nullable()->constrained('users')->onDelete('set null');
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
