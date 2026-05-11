<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->string('locale', 5)->default('id')->after('import_source');
            $table->uuid('translation_group_id')->nullable()->after('locale');
            $table->index(['locale', 'translation_group_id']);
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropIndex(['locale', 'translation_group_id']);
            $table->dropColumn(['locale', 'translation_group_id']);
        });
    }
};
