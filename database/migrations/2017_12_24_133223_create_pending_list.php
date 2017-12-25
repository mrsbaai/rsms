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
            $table->string('subject', '255')->nullable()->default(null);

            $table->string('heading1', '255')->nullable()->default(null);
            $table->string('heading2', '255')->nullable()->default(null);
            $table->string('heading3', '255')->nullable()->default(null);
            $table->string('heading4', '255')->nullable()->default(null);
            $table->string('text1', '255')->nullable()->default(null);
            $table->string('text2', '255')->nullable()->default(null);
            $table->string('text3', '255')->nullable()->default(null);
            $table->string('text4', '255')->nullable()->default(null);
            $table->string('button1', '255')->nullable()->default(null);
            $table->string('button2', '255')->nullable()->default(null);
            $table->string('button3', '255')->nullable()->default(null);
            $table->string('buttonURL1', '255')->nullable()->default(null);
            $table->string('buttonURL2', '255')->nullable()->default(null);
            $table->string('buttonURL3', '255')->nullable()->default(null);
            $table->string('img1', '255')->nullable()->default(null);
            $table->string('img2', '255')->nullable()->default(null);
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
