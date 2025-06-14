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
        Schema::create('sidang_uploads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_sidang')->constrained('sidang', 'id_sidang')->onDelete('cascade');
            $table->enum('jenis_upload', ['draft', 'revisi']);
            $table->string('file_path');
            $table->string('file_path_2')->nullable();
            $table->timestamp('uploaded_at')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sidang_uploads');
    }
};
