<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Posts: drop unique on slug, add unique on (slug, locale)
        Schema::table('posts', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->unique(['slug', 'locale'], 'posts_slug_locale_unique');
        });

        // Pages: drop unique on slug, add unique on (slug, locale)
        Schema::table('pages', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->unique(['slug', 'locale'], 'pages_slug_locale_unique');
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropUnique('posts_slug_locale_unique');
            $table->unique('slug');
        });

        Schema::table('pages', function (Blueprint $table) {
            $table->dropUnique('pages_slug_locale_unique');
            $table->unique('slug');
        });
    }
};
