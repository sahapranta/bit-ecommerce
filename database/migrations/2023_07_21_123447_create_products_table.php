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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('category_id')->constrained();
            $table->string('name');
            $table->string('title')->nullable();
            $table->string('upc', 20)->unique()->nullable();
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('short_description')->nullable();
            $table->decimal('price', 16, 8)->default(0);
            $table->decimal('discount', 10, 8)->nullable();
            $table->string('status')->default(\App\Enums\ProductStatusEnum::DRAFT?->value || 'draft');
            $table->integer('stock')->default(0);
            $table->integer('sales')->default(0);
            $table->decimal('delivery_fee', 10, 8)->default(0);
            $table->json('tags')->nullable();
            $table->json('options')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
