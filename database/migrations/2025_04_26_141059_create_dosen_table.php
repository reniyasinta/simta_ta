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
        Schema::create('dosen', function (Blueprint $table) {
            $table->id('id_dosen');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('nip_dosen');
            $table->string('nama_dosen');
            $table->unsignedBigInteger('id_prodi')->nullable();
            $table->foreign('id_prodi')->references('id')->on('prodis')->onDelete('set null');
            $table->string('keahlian')->nullable();
            $table->string('no_telp')->nullable();
            $table->string('foto')->nullable();
            $table->integer('kuota_bimbingan')->nullable()->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dosen');
    }
};
