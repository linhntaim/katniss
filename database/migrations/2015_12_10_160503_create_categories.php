<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->rowFormat = 'DYNAMIC';

            $table->increments('id');
            $table->integer('parent_id')->unsigned()->nullable();
            $table->integer('order')->unsigned()->default(0);
            $table->tinyInteger('type')->unsigned()->default(0); // 0 = BLOG
            $table->timestamps();

            $table->foreign('parent_id')->references('id')->on('categories');
        });

        Schema::create('category_translations', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->rowFormat = 'DYNAMIC';

            $table->increments('id');
            $table->integer('category_id')->unsigned();
            $table->string('locale')->index();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description');

            $table->unique(['category_id', 'locale']);
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('category_translations');
        Schema::drop('categories');
    }
}
