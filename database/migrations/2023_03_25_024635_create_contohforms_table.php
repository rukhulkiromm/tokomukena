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
        Schema::create('contohform', function (Blueprint $table) {
            $table->id();
            $table->string('nama_dokumen');
            $table->string('gambar_dokumen');
            $table->date('tgl_rilis');
            $table->string('klasifikasi_dokumen');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contohform');
    }
};
