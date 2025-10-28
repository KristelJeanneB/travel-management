<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateSensorsTable extends Migration
{
    public function up()
    {
        Schema::create('sensors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        DB::table('sensors')->insert([
            [
                'name' => 'Route A - West Approach',
                'latitude' => 16.029969,
                'longitude' => 120.227346,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Route B - South Approach',
                'latitude' => 16.030020,
                'longitude' => 120.227739,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Route C - North Approach',
                'latitude' => 16.030323,
                'longitude' => 120.227743,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Route D - East Approach',
                'latitude' => 16.030192,
                'longitude' => 120.228118,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('sensors');
    }
}
