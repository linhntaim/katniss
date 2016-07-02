<?php

use Katniss\Models\Helpers\Database\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserNotifications extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_notifications', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->rowFormat = 'DYNAMIC';

            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned();
            $table->string('url_index');
            $table->string('url_params')->default('{}');
            $table->string('message_index');
            $table->string('message_params')->default('{}');
            $table->tinyInteger('type')->unsigned()->default(0);
            $table->boolean('read')->default(false);
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
        Schema::drop('user_notifications');
    }
}
