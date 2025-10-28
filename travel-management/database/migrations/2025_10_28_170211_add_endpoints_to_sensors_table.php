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
    Schema::table('sensors', function (Blueprint $table) {
        $table->decimal('start_lat', 10, 8)->nullable()->after('longitude');
        $table->decimal('start_lng', 11, 8)->nullable()->after('start_lat');
        $table->decimal('end_lat', 10, 8)->nullable()->after('start_lng');
        $table->decimal('end_lng', 11, 8)->nullable()->after('end_lat');
    });
}

public function down()
{
    Schema::table('sensors', function (Blueprint $table) {
        $table->dropColumn(['start_lat', 'start_lng', 'end_lat', 'end_lng']);
    });
}
};
