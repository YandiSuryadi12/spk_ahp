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
        Schema::create('matriks_penilaian_akhir_ahps', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->double('nilai');
            $table->foreignId("kriteria_id")->constrained("kriteria", "id");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matriks_penilaian_akhir_ahps');
    }
};
