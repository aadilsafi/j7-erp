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
        Schema::create('model_templates', function (Blueprint $table) {
            $table->id();
            $table->string('model_type')->nullable();
            $table->integer('template_id')->nullable();
            $table->integer('parent_id')->default(0);
            $table->boolean('default')->nullable();
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
        Schema::dropIfExists('model_templates');
    }
};
