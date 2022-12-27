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
        Schema::create('user_batches', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('site_id')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->string('job_batch_id')->nullable();
            $table->string('batch_status')->nullable();
            $table->string('actions')->nullable();
            $table->timestamps();

            $table->foreign('job_batch_id')->references('id')->on('job_batches');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_batches');
    }
};
