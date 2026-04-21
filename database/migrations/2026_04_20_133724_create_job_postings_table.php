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
        Schema::create('job_postings', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('short_description', 255)->nullable();
            $table->string('location')->nullable();
            $table->string('salary_range')->nullable();
            $table->integer('min_salary')->nullable();
            $table->integer('max_salary')->nullable();
            $table->string('job_type')->nullable(); // full_time, freelance, etc.
            $table->string('min_education')->nullable();
            $table->boolean('is_urgent')->default(false);
            $table->string('provinsi')->nullable();
            $table->string('kota')->nullable();
            $table->string('kecamatan')->nullable();
            $table->timestamp('deadline')->nullable();
            $table->integer('max_applicants')->nullable();
            $table->json('skills_json')->nullable();
            $table->json('benefits_json')->nullable();
            $table->string('experience_level')->nullable();
            
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_postings');
    }
};
