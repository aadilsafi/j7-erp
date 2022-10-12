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
        Schema::create('custom_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id')->constrained();
            $table->string('name', 127);
            $table->string('type', 56);
            $table->string('values')->nullable();
            $table->boolean('disabled')->nullable();
            $table->boolean('required')->nullable();
            $table->boolean('in_table')->nullable();
            $table->boolean('readonly')->nullable();
            $table->boolean('multiple')->nullable();
            $table->tinyInteger('min')->nullable();
            $table->tinyInteger('max')->nullable();
            $table->tinyInteger('minlength')->nullable();
            $table->tinyInteger('maxlength')->nullable();
            $table->tinyInteger('bootstrap_column')->nullable();
            $table->tinyInteger('order')->nullable();
            $table->string('custom_field_model');
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
        Schema::dropIfExists('custom_fields');
    }
};
