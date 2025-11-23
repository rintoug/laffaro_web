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
        Schema::create('gift_article_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gift_article_id')->constrained()->onDelete('cascade');
            $table->string('title',150);
            $table->longText('description');
            $table->string('image',255);
            $table->decimal('price',10,2);
            $table->string('aff_link',255);
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gift_article_products');
    }
};
