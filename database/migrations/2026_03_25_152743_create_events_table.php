<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title', 500);
            $table->text('description')->nullable();
            $table->string('category_slug', 50)->nullable();
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->string('source_url', 500)->nullable();
            $table->string('image_url', 500)->nullable();
            $table->string('source_name', 100)->nullable();
            $table->dateTime('published_at')->nullable();
            $table->timestamps();

            $table->index('category_slug');
            $table->index('published_at');
            $table->index(['latitude', 'longitude']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
