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
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('image');
            $table->text('short_description');
            $table->text('description')->nullable();
            $table->string('aff_link');
            $table->decimal('price', 10, 2);
            $table->tinyInteger('status')->default(1);
            $table->string('meta_title',255)->nullable();
            $table->string('meta_keywords',255)->nullable();
            $table->string('meta_description',500)->nullable();
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
