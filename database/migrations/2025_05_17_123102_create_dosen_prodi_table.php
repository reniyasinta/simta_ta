<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('dosen_prodi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_dosen');
            $table->unsignedBigInteger('id_prodi');

            $table->foreign('id_dosen')->references('id_dosen')->on('dosen')->onDelete('cascade');
            $table->foreign('id_prodi')->references('id')->on('prodis')->onDelete('cascade');

            $table->unique(['id_dosen', 'id_prodi']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dosen_prodi');
    }
};
