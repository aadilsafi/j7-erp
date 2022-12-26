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
        Schema::create('unit_stakeholders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id')->constrained();
            $table->bigInteger('unit_id')->constrained();
            $table->bigInteger('stakeholder_id');
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
        Schema::dropIfExists('unit_stakeholders');
    }
};
