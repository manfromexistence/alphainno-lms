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
        Schema::table('students', function (Blueprint $table) {
            $table->string('class')->nullable()->after('batch_id');
        });

        Schema::table('courses', function (Blueprint $table) {
            $table->string('class')->nullable()->after('category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('class');
        });

        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn('class');
        });
    }
};
