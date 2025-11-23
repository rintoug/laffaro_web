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
        Schema::create('gift_articles', function (Blueprint $table) {
            $table->id();
            $table->string('title',150);
            $table->string('slug')->unique();
            $table->longText('description')->nullable();
            $table->string('image',255)->nullable();
            $table->tinyInteger('status')->default(1);
            $table->string('meta_title', 255)->nullable();
            $table->string('meta_keywords', 255)->nullable();
            $table->string('meta_description', 500)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gift_articles');
    }
};
