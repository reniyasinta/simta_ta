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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->unsignedBigInteger('role_id')->default(4); // misalnya 4 = mahasiswa
            $table->string('nip')->nullable()->unique(); // untuk admin, dosen, panitia
            $table->string('nim')->nullable()->unique(); // untuk mahasiswa

            $table->unsignedBigInteger('id_prodi')->nullable();

            $table->timestamps();

            // Foreign key relasi ke tabel roles
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');

            // Foreign key relasi ke tabel prodis
            $table->foreign('id_prodi')->references('id')->on('prodis')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
