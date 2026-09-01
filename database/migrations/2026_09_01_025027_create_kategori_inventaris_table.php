<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kategori_inventaris', function (Blueprint $table) {
            $table->increments('kategori_inventaris_id');
            $table->string('kode_kategori', 20)->unique();
            $table->string('nama_kategori', 100);
            $table->text('deskripsi')->nullable();
            $table->enum('status', ['Aktif', 'Tidak Aktif'])->default('Aktif');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kategori_inventaris');
    }
};