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
        // Migration intentionally left blank because a separate migration (2025_11_10_165642) handles adding user_id.
        return;
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Nothing to rollback here.
        return;
    }
};
