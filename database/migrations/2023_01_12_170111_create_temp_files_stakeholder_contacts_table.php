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
        Schema::create('temp_files_stakeholder_contacts', function (Blueprint $table) {
            $table->id();
            $table->string('file_doc_no')->nullable();
            $table->string('contact_cnic')->nullable();
            $table->string('kin_cnic')->nullable();
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
        Schema::dropIfExists('temp_files_stakeholder_contacts');
    }
};
