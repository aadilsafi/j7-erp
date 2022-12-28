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
        Schema::create('accounting_starting_codes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('site_id')->nullable();
            $table->string('model');
            $table->string('level_code');
            $table->string('starting_code');
            $table->tinyInteger('level');
            $table->boolean('status');
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
        Schema::dropIfExists('accounting_starting_codes');
    }
};
