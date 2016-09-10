<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJokesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jokes', function (Blueprint $table)
        {
            $table->increments('id');
            $table->string('title');
            $table->string('content');
            $table->boolean('approved');

            $table->timestamps();
        });

        Schema::create('category_joke', function (Blueprint $table)
        {
            $table->unsignedInteger('joke_id')->index();
            $table->foreign('joke_id')->references('id')->on('jokes')->onDelete('cascade');

            $table->unsignedInteger('category_id')->index();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');

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
        Schema::drop('jokes');
        Schema::drop('category_joke');
    }
}
