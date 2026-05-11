<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('exports', function (Blueprint $table) {
            $table->string('deploy_status')->nullable()->after('errors');
            $table->string('deploy_pr_url')->nullable()->after('deploy_status');
            $table->timestamp('deployed_at')->nullable()->after('deploy_pr_url');
        });
    }

    public function down(): void
    {
        Schema::table('exports', function (Blueprint $table) {
            $table->dropColumn(['deploy_status', 'deploy_pr_url', 'deployed_at']);
        });
    }
};
