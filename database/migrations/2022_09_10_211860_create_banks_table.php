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
        Schema::create('banks', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('site_id')->nullable();
            $table->string('name')->nullable();
            $table->string('slug')->nullable();
            $table->string('account_number')->nullable();
            $table->string('branch')->nullable();
            $table->string('branch_code')->nullable();
            $table->string('address')->nullable();
            $table->string('contact_number')->nullable();
            $table->boolean('status')->default(true);
            $table->string('comments')->nullable();
            $table->string('account_head_code')->nullable();
            $table->json('contact_details')->nullable();
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
        Schema::dropIfExists('banks');
    }
};
