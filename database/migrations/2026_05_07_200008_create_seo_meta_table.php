<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seo_meta', function (Blueprint $table) {
            $table->id();
            $table->morphs('metable'); // metable_type, metable_id — polymorphic to posts/pages
            $table->string('robots')->nullable(); // noindex, nofollow, etc.
            $table->json('json_ld')->nullable(); // structured data
            $table->string('og_type')->nullable(); // article, website
            $table->string('twitter_card')->default('summary_large_image');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seo_meta');
    }
};
