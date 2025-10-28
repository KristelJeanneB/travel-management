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
    Schema::table('incidents', function (Blueprint $table) {
        $table->unsignedBigInteger('user_id')->nullable()->after('id');
        $table->string('type')->nullable(); // e.g., 'accident'
        $table->string('reporter_role')->nullable(); // e.g., 'user' or 'poso'
        $table->string('unit')->nullable(); // POSO-only
        $table->string('badge_number')->nullable(); // POSO-only
    });
}

public function down()
{
    Schema::table('incidents', function (Blueprint $table) {
        $table->dropColumn(['user_id', 'type', 'reporter_role', 'unit', 'badge_number']);
    });
}
};
