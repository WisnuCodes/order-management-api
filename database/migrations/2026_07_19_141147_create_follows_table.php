<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('follows', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('buyer_id');
            $table->unsignedBigInteger('seller_id');
            $table->timestamps();

            $table->unique(['buyer_id', 'seller_id']);

            $table->foreign('buyer_id')->references('user_id')->on('User')->onDelete('cascade');
            $table->foreign('seller_id')->references('user_id')->on('User')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('follows');
    }
};
