<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temp_units', function (Blueprint $table) {
            $table->id();
            $table->string('floor_short_label');
            $table->string('name');
            $table->float('width');
            $table->float('length');
            $table->string('unit_short_label');
            $table->float('net_area');
            $table->float('gross_area');
            $table->float('price_sqft');
            $table->float('total_price');
            $table->string('unit_type_slug');
            $table->string('status');
            $table->string('parent_unit_short_label');
            $table->string('is_corner')->default('no');
            $table->string('is_facing')->default('no');
            $table->string('additional_costs_name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('temp_units');
    }
};
