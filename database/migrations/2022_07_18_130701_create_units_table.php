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
            $table->foreignId('floor_id')->constrained();
            $table->string('name')->nullable();
            $table->float('width')->default(0);
            $table->float('length')->default(0);
            $table->integer('parent_id')->default(0);
            $table->tinyInteger('unit_number')->default(0);
            $table->string('floor_unit_number')->nullable();
            $table->float('net_area')->default(0);
            $table->float('gross_area')->default(0);
            $table->float('price_sqft')->default(0);
            $table->double('total_price')->default(0);
            $table->foreignId('type_id')->constrained();
            $table->foreignId('status_id')->constrained();
            $table->boolean('active')->default(false);
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
