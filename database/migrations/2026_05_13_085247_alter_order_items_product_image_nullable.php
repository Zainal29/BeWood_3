<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->string('product_image')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->string('product_image')->nullable(false)->change();
        });
    }
};
