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
        Schema::table('tim_produksi', function (Blueprint $table) {
            $table->text('feedback_pra_produksi')->nullable()->change();
            $table->text('feedback_produksi')->nullable()->change();
            $table->text('feedback_pasca_produksi')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tim_produksi', function (Blueprint $table) {
            $table->text('feedback_pra_produksi')->nullable(false)->change();
            $table->text('feedback_produksi_akhir')->nullable(false)->change();
            $table->text('feedback_pasca_produksi')->nullable(false)->change();
        });
    }
};
