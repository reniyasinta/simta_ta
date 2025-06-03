<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('undangan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jadwal_id')->constrained('jadwals')->onDelete('cascade');
            $table->foreignId('penguji_id')->constrained('users')->onDelete('cascade');
            $table->enum('jenis_acara', ['seminar', 'sidang']);
            $table->string('file_path');
            $table->foreignId('uploaded_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('undangan');
    }
};
