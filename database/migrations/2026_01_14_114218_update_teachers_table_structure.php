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
        Schema::table('teachers', function (Blueprint $table) {
            if (!Schema::hasColumn('teachers', 'user_id')) {
                $table->foreignId('user_id')->after('id')->constrained()->onDelete('cascade');
            }
            if (!Schema::hasColumn('teachers', 'phone')) {
                $table->string('phone')->nullable()->after('user_id');
            }
            if (!Schema::hasColumn('teachers', 'department')) {
                $table->string('department')->default('General')->after('phone');
            }
            if (!Schema::hasColumn('teachers', 'subjects')) {
                $table->json('subjects')->nullable()->after('department');
            }
            if (!Schema::hasColumn('teachers', 'status')) {
                $table->string('status')->default('active')->after('subjects');
            }
            if (!Schema::hasColumn('teachers', 'profile_image')) {
                $table->string('profile_image')->nullable()->after('status');
            }
            if (!Schema::hasColumn('teachers', 'salary')) {
                $table->decimal('salary', 10, 2)->default(0)->after('profile_image');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'phone', 'department', 'subjects', 'status', 'profile_image', 'salary']);
        });
    }
};
