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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2)->default(0);
            $table->integer('duration')->nullable();
            $table->enum('duration_unit', ['hours', 'days', 'weeks', 'months'])->default('months');
            $table->enum('status', ['active', 'inactive', 'draft'])->default('active');
            $table->string('image')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->integer('max_students')->nullable();
            $table->string('category')->nullable();
            $table->enum('level', ['beginner', 'intermediate', 'advanced'])->default('beginner');
            $table->json('prerequisites')->nullable();
            $table->json('objectives')->nullable();
            $table->json('syllabus')->nullable();
            $table->string('materials_url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
