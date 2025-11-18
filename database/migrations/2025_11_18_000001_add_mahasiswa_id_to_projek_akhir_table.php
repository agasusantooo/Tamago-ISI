<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMahasiswaIdToProjekAkhirTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('projek_akhir', function (Blueprint $table) {
            if (!Schema::hasColumn('projek_akhir', 'mahasiswa_id')) {
                $table->unsignedBigInteger('mahasiswa_id')->nullable();
                $table->foreign('mahasiswa_id')->references('id')->on('mahasiswa')->onDelete('cascade');
            }

            if (!Schema::hasColumn('projek_akhir', 'proposal_id')) {
                $table->unsignedBigInteger('proposal_id')->nullable();
                $table->foreign('proposal_id')->references('id')->on('proposal')->onDelete('cascade');
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
        Schema::table('projek_akhir', function (Blueprint $table) {
            if (Schema::hasColumn('projek_akhir', 'mahasiswa_id')) {
                $table->dropForeign(['mahasiswa_id']);
                $table->dropColumn('mahasiswa_id');
            }

            if (Schema::hasColumn('projek_akhir', 'proposal_id')) {
                $table->dropForeign(['proposal_id']);
                $table->dropColumn('proposal_id');
            }
        });
    }
}