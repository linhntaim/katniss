<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Katniss\Models\UserSession;

class CreateSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sessions', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->rowFormat = 'DYNAMIC';

            $table->string('id')->unique();
            $table->text('payload');
            $table->integer('last_activity');
            $table->string('client_ip');
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->tinyInteger('status')->unsigned()->default(UserSession::STATUS_OFFLINE);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('sessions');
    }
}
