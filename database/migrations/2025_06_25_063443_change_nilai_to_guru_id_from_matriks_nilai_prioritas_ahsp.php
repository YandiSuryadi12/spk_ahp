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
        Schema::table('matriks_penilaian_akhir_ahps', function (Blueprint $table) {
            $table->unsignedBigInteger('data_gurus_id')->after('id'); // sesuaikan penempatannya
            $table->foreign('data_gurus_id')->references('id')->on('data_gurus')->onDelete('cascade');
        });
    }

};
