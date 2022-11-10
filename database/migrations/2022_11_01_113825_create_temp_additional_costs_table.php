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
        Schema::create('temp_additional_costs', function (Blueprint $table) {
            $table->id();
            $table->string('additional_costs_name')->unique();
            $table->float('site_percentage')->default(0);
            $table->float('floor_percentage')->default(0);
            $table->float('unit_percentage')->default(0);
            $table->string('is_sub_types')->default(false);
            $table->string('parent_type_name');
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
        Schema::dropIfExists('temp_additional_costs');
    }
};
