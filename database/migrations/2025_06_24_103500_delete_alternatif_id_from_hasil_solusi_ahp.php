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
        Schema::table('hasil_solusi_ahp', function (Blueprint $table) {
            $table->dropForeign(['alternatif_id']);
            $table->dropColumn('alternatif_id');
        });
    }

    public function down()
    {
        Schema::table('hasil_solusi_ahp', function (Blueprint $table) {
            // Restore the column
            $table->unsignedBigInteger('alternatif_id');

            // Restore the foreign key
            $table->foreign('alternatif_id')->references('id')->on('alternatif')->onDelete('cascade');
        });
    }

};
