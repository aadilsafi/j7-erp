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
        Schema::create('account_heads', function (Blueprint $table) {
            $table->foreignId('site_id')->constrained();
            $table->nullableMorphs('modelable');
            $table->string('code')->primary();
            $table->string('name')->nullable();
            $table->string('account_type')->nullable();
            $table->string('opening_balance')->nullable();
            $table->boolean('status')->default(0);
            $table->tinyInteger('level')->default(1);
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
        Schema::dropIfExists('account_heads');
    }
};
