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
        Schema::create('pengembalians', function (Blueprint $table) {
            $table->id();
            $table->string('peminjam');
            $table->string('barang');
            $table->date('tgl_kembali');
            $table->enum('status', ['Pending','Dipinjam', 'Dikembalikan']);
            $table->enum('kondisi', ['baik', 'rusak'])->default('baik');
            $table->integer('denda')->default(0);
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengembalians');
    }
};
