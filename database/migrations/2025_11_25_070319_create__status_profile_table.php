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
        Schema::create('_status_profile', function (Blueprint $table) {
            $table->id();

            // relasi ke tabel profile_controllers
            $table->unsignedBigInteger('profile_id')->unique();
            $table->foreign('profile_id')
                  ->references('id')->on('profile_controllers')
                  ->onDelete('cascade');

            $table->string('profile_aktif');
            $table->string('validasi_data');
            $table->string('dibuat');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('_status_profile');
    }
};
