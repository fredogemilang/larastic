<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('content_revisions', function (Blueprint $table) {
            $table->id();
            $table->string('revisionable_type');        // App\Models\Post or App\Models\Page
            $table->unsignedBigInteger('revisionable_id');
            $table->enum('action', ['created', 'updated', 'deleted']);
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('summary');                   // e.g. "Updated title, content"
            $table->json('old_values')->nullable();      // Changed fields before
            $table->json('new_values')->nullable();      // Changed fields after
            $table->timestamp('created_at')->useCurrent();

            $table->index(['revisionable_type', 'revisionable_id'], 'revision_morph_index');
            $table->index('action');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('content_revisions');
    }
};
