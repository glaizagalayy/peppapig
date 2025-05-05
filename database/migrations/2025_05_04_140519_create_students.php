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
        Schema::create('students', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->string('student_id', 20)->unique(); // Unique student ID
            $table->string('first_name', 50);
            $table->string('last_name', 50);
            $table->string('middle_initial', 10)->nullable();
            $table->string('suffix', 10)->nullable();
            $table->string('email', 100)->nullable();
            $table->integer('batch_year');
            $table->integer('group_num');
            $table->integer('student_number');
            $table->char('center_training_code', 1);
            $table->integer('region_code');
            $table->string('temporary_password', 255)->nullable();
            $table->timestamps(); // Adds created_at and updated_at columns
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
