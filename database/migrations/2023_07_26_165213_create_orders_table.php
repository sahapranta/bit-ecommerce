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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_id')->unique()->index();
            $table->string('tracking_id')->nullable()->index();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('shipping_address_id')->nullOnDelete()->constrained('addresses');
            $table->foreignId('billing_address_id')->nullable()->constrained('addresses');
            $table->decimal('total', 10, 2);
            $table->decimal('subtotal', 10, 2);
            $table->decimal('tax', 10, 2)->nullable();
            $table->decimal('discount', 10, 2)->nullable();
            $table->decimal('shipping', 10, 2)->nullable();
            $table->string('payment_method')->nullable();
            $table->string('payment_status')->nullable();
            $table->string('delivery_method')->nullable();
            $table->string('delivery_status')->nullable();
            $table->string('delivery_date')->nullable();
            $table->string('delivery_note')->nullable();
            $table->boolean('is_gift')->default(false);
            $table->string('gift_message')->nullable();
            $table->string('is_paid')->default(false);
            $table->string('status')->default(\App\Enums\OrderStatusEnum::PENDING?->value ?? 'pending')->index();
            $table->string('currency')->default('BTC');
            $table->json('address')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
