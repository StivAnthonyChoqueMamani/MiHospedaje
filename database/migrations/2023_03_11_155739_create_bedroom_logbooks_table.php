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
        Schema::create('bedroom_logbook', function (Blueprint $table) {
            $table->id();
            $table->foreignId('logbook_id')->constrained();
            $table->foreignId('bedroom_id')->constrained();
            $table->integer('additional_charge')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bedroom_logbook');
    }
};
