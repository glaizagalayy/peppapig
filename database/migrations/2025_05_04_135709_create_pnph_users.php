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
        Schema::create('pnph_users', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->string('login_id', 50)->unique(); // Login ID for admin, finance, student
            $table->string('email', 100)->unique();
            $table->string('password');
            $table->string('role', 20); // 'admin', 'finance', 'student'
            $table->timestamps(); // Adds created_at and updated_at columns
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pnph_users');
    }
};
