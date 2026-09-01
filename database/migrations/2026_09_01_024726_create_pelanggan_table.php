<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pelanggan', function (Blueprint $table) {
            $table->increments('pelanggan_id');

            $table->string('kode_pelanggan', 20)->unique();
            $table->string('nama_pelanggan', 100);
            $table->string('email', 100)->nullable();
            $table->string('no_telepon', 20);
            $table->text('alamat')->nullable();
            $table->date('tanggal_daftar');
            $table->enum('status', ['Aktif', 'Tidak Aktif'])->default('Aktif');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pelanggan');
    }
};