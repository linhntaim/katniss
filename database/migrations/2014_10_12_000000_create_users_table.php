<?php

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
            $table->engine = 'InnoDB';
            $table->rowFormat = 'DYNAMIC';

            $table->bigIncrements('id');
            $table->string('display_name');
            $table->string('name')->unique();
            $table->string('email')->unique();
            $table->string('password', 60);
            $table->string('url_avatar');
            $table->string('url_avatar_thumb');
            $table->string('activation_code')->default('');
            $table->boolean('active')->default(false);
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('user_socials', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->rowFormat = 'DYNAMIC';

            $table->bigInteger('user_id')->unsigned();
            $table->string('provider');
            $table->string('provider_id');
            $table->unique(['provider', 'provider_id']);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('user_socials');
        Schema::drop('users');
    }
}
