<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('order_number')->unique();
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone');
            $table->text('shipping_address');
            $table->text('note')->nullable();
            $table->integer('subtotal');
            $table->integer('shipping_cost')->default(0);
            $table->integer('discount')->default(0);
            $table->integer('total');
            $table->string('payment_method');
            $table->string('payment_status')->default('pending'); // pending, paid, failed
            $table->string('delivery_status')->default('pending'); // pending, processing, shipped, delivered, cancelled
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
