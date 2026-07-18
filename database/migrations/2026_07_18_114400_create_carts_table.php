<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('Carts', function (Blueprint $table) {
            $table->id('cart_id');
            $table->foreignId('buyer_id')->constrained('User', 'user_id')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('Product', 'product_id')->onDelete('cascade');
            $table->unsignedInteger('quantity')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Carts');
    }
};
