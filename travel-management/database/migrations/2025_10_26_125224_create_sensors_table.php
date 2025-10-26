<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateSensorsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('sensors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->boolean('is_active')->default(true); // Optional: to enable/disable sensors
            $table->timestamps();
        });

        // Insert default 4 sensors if none exist
        DB::table('sensors')->insert([
            [
                'name' => 'Route A - West Approach',
                'latitude' => 16.029969002086467,
                'longitude' => 120.22734646082192,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Route B - South Approach',
                'latitude' => 16.030020362249363,
                'longitude' => 120.22773928488766,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Route C - North Approach',
                'latitude' => 16.030323444510252,
                'longitude' => 120.22774256414759,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Route D - East Approach',
                'latitude' => 16.030192476120416,
                'longitude' => 120.22811757860168,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('sensors');
    }
}