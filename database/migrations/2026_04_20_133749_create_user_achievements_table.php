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
        Schema::create('user_achievements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('type')->nullable(); // misal: sertifikasi, penghargaan, lomba
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('organizer')->nullable();
            $table->string('year', 4)->nullable();
            $table->string('rank')->nullable();
            $table->string('level')->nullable();
            $table->string('certificate_link')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_achievements');
    }
};
