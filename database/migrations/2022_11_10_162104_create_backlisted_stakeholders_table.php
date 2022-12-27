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
        Schema::create('backlisted_stakeholders', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('fatherName')->nullable();
            $table->string('cnic')->nullable();
            $table->string('province')->nullable();
            $table->string('district')->nullable();
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
        Schema::dropIfExists('backlisted_stakeholders');
    }
};
