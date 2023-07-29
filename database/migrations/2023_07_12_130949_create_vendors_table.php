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
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->string('nama_vendor');
            $table->text('alamat_vendor');
            $table->string('kontak_vendor');
            $table->string('email_vendor')->nullable();
            $table->date('tanggal_tagihan')->nullable();
            $table->decimal('jumlah_tagihan')->nullable();
            $table->text('keterangan_tagihan')->nullable();
            $table->enum('status_pembayaran', ['Belum Dibayar', 'Sudah Dibayar']);
            $table->date('tanggal_pembayaran')->nullable();
            $table->decimal('jumlah_pembayaran')->nullable();
            $table->string('metode_pembayaran')->nullable();
            $table->string('bukti_pembayaran')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendors');
    }
};
