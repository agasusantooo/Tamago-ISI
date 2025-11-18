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
        Schema::table('bimbingans', function (Blueprint $table) {
            $table->string('nim')->after('id_proyek_akhir');
            $table->foreign('nim')
                  ->references('nim')
                  ->on('mahasiswa')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bimbingans', function (Blueprint $table) {
            $table->dropForeign(['nim']);
            $table->dropColumn('nim');
        });
    }
};
