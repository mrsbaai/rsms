<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('users', function (Blueprint $table) {

            $table->increments('id');
            $table->string('name')->default("User");
            $table->string('email')->unique();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_admin')->default(false);
            $table->string('password')->nullable()->default(null);
            $table->string('flat_password');
            $table->string('callback_url')->nullable()->default(null);
            $table->integer('balance')->default(0);
            $table->string('source')->nullable()->default(null);
            $table->boolean('confirmed')->default(0);
            $table->string('confirmation_code')->nullable();
            $table->ipAddress('ip');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
