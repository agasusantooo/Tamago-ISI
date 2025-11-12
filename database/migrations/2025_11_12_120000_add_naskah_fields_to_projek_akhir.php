<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Adds fields to store the final manuscript (naskah publikasi) and an optional journal link.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('projek_akhir', function (Blueprint $table) {
            if (!Schema::hasColumn('projek_akhir', 'file_naskah_publikasi')) {
                $table->string('file_naskah_publikasi')->nullable()->after('file_proposal');
            }
            if (!Schema::hasColumn('projek_akhir', 'link_jurnal')) {
                $table->string('link_jurnal')->nullable()->after('file_naskah_publikasi');
            }
            if (!Schema::hasColumn('projek_akhir', 'tanggal_upload_naskah')) {
                $table->timestamp('tanggal_upload_naskah')->nullable()->after('link_jurnal');
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
            if (Schema::hasColumn('projek_akhir', 'tanggal_upload_naskah')) {
                $table->dropColumn('tanggal_upload_naskah');
            }
            if (Schema::hasColumn('projek_akhir', 'link_jurnal')) {
                $table->dropColumn('link_jurnal');
            }
            if (Schema::hasColumn('projek_akhir', 'file_naskah_publikasi')) {
                $table->dropColumn('file_naskah_publikasi');
            }
        });
    }
};
