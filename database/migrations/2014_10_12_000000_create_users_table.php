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
            $table->bigIncrements('id');
            $table->string('firstname');
            $table->string('lastname');
            $table->string('email')->unique();
			$table->timestamp('email_verified_at')->nullable();
            $table->string('username');
            $table->string('password');
			$table->string('phone');
			$table->integer('state_id')->unsigned()->nullable();
			$table->integer('country_id')->unsigned()->nullable();
            $table->tinyInteger('verified')->default(1);
            $table->tinyInteger('enabled')->default(1);
            $table->rememberToken();
            $table->softDeletes();
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
