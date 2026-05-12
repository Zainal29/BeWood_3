<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('testimonials', function (Blueprint $table) {
            if (!Schema::hasColumn('testimonials', 'rating')) {
                $table->tinyInteger('rating')->unsigned()->default(5)->after('content');
            }
            if (!Schema::hasColumn('testimonials', 'location')) {
                $table->string('location')->nullable()->after('customer_name');
            }
            if (!Schema::hasColumn('testimonials', 'product_name')) {
                $table->string('product_name')->nullable()->after('location');
            }
            if (!Schema::hasColumn('testimonials', 'order')) {
                $table->integer('order')->default(0)->after('is_active');
            }
            if (!Schema::hasColumn('testimonials', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('order');
            }
        });
    }

    public function down(): void
    {
        Schema::table('testimonials', function (Blueprint $table) {
            $table->dropColumn(['rating', 'location', 'product_name', 'order', 'is_active']);
        });
    }
};
