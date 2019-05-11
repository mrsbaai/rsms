<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateflatpeninglistTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flatpendinglist', function (Blueprint $table) {

            $table->increments('id');
            $table->date('sendingdate')->nullable()->default(null);
            $table->string('email');
            $table->string('subject', '255')->nullable()->default(null);
            $table->string('from_email')->nullable()->default(null);
            $table->string('from_name')->nullable()->default(null);
            $table->string('html')->nullable()->default(null);

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
        Schema::dropIfExists('flatpendinglist');
    }
}
