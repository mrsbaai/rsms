<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNumbersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     *


     */
    public function up()
    {
        Schema::create('numbers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('number', 14)->unique();
            $table->string('country', 2)->default("us");
            $table->dateTime('expiration')->default('2030-09-20');
            $table->dateTime('network_expiration')->nullable()->default(null);
            $table->boolean('is_private')->default(true);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_removed')->default(false);
            $table->mediumText('network')->nullable()->default(null);
            $table->string('network_login')->nullable()->default(null);
            $table->string('network_password')->nullable()->default(null);
            $table->string('email')->nullable()->default(null);
            $table->dateTime('last_checked')->nullable()->default(null);;

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
        Schema::dropIfExists('numbers');
    }
}
