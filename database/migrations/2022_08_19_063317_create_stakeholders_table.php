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
        Schema::create('stakeholders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id')->constrained();
            $table->foreignId('country_id')->default(1)->nullable();
            $table->foreignId('state_id')->default(2)->nullable();
            $table->foreignId('city_id')->default(4)->nullable();
            $table->string('full_name', 50)->nullable();
            $table->string('father_name', 50)->nullable();
            $table->string('occupation', 50)->nullable();
            $table->string('designation', 50)->nullable();
            $table->string('cnic', 15)->nullable();
            $table->string('ntn')->nullable();
            $table->string('contact', 20)->nullable();
            $table->string('address')->nullable();
            $table->string('comments')->nullable();
            $table->integer('parent_id')->default(0);
            $table->string('relation')->nullable();
            $table->string('optional_contact_number')->nullable();
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
        Schema::dropIfExists('stakeholders');
    }
};
