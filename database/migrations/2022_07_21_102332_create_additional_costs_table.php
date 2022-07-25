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
        Schema::create('additional_costs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id')->constrained();
            $table->string('name')->nullable();
            $table->string('slug')->unique();
            $table->integer('parent_id')->default(0);
            $table->boolean('has_child')->default(false);
            $table->float('site_percentage')->default(0);
            $table->boolean('applicable_on_site')->default(0);
            $table->float('floor_percentage')->default(0);
            $table->boolean('applicable_on_floor')->default(0);
            $table->float('unit_percentage')->default(0);
            $table->boolean('applicable_on_unit')->default(0);
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
        Schema::dropIfExists('additional_costs');
    }
};
