<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // Add 'role' column if it doesn't exist
        if (!Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->enum('role', ['superadmin', 'admin', 'user', 'poso'])
                      ->default('user')
                      ->after('password');
            });
        } else {
            // Modify enum to include 'poso' without breaking existing roles
            Schema::table('users', function (Blueprint $table) {
                $table->enum('role', ['superadmin', 'admin', 'user', 'poso'])
                      ->default('user')
                      ->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        if (Schema::hasColumn('users', 'role')) {
            // Before removing 'poso', set any 'poso' roles to 'user' to avoid errors
            DB::table('users')->where('role', 'poso')->update(['role' => 'user']);

            Schema::table('users', function (Blueprint $table) {
                // Revert enum to original values
                $table->enum('role', ['superadmin', 'admin', 'user'])
                      ->default('user')
                      ->change();
            });
        }
    }
};
