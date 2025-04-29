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
            $table->id('id_Sempro');
            $table->foreignId('id_ajuan')->constrained('pengajuan_pembimbing','id_ajuan');
            $table->string('form_persetujuan_sempro');
            $table->string('hasil_sempro');
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
