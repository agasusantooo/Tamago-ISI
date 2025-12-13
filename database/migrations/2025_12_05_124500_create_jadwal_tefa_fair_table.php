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
        Schema::create('jadwal_tefa_fair', function (Blueprint $table) {
            $table->id();
            $table->string('nama_slot')->comment('e.g., "Pagi 08:00-10:00", "Siang 13:00-15:00"');
            $table->string('mata_kuliah')->comment('Course/subject name');
            $table->string('ruang')->comment('Room/space name');
            $table->dateTime('tanggal_mulai');
            $table->dateTime('tanggal_selesai');
            $table->integer('kapasitas')->default(1)->comment('Max registrations for this slot');
            $table->text('daftar_sumber_daya_default')->nullable()->comment('Default resource requirements template');
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_tefa_fair');
    }
};
