<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('why_us_stats', function (Blueprint $table) {
            $table->id();
            $table->string('label'); // e.g., "Pelanggan Puas"
            $table->string('value'); // e.g., "7K+"
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $timestamps = false; // optional, bisa pakai timestamps
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('why_us_stats');
    }
};
