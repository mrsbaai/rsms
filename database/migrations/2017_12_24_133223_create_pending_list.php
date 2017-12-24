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
            $table->date('sendingdate')->nullable()->default(null);
            $table->string('email');
            $table->string('subject')->nullable()->default(null);

            $table->string('heading1')->nullable()->default(null);
            $table->string('heading2')->nullable()->default(null);
            $table->string('heading3')->nullable()->default(null);
            $table->string('heading4')->nullable()->default(null);
            $table->string('text1')->nullable()->default(null);
            $table->string('text2')->nullable()->default(null);
            $table->string('text3')->nullable()->default(null);
            $table->string('text4')->nullable()->default(null);
            $table->string('button1')->nullable()->default(null);
            $table->string('button2')->nullable()->default(null);
            $table->string('button3')->nullable()->default(null);
            $table->string('buttonURL1')->nullable()->default(null);
            $table->string('buttonURL2')->nullable()->default(null);
            $table->string('buttonURL3')->nullable()->default(null);
            $table->string('img1')->nullable()->default(null);
            $table->string('img2')->nullable()->default(null);
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
        Schema::dropIfExists('pendinglist');
    }

}
