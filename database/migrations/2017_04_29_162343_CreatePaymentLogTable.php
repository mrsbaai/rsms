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
            $table->double('payedAmount');
            $table->double('originalAmount');
            $table->string('code')->default('');
            $table->string('type')->nullable();
            $table->string('status')->nullable();
            $table->string('source')->nullable()->default(null);
            $table->string('userEmail');
            $table->string('buyerEmail');
            $table->string('accountId');
            $table->string('paymentSystemId')->default('PayPal');
            $table->string('operation_id')->nullable()->default(null);
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
