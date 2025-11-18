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
        Schema::table('projek_akhir', function (Blueprint $table) {
            $table->unsignedBigInteger('proposal_id')->nullable();
            // Uncomment below if you want to add a foreign key constraint
            // $table->foreign('proposal_id')->references('id')->on('proposal')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projek_akhir', function (Blueprint $table) {
            // Uncomment below if you added a foreign key constraint
            // $table->dropForeign(['proposal_id']);
            $table->dropColumn('proposal_id');
        });
    }
};
