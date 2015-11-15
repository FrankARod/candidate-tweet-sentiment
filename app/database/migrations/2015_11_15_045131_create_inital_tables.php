<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateInitalTables extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('candidates', function(Blueprint $table)
		{
			$table->increments('id');
            $table->string('name');
            $table->string('twitter_handle');
			$table->timestamps();
		});

        Schema::create('raw_tweets', function (Blueprint $table) {
            $table->increments('id');
            $table->text('text');
            $table->string('timestamp');
            $table->timestamps();
        });

        Schema::create('processed_tweets', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('candidate_id')->unsigned();
            $table->foreign('candidate_id')->references('id')->on('candidates');
            $table->text('text');
            $table->timestamp('tweeted_at');
            $table->decimal('sentiment', 7, 6);
            $table->text('type');
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
        Schema::drop('processed_tweets');
        Schema::drop('raw_tweets');
        Schema::drop('candidates');
	}

}
