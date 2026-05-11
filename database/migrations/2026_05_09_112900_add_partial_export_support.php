<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('exports', function (Blueprint $table) {
            // Change type enum to include 'partial'
            // We'll use raw SQL for enum modification
        });

        // Modify the enum column to include 'partial'
        DB::statement("ALTER TABLE exports MODIFY COLUMN type ENUM('full', 'partial', 'preview') DEFAULT 'full'");

        Schema::table('exports', function (Blueprint $table) {
            $table->json('scope_details')->nullable()->after('csp_report');
            $table->unsignedBigInteger('based_on_export_id')->nullable()->after('scope_details');

            $table->foreign('based_on_export_id')
                ->references('id')
                ->on('exports')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('exports', function (Blueprint $table) {
            $table->dropForeign(['based_on_export_id']);
            $table->dropColumn(['scope_details', 'based_on_export_id']);
        });

        DB::statement("ALTER TABLE exports MODIFY COLUMN type ENUM('full', 'preview') DEFAULT 'full'");
    }
};
