<?php

use Illuminate\Database\Migrations\Migration;

class AddPrimaryKeyToProjekAkhirTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Skip this migration as the table already has a primary key (id_proyek_akhir)
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // No action needed
    }
}
