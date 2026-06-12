<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('Product', function (Blueprint $table) {
            $table->id('product_id');
            $table->foreignId('seller_id')->constrained('User', 'user_id')->onDelete('cascade');
            $table->foreignId('category_id')->constrained('Product_Category', 'category_id')->onDelete('restrict');
            $table->string('title', 100);
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('rating', 3, 1)->default(0);
            $table->string('thumbnail', 255)->nullable();
            $table->string('file_path', 255);
            $table->unsignedInteger('download_count')->default(0);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Product');
    }
};