<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('instagram_posts', function (Blueprint $table) {
            $table->id();
            $table->string('image'); // path gambar
            $table->string('instagram_url')->nullable(); // link ke postingan IG
            $table->boolean('is_active')->default(true);
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('instagram_posts');
    }
};
