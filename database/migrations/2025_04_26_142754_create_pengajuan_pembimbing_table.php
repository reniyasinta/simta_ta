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
        Schema::create('pengajuan_pembimbing', function (Blueprint $table) {
            $table->id('id_ajuan');
            $table->foreignId('id_kelompok')->constrained('kelompok','id_kelompok');
            $table->foreignId('id_dosen1')->constrained('dosen','id_dosen');
            $table->foreignId('id_dosen2')->constrained('dosen','id_dosen');
            $table->string('judul_ta');
            $table->string('proposal');
            $table->enum('status', ['Menunggu', 'Diterima', 'Ditolak'])->default('Menunggu');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_pembimbing');
    }
};
