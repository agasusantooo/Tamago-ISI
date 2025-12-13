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
        Schema::table('tefa_fair', function (Blueprint $table) {
            // Drop the foreign key constraint first
            $table->dropForeign(['id_proyek_akhir']);
            
            // Drop the old column
            $table->dropColumn('id_proyek_akhir');
            
            // Add the new proposal_id column
            $table->unsignedBigInteger('proposal_id')->nullable();
            
            // Add foreign key for proposal_id
            $table->foreign('proposal_id')->references('id')->on('proposals')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tefa_fair', function (Blueprint $table) {
            // Drop the new foreign key
            $table->dropForeign(['proposal_id']);
            
            // Drop the new column
            $table->dropColumn('proposal_id');
            
            // Add back the old column
            $table->unsignedBigInteger('id_proyek_akhir');
            
            // Add back the old foreign key
            $table->foreign('id_proyek_akhir')->references('id_proyek_akhir')->on('projek_akhir')->onDelete('cascade');
        });
    }
};
