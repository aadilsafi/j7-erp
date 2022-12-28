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
        Schema::create('stakeholder_next_of_kin', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('site_id')->nullable();
            $table->bigInteger('stakeholder_id')->nullable();
            $table->bigInteger('kin_id')->nullable();
            $table->string('relation')->nullable();
            $table->boolean('is_imported')->default(false);
            $table->string('comments')->nullable();
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
        Schema::dropIfExists('stakeholder_next_of_kin');
    }
};
