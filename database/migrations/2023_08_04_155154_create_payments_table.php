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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users', 'id')->cascadeOnDelete();
            $table->foreignId('order_id')->nullable()->constrained('orders', 'id')->cascadeOnDelete();
            $table->decimal('amount', 16, 8)->default(0);
            $table->decimal('received', 16, 8)->nullable();
            $table->boolean('is_paid')->default(false);
            $table->boolean('active')->default(false);
            $table->string('address')->nullable();
            $table->string('txid')->nullable();
            $table->integer('confirmations')->default(0);
            $table->timestamp('paid_at')->nullable();
            $table->boolean('is_refundable')->default(false);
            $table->boolean('is_refunded')->default(false);
            $table->decimal('refund_amount', 16, 8)->nullable();
            $table->string('refund_address')->nullable();
            $table->string('refund_txid')->nullable();
            $table->timestamp('refunded_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
