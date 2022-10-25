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
        Schema::create('types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id')->constrained();
            $table->string('name')->nullable();
            $table->string('slug')->unique();
            $table->integer('parent_id')->default(0);
            $table->boolean('status')->default(true);
            $table->boolean('account_added')->default(false);
            $table->string('account_number')->nullable();
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
        Schema::dropIfExists('types');
    }
};
