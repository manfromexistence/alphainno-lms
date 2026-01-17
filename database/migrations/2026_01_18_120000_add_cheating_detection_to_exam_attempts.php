<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('exam_attempts', function (Blueprint $table) {
            $table->integer('tab_switches')->default(0)->after('status');
            $table->boolean('flagged_for_cheating')->default(false)->after('tab_switches');
            $table->text('cheating_notes')->nullable()->after('flagged_for_cheating');
            $table->timestamp('auto_submitted_at')->nullable()->after('submitted_at');
        });

        Schema::table('cq_submissions', function (Blueprint $table) {
            $table->json('annotated_files')->nullable()->after('files');
            $table->text('teacher_notes')->nullable()->after('feedback');
        });
    }

    public function down(): void
    {
        Schema::table('exam_attempts', function (Blueprint $table) {
            $table->dropColumn(['tab_switches', 'flagged_for_cheating', 'cheating_notes', 'auto_submitted_at']);
        });

        Schema::table('cq_submissions', function (Blueprint $table) {
            $table->dropColumn(['annotated_files', 'teacher_notes']);
        });
    }
};
