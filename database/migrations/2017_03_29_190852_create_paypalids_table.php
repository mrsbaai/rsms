<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaypalidsTable extends Migration
{

    public function up()
    {
        Schema::create('paypalids', function (Blueprint $table) {
            $table->increments('id');
            $table->string('paypalid');
            $table->boolean('is_active')->default(true);
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
        Schema::dropIfExists('paypalids');
    }

}
