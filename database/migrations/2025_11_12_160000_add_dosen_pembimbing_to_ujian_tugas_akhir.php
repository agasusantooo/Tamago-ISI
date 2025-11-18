<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ujian_tugas_akhir', function (Blueprint $table) {
            if (!Schema::hasColumn('ujian_tugas_akhir', 'dosen_pembimbing_id')) {
                // Add column without positional "after" to avoid errors when proposal_id
                // or other intermediate columns are not present in all environments.
                $table->unsignedBigInteger('dosen_pembimbing_id')->nullable()->index();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ujian_tugas_akhir', function (Blueprint $table) {
            if (Schema::hasColumn('ujian_tugas_akhir', 'dosen_pembimbing_id')) {
                $table->dropColumn('dosen_pembimbing_id');
            }
        });
    }
};
