<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePendingList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pendinglist', function (Blueprint $table) {

            $table->increments('id');
            $table->date('sendingdate');
            $table->string('email');
            $table->string('subject');

            $table->string('heading1');
            $table->string('heading2');
            $table->string('heading3');
            $table->string('heading4');
            $table->string('text1');
            $table->string('text2');
            $table->string('text3');
            $table->string('text4');
            $table->string('button1');
            $table->string('button2');
            $table->string('button3');
            $table->string('buttonURL1');
            $table->string('buttonURL2');
            $table->string('buttonURL3');
            $table->string('img1');
            $table->string('img2');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pendinglist');
    }

}
