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
        Schema::create('stakeholder_contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stakeholder_id')->constrained('stakeholders');
            $table->string('full_name', 50)->nullable();
            $table->string('father_name', 50)->nullable();
            $table->string('occupation', 50)->nullable();
            $table->string('designation', 50)->nullable();
            $table->string('cnic', 15)->nullable();
            $table->string('ntn')->nullable();
            $table->string('contact', 20)->nullable();
            $table->string('address')->nullable();
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
        Schema::dropIfExists('stakeholder_contacts');
    }
};
