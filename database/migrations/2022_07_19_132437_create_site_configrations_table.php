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
        Schema::create('site_configrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id')->constrained();
            $table->tinyInteger('site_max_floors')->default(0);
            $table->string('floor_prefix', 5)->default('F');
            $table->tinyInteger('unit_number_digits')->default(2);
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
        Schema::dropIfExists('site_configrations');
    }
};
