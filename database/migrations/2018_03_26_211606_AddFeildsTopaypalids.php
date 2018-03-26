<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFeildsTopaypalids extends Migration
{

    public function up()
    {
    Schema::table('paypalids', function($table) {
        $table->integer('balance')->nullable()->default(0);
        $table->string('notes')->nullable()->default("");
        $table->boolean('is_disposable')->default(false);
        $table->boolean('is_limited')->default(false);
    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('paypalids', function($table) {
            $table->dropColumn('balance');
            $table->dropColumn('notes');
            $table->dropColumn('is_disposable');
            $table->dropColumn('is_limited');
        });
    }
}
