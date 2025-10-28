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
        // If you saved role as 'role', but want to use 'reporter_role' consistently
        if (!Schema::hasColumn('incidents', 'reporter_role')) {
            $table->string('reporter_role')->nullable()->after('status');
        }

        // Add 'type' column to store the incident type (accident, hazard, etc.)
        if (!Schema::hasColumn('incidents', 'type')) {
            $table->string('type')->nullable()->after('title');
        }

        // Optionally, drop the old 'role' column if you're replacing it
        // $table->dropColumn('role');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('incidents', function (Blueprint $table) {
            //
        });
    }
};
