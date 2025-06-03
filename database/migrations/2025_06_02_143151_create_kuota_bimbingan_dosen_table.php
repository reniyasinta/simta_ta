<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kuota_bimbingan_dosen', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_dosen'); // FK ke dosen
            $table->unsignedBigInteger('id_prodi'); // FK ke prodi
            $table->integer('kuota_bimbingan')->default(0);
            $table->integer('kuota_p2')->default(0); // kalau mau sekalian kuota P2 per prodi
            $table->timestamps();

            $table->foreign('id_dosen')->references('id_dosen')->on('dosen')->onDelete('cascade');
            $table->foreign('id_prodi')->references('id')->on('prodis')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kuota_bimbingan_dosen');
    }
};
