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
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            // Common fields
            $table->string('username')->unique();
            $table->string('password');
            $table->enum('role', ['admin', 'teacher', 'student']);

            // Admin-specific
            $table->string('admin_email')->unique()->nullable();
            $table->string('admin_security_code')->nullable();

            // Teacher-specific
            $table->string('teacher_name')->nullable();
            $table->string('teacher_email')->unique()->nullable();
            $table->string('teacher_position')->nullable();

            // Student-specific
            $table->string('student_name')->nullable();
            $table->string('student_lrn')->unique()->nullable();
            $table->string('student_grade')->nullable();
            $table->string('student_section')->nullable();

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
