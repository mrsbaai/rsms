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
            $table->double('number', 14)->unique();
            $table->string('country', 2);
            $table->dateTime('expiration');
            $table->dateTime('network_expiration')->nullable()->default(null);
            $table->boolean('is_private');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_removed')->default(false);
            $table->string('network', 4);
            $table->string('network_login');
            $table->string('network_password');
            $table->string('email')->nullable();
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
