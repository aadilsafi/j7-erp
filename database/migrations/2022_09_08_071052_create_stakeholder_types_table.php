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
        Schema::create('stakeholder_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stakeholder_id')->constrained();
            $table->string('type')->nullable();
            $table->unique(['stakeholder_id', 'type']);
            $table->string('stakeholder_code')->nullable();
            $table->json('receivable_account')->nullable();
            $table->string('payable_account')->nullable();
            $table->boolean('status');
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
        Schema::dropIfExists('stakeholder_types');
    }
};
