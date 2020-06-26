<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateAreasTable extends Migration
{
    public function up()
    {
        DB::beginTransaction();

        Schema::create('divisions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('division_name')->unique();
             $table->timestamps();
        });

        Schema::create('districts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('division_id');
            $table->string('district_name')->unique();
            $table->timestamps();

            $table->foreign('division_id')->references('id')->on('divisions') ->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('upazilas', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('district_id');
            $table->string('upazila_name');
            $table->timestamps();

            $table->foreign('district_id')->references('id')->on('districts') ->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('unions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('upazila_id');
            $table->string('union_name');
            $table->timestamps();

            $table->foreign('upazila_id')->references('id')->on('upazilas') ->onUpdate('cascade')->onDelete('cascade');
        });

        DB::commit();
    }

    public function down()
    {
        Schema::dropIfExists('unions');
        Schema::dropIfExists('upazilas');
        Schema::dropIfExists('districts');
        Schema::dropIfExists('divisions');
    }
}
