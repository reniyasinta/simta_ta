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
        Schema::create('mahasiswa', function (Blueprint $table) {
            $table->id('id_mhs');
            $table->foreignId('id_users')->constrained('users')->onDelete('cascade');
            $table->string('nim_mhs');
            $table->string('nama_mhs');
            $table->unsignedBigInteger('id_kelompok')->nullable();
            $table->foreign('id_kelompok')->references('id_kelompok')->on('kelompok')->onDelete('set null');
            $table->string('prodi_mhs')->nullable();
            $table->string('semester')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mahasiswa');
    }
};
