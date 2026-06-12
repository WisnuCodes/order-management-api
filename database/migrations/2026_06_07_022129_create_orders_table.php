<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('Orders', function (Blueprint $table) {
            $table->id('transaction_id');
            $table->foreignId('buyer_id')->constrained('User', 'user_id')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('Product', 'product_id')->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->enum('payment_status', ['pending', 'success', 'failed'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Orders');
    }
};