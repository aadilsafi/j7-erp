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
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('floor_id')->nullable();
            $table->string('name')->nullable();
            $table->float('width')->default(0);
            $table->float('length')->default(0);
            $table->integer('parent_id')->default(0);
            $table->boolean('has_sub_units')->default(false);
            $table->tinyInteger('unit_number')->default(0);
            $table->boolean('is_corner')->default(0);
            $table->unsignedBigInteger('corner_id')->nullable();
            $table->boolean('is_facing')->default(0);
            $table->unsignedBigInteger('facing_id')->nullable();
            $table->foreign('facing_id')->references('id')->on('additional_costs');
            $table->string('floor_unit_number')->nullable();
            $table->float('net_area')->default(0);
            $table->float('gross_area')->default(0);
            $table->float('price_sqft')->default(0);
            $table->double('total_price')->default(0);
            $table->bigInteger('type_id')->nullable();
            $table->bigInteger('status_id')->nullable();
            $table->boolean('is_for_rebate')->default(false);
            $table->boolean('active')->default(false);
            $table->json('unit_account')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('units');
    }
};
