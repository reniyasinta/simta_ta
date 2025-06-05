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
            $table->string('form_persetujuan_sempro')->nullable();
            $table->string('hasil_sempro')->nullable();
            $table->enum('status_dospem1', ['Menunggu', 'Menyetujui', 'Tidak Menyetujui'])->default('Menunggu');
            $table->enum('status_dospem2', ['Menunggu', 'Menyetujui', 'Tidak Menyetujui'])->default('Menunggu');
            $table->text('catatan_dospem1')->nullable();
            $table->text('catatan_dospem2')->nullable();
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
