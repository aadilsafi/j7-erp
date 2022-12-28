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
            $table->bigInteger('site_id')->nullable();
            $table->string('name', 120)->nullable();
            $table->string('slug', 55)->unique();
            $table->string('type', 50)->nullable();
            $table->text('values')->nullable();
            $table->boolean('disabled')->default(false)->nullable();
            $table->boolean('required')->default(false)->nullable();
            $table->boolean('in_table')->default(false)->nullable();
            $table->boolean('multiple')->default(false)->nullable();
            $table->integer('min')->default(0)->nullable();
            $table->integer('max')->default(0)->nullable();
            $table->tinyInteger('minlength')->default(0)->nullable();
            $table->tinyInteger('maxlength')->default(0)->nullable();
            $table->tinyInteger('bootstrap_column')->default(6)->nullable();
            $table->tinyInteger('order')->default(0)->nullable();
            $table->string('custom_field_model')->nullable();
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
