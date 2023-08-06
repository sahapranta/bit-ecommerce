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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->unsignedInteger('rating')->default(5);
            $table->text('review')->nullable();
            $table->boolean('is_approved')->default(true);
            $table->boolean('is_anonymous')->default(false);
            $table->timestamps();
        });

        Schema::table('products', function (Blueprint $table) {
            $table->after('options', function ($table) {
                // $table->unsignedInteger('reviews_count')->default(0);
                $table->decimal('ratings', 2, 1)->default(0);
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // $table->dropColumn(['reviews_count', 'rating']);
            $table->dropColumn(['ratings']);
        });

        Schema::dropIfExists('reviews');
    }
};
