<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dokumens', function (Blueprint $table) {
            $table->id();
            $table->string('nama_dokumen');
            $table->string('kategori');
            $table->foreignId('box_id')->constrained('boxes')->onDelete('cascade');
            $table->date('tgl_masuk');
            $table->enum('jenis', ['fisik', 'digital'])->default('fisik');
            $table->string('file_path')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dokumens');
    }
};