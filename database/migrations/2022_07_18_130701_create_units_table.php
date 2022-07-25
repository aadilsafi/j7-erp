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
            $table->string('name')->nullable();
            $table->float('width')->default(0);
            $table->float('length')->default(0);
            $table->tinyInteger('unit_number')->default(0);
            $table->foreignId('agent_id')->constrained();
            $table->boolean('is_corner')->default(0);
            $table->float('corner_percentage')->default(0);
            $table->float('corner_amount')->default(0);
            $table->boolean('is_facing')->default(0);
            $table->foreignId('facing_id')->constrained();
            $table->float('facing_percentage')->default(0);
            $table->float('facing_amount')->default(0);
            $table->foreignId('type_id')->constrained();
            $table->foreignId('status_id')->constrained();
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
