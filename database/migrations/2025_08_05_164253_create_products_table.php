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
            $table->string('name')->max(255);
            $table->string('brand')->max(255);
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('description')->nullable();
            $table->integer('quantity_in_stock')->default(0);
            $table->string('serial_number')->unique()->max(255);
            $table->date('date_of_acquisition')->nullable();
            $table->foreignId('status_id')->constrained()->onDelete('cascade')->default(1);
            $table->timestamps();
            $table->softDeletes();
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