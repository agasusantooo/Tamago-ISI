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
        Schema::table('ta_progress_stages', function (Blueprint $table) {
            $table->string('type')->default('ta')->after('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ta_progress_stages', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};
