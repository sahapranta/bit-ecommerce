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
        Schema::create('unknown_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('txid')->nullable();
            $table->string('address')->nullable();
            $table->decimal('amount', 16, 8)->default(0);
            $table->integer('confirmations')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unknown_transactions');
    }
};
