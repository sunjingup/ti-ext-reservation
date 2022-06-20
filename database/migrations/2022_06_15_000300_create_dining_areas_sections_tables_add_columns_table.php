<?php

namespace Igniter\Reservation\Database\Migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateDiningAreasSectionsTablesAddColumnsTable extends Migration
{
    public function up()
    {
        Schema::create('dining_areas', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->unsignedBigInteger('location_id');
            $table->string('name');
            $table->string('description')->nullable();
            $table->boolean('is_active')->default(0);
            $table->timestamps();
        });

        $this->createLocationDiningAreas();

        Schema::create('dining_tables', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->unsignedBigInteger('dining_area_id')->index();
            $table->unsignedBigInteger('dining_section_id')->index();
            $table->unsignedBigInteger('parent_id')->index()->nullable();
            $table->string('name');
            $table->integer('min_capacity')->default(0);
            $table->integer('max_capacity')->default(0);
            $table->integer('extra_capacity')->default(0);
            $table->boolean('is_enabled')->default(0);
            $table->integer('nest_left')->nullable();
            $table->integer('nest_right')->nullable();
            $table->integer('priority')->default(0);
            $table->timestamps();
        });

        $this->copyTablesIntoDiningTables();

        Schema::create('dining_sections', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->unsignedBigInteger('dining_area_id')->index();
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('color')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('dining_sections');
        Schema::dropIfExists('dining_tables');
        Schema::dropIfExists('dining_areas');
    }

    protected function createLocationDiningAreas()
    {
        if (!DB::table('tables')->count())
            return;

        DB::table('locations')->get()->each(function ($location) {
            DB::table('dining_areas')->insertGetId([
                'name' => 'Default',
                'location_id' => $location->location_id,
                'created_at' => $location->created_at,
                'updated_at' => $location->updated_at,
            ]);
        });
    }

    protected function copyTablesIntoDiningTables()
    {
        if (!DB::table('tables')->count())
            return;

        $diningAreas = DB::table('dining_areas')->pluck('id', 'location_id');

        DB::table('tables')->get()->each(function ($table) use ($diningAreas) {
            DB::table('locationables')
                ->where('locationable_type', 'tables')
                ->where('locationable_id', $table->table_id)
                ->get()->each(function ($locationable) use ($diningAreas, $table) {
                    DB::table('dining_tables')->insert([
                        'dining_area_id' => array_get($diningAreas, $locationable->location_id),
                        'name' => $table->table_name,
                        'min_capacity' => $table->min_capacity,
                        'max_capacity' => $table->max_capacity,
                        'extra_capacity' => $table->extra_capacity,
                        'is_enabled' => $table->table_status,
                        'priority' => $table->priority,
                        'created_at' => $table->created_at,
                        'updated_at' => $table->updated_at,
                    ]);
                });
        });
    }
}
