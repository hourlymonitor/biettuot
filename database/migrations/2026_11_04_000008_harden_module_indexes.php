<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contributor_profiles', function (Blueprint $table): void {
            $table->unique('user_id', 'contributor_profiles_user_id_unique');
        });
        Schema::table('content_reviews', function (Blueprint $table): void {
            $table->index(['status', 'created_at'], 'content_reviews_status_created_at_index');
        });
        Schema::table('ai_usage_logs', function (Blueprint $table): void {
            $table->index(['status', 'created_at'], 'ai_usage_logs_status_created_at_index');
        });
    }

    public function down(): void
    {
        Schema::table('ai_usage_logs', fn (Blueprint $table) => $table->dropIndex('ai_usage_logs_status_created_at_index'));
        Schema::table('content_reviews', fn (Blueprint $table) => $table->dropIndex('content_reviews_status_created_at_index'));
        Schema::table('contributor_profiles', fn (Blueprint $table) => $table->dropUnique('contributor_profiles_user_id_unique'));
    }
};
