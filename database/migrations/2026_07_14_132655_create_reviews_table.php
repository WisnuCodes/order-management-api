<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('Reviews', function (Blueprint $table) {
            $table->id('review_id');
            $table->foreignId('product_id')->constrained('Product', 'product_id')->onDelete('cascade');
            $table->foreignId('buyer_id')->constrained('User', 'user_id')->onDelete('cascade');
            $table->integer('rating');
            $table->text('comment')->nullable();
            $table->timestamps();
            
            $table->unique(['product_id', 'buyer_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Reviews');
    }
};
