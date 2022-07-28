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
        Schema::table('units', function (Blueprint $table) {
            $table->after('unit_number', function () use ($table) {
                $table->boolean('is_corner')->default(0);
                $table->unsignedBigInteger('corner_id')->nullable();
                $table->boolean('is_facing')->default(0);
                $table->unsignedBigInteger('facing_id')->nullable();

                $table->foreign('corner_id')->references('id')->on('additional_costs');
                $table->foreign('facing_id')->references('id')->on('additional_costs');
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('units', function (Blueprint $table) {
            $table->dropColumn(['is_corner', 'corner_id', 'is_facing', 'facing_id']);
        });
    }
};
