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
            // Make feedback columns nullable with default NULL
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
            // Revert if needed
        });
    }
};
