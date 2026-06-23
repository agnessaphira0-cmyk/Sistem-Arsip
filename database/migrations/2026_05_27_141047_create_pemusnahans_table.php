<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pemusnahans', function (Blueprint $table) {
            $table->id();
            $table->string('no_surat', 100);
            $table->string('nama_dokumen');
            $table->string('kategori', 100);
            $table->string('kode_box_lama', 50);
            $table->string('kode_rak_lama', 50);
            $table->timestamp('tanggal_pemusnahan');
            $table->string('eksekutor');
            $table->text('alasan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pemusnahans');
    }
};