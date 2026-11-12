<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('exports', function (Blueprint $table) {
            if (!Schema::hasIndex('exports', 'exports_created_at_index')) {
                $table->index('created_at', 'exports_created_at_index');
            }
            if (!Schema::hasIndex('exports', 'exports_completed_at_index')) {
                $table->index('completed_at', 'exports_completed_at_index');
            }
            if (!Schema::hasIndex('exports', 'exports_type_status_completed_at_index')) {
                $table->index(['type', 'status', 'completed_at'], 'exports_type_status_completed_at_index');
            }
        });
    }

    public function down(): void
    {
        Schema::table('exports', function (Blueprint $table) {
            if (Schema::hasIndex('exports', 'exports_created_at_index')) {
                $table->dropIndex('exports_created_at_index');
            }
            if (Schema::hasIndex('exports', 'exports_completed_at_index')) {
                $table->dropIndex('exports_completed_at_index');
            }
            if (Schema::hasIndex('exports', 'exports_type_status_completed_at_index')) {
                $table->dropIndex('exports_type_status_completed_at_index');
            }
        });
    }
};
