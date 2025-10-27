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
    Schema::table('users', function (Blueprint $table) {
        $table->enum('role', ['superadmin', 'admin', 'user'])->default('user')->after('password');

         // Remove old boolean columns (optional)
        $table->dropColumn(['is_admin', 'is_superadmin']);
    });
}

public function down()
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn('role');
        $table->boolean('is_admin')->default(false);
        $table->boolean('is_superadmin')->default(false);
    });
}
};
