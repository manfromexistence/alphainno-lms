<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            // Identity
            $table->string('registration_no')->nullable()->after('id');
            $table->string('name_bn')->nullable()->after('registration_no');
            $table->date('dob')->nullable()->after('name_bn');
            $table->string('gender')->nullable()->after('dob');
            $table->string('blood_group')->nullable()->after('gender');
            $table->string('religion')->nullable()->after('blood_group');

            // Schedule
            $table->string('class_days')->nullable()->after('religion');
            $table->string('class_time')->nullable()->after('class_days');

            // Family
            $table->string('father_name')->nullable()->after('class_time');
            $table->string('mother_name')->nullable()->after('father_name');
            $table->string('father_occupation')->nullable()->after('mother_name');
            $table->string('mother_occupation')->nullable()->after('father_occupation');
            $table->string('father_phone')->nullable()->after('mother_occupation');
            $table->string('mother_phone')->nullable()->after('father_phone');
            $table->string('guardian_name')->nullable()->after('mother_phone');
            $table->string('guardian_phone')->nullable()->after('guardian_name');

            // Address (Present)
            $table->string('present_village')->nullable();
            $table->string('present_po')->nullable();
            $table->string('present_ps')->nullable();
            $table->string('present_dist')->nullable();
            $table->string('present_holding')->nullable();

            // Address (Permanent)
            $table->string('permanent_village')->nullable();
            $table->string('permanent_po')->nullable();
            $table->string('permanent_ps')->nullable();
            $table->string('permanent_dist')->nullable();
            $table->string('permanent_holding')->nullable();

            // Education (SSC)
            $table->string('ssc_institute')->nullable();
            $table->string('ssc_board')->nullable();
            $table->string('ssc_year')->nullable();
            $table->string('ssc_gpa')->nullable();
            $table->string('ssc_group')->nullable();

            // Education (HSC)
            $table->string('hsc_institute')->nullable();
            $table->string('hsc_board')->nullable();
            $table->string('hsc_year')->nullable();
            $table->string('hsc_gpa')->nullable();
            $table->string('hsc_group')->nullable();

            // Education (Undergrad)
            $table->string('undergrad_institute')->nullable();
            $table->string('undergrad_board')->nullable();
            $table->string('undergrad_year')->nullable();
            $table->string('undergrad_gpa')->nullable();
            $table->string('undergrad_group')->nullable();
            $table->string('undergrad_department')->nullable();

            // Course
            $table->string('course_name')->nullable(); // Select option stored as string

            // Signatures (Paths)
            $table->string('student_signature')->nullable();
            $table->string('guardian_signature')->nullable();
            $table->string('authority_signature')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn([
                'registration_no',
                'name_bn',
                'dob',
                'gender',
                'blood_group',
                'religion',
                'class_days',
                'class_time',
                'father_name',
                'mother_name',
                'father_occupation',
                'mother_occupation',
                'father_phone',
                'mother_phone',
                'guardian_name',
                'guardian_phone',
                'present_village',
                'present_po',
                'present_ps',
                'present_dist',
                'present_holding',
                'permanent_village',
                'permanent_po',
                'permanent_ps',
                'permanent_dist',
                'permanent_holding',
                'ssc_institute',
                'ssc_board',
                'ssc_year',
                'ssc_gpa',
                'ssc_group',
                'hsc_institute',
                'hsc_board',
                'hsc_year',
                'hsc_gpa',
                'hsc_group',
                'undergrad_institute',
                'undergrad_board',
                'undergrad_year',
                'undergrad_gpa',
                'undergrad_group',
                'undergrad_department',
                'course_name',
                'student_signature',
                'guardian_signature',
                'authority_signature'
            ]);
        });
    }
};
