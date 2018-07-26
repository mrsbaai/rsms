<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddoperationidToLog extends Migration
{
    public function up()
    {
        Schema::table('paymentlog', function($table) {
            $table->string('operation_id')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('paymentlog', function($table) {
            $table->dropColumn('operation_id');
        });
    }
}
