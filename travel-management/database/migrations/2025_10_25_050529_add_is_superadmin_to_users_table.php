<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsSuperadminToUsersTable extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('users', 'is_superadmin')) {
            Schema::table('users', function (Blueprint $table) {
                $table->boolean('is_superadmin')->default(false);
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('users', 'is_superadmin')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('is_superadmin');
            });
        }
    }
}
