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
        Schema::create('temp_stakeholders', function (Blueprint $table) {
            $table->id();
            $table->string('full_name', 50)->nullable();
            $table->string('father_name', 50)->nullable();
            $table->string('occupation', 50)->nullable();
            $table->string('designation', 50)->nullable();
            $table->bigInteger('cnic')->nullable();
            $table->string('ntn')->nullable();
            $table->string('contact', 20)->nullable();
            $table->string('address')->nullable();
            $table->string('comments')->nullable();
            $table->bigInteger('parent_cnic')->nullable();
            $table->string('relation')->nullable();
            $table->boolean('is_dealer')->default(false);
            $table->boolean('is_vendor')->default(false);
            $table->boolean('is_customer')->default(false);
            $table->boolean('is_kin')->default(false);

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
        Schema::dropIfExists('temp_stakeholders');
    }
};
