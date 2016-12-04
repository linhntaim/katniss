<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Katniss\Everdeen\Vendors\Laravel\Framework\Illuminate\Database\Schema\Blueprint;

class CreatePosts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned();
            $table->string('template')->nullable();
            $table->string('featured_image')->nullable();
            $table->tinyInteger('type')->unsigned()->default(0); // 1 = PAGE;
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('post_translations', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->rowFormat = 'DYNAMIC';

            $table->bigIncrements('id');
            $table->bigInteger('post_id')->unsigned();
            $table->string('locale')->index();
            $table->string('title');
            $table->string('slug')->index();
            $table->string('description')->nullable();
            $table->longText('content');

            $table->unique(['post_id', 'locale']);
            $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('post_translations');
        Schema::dropIfExists('posts');
    }
}
