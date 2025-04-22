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
        if (!Schema::hasColumn('pnph_users', 'password_reset_required')) {
            Schema::table('pnph_users', function (Blueprint $table) {
                $table->boolean('password_reset_required')->default(1)->after('password');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('pnph_users', 'password_reset_required')) {
            Schema::table('pnph_users', function (Blueprint $table) {
                $table->dropColumn('password_reset_required');
            });
        }
    }
};
