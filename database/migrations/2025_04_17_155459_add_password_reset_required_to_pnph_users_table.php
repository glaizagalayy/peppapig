<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('pnph_users', function (Blueprint $table) {
            $table->boolean('password_reset_required')->default(true)->after('password');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('pnph_users', function (Blueprint $table) {
            $table->dropColumn('password_reset_required');
        });
    }
};
