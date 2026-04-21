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
        // 1. Missing Indexes
        Schema::table('applications', function (Blueprint $table) {
            $table->index(['job_id', 'status'], 'idx_applications_job_status');
            $table->index(['user_id', 'job_id'], 'idx_applications_user_job');
        });

        Schema::table('job_postings', function (Blueprint $table) {
            $table->index(['created_by', 'created_at'], 'idx_job_postings_creator_created');
            $table->index('job_type', 'idx_job_postings_type');
            $table->index(['min_salary', 'max_salary'], 'idx_job_postings_salary');
        });

        // 2. Missing rate_limits Table
        if (!Schema::hasTable('rate_limits')) {
            Schema::create('rate_limits', function (Blueprint $table) {
                $table->id();
                $table->string('key_name')->unique();
                $table->integer('count')->default(0);
                $table->integer('last_attempt');
                $table->integer('expires_at');
                $table->index('expires_at', 'idx_expires');
            });
        }

        // 3. Missing password_resets Table Migration
        if (!Schema::hasTable('password_resets')) {
            Schema::create('password_resets', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->index();
                $table->string('token');
                $table->timestamp('expires_at')->nullable();
                $table->timestamp('created_at')->useCurrent();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropIndex('idx_applications_job_status');
            $table->dropIndex('idx_applications_user_job');
        });

        Schema::table('job_postings', function (Blueprint $table) {
            $table->dropIndex('idx_job_postings_creator_created');
            $table->dropIndex('idx_job_postings_type');
            $table->dropIndex('idx_job_postings_salary');
        });

        Schema::dropIfExists('rate_limits');
        Schema::dropIfExists('password_resets');
    }
};
