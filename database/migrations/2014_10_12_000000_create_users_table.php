<?php

use Katniss\Models\Helpers\Database\Blueprint;
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
        Schema::create('user_settings', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->rowFormat = 'DYNAMIC';

            $table->bigIncrements('id');
            $table->string('locale')->default('en');
            $table->string('country')->default('US');
            $table->string('timezone')->default('UTC');
            $table->string('currency')->default('USD');
            $table->string('number_format')->default('point_comma');
            $table->tinyInteger('first_day_of_week')->unsigned()->default(0);
            $table->tinyInteger('long_date_format')->unsigned()->default(0);
            $table->tinyInteger('short_date_format')->unsigned()->default(0);
            $table->tinyInteger('long_time_format')->unsigned()->default(0);
            $table->tinyInteger('short_time_format')->unsigned()->default(0);
            $table->timestamps();
        });

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
            $table->bigInteger('setting_id')->unsigned();
            $table->string('channel');
            $table->rememberToken();
            $table->timestamps();

            $table->foreign('setting_id')->references('id')->on('user_settings');
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
        Schema::drop('user_settings');
    }
}
