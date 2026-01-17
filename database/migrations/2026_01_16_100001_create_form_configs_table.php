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
        Schema::create('form_configs', function (Blueprint $table) {
            $table->id();
            $table->string('form_type'); // e.g., 'student_registration', 'teacher_registration'
            $table->string('field_name');
            $table->boolean('visible')->default(true);
            $table->integer('order')->default(0);
            $table->json('role_visibility')->nullable(); // Array of roles that can see this field
            $table->timestamps();

            $table->unique(['form_type', 'field_name']);
            $table->index('form_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_configs');
    }
};
