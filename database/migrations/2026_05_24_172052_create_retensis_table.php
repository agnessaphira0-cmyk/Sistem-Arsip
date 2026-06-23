<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('retensis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dokumen_id')->constrained('dokumens')->onDelete('cascade');
            $table->date('tgl_mulai');
            $table->date('tgl_kadaluarsa');
            $table->enum('status', ['aktif', 'akan_kadaluarsa', 'kadaluarsa'])->default('aktif');
            $table->text('ket_retensi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('retensis');
    }
};