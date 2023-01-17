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
        Schema::create('file_stakeholder_contacts', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('site_id');
            $table->bigInteger('file_management_id');
            $table->bigInteger('stakeholder_contact_id');
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
        Schema::dropIfExists('file_stakeholder_contacts');
    }
};
