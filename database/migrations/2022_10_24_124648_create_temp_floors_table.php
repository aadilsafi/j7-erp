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
        Schema::create('temp_floors', function (Blueprint $table) {
            $table->id();
            $table->longText('import-data')->nullable();
            $table->string('name')->nullable();
            $table->float('floor_area')->default(0);
            $table->string('short_label', 10)->default('F');
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
        Schema::dropIfExists('temp_floors');
    }
};
