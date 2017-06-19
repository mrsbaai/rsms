<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentlogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paymentlog', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('payedAmount');
            $table->integer('originalAmount');
            $table->string('code');
            $table->string('type')->nullable();
            $table->string('status')->nullable();
            $table->string('userEmail');
            $table->string('buyerEmail');
            $table->string('accountId');
            $table->string('paymentSystemId')->default(0);
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
        Schema::dropIfExists('paymentlog');
    }
}
