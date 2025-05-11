<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kelompok', function (Blueprint $table) {
            $table->id('id_kelompok');
            $table->foreignId('anggota_1_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('anggota_2_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('anggota_3_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('kelompok');
    }
};
