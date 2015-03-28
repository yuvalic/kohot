<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class KohotInitialSchema extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('players', function(Blueprint $table){
			$table->increments('id');
			$table->string('first_name',40);
			$table->string('last_name',40);
			$table->date('birth_date');
			$table->float('rank');
			$table->string('avatar',255);
			$table->enum('dominant_foot',['L','R']);
			$table->string('email')->unique();
			$table->string('phone',20)->nullable();
			$table->string('address',255)->nullable();
		});

		Schema::create('teams', function(Blueprint $table){
			$table->increments('id');
			$table->string('hash',70)->index();
			$table->integer('wins_count')->unsigned();
			$table->integer('games_count')->unsigned();
			$table->integer('goals_count')->unsigned()->default(0);
		});

		Schema::create('tournaments', function(Blueprint $table){
			$table->increments('id');
			$table->date('date');
			$table->string('weather')->nullable();
			$table->string('location')->nullable();
		});

		Schema::create('player_team', function(Blueprint $table){
			$table->string('position')->nullable();
			$table->integer('player_id')->unsigned();
			$table->integer('team_id')->unsigned();
			$table->foreign('player_id')
				  ->references('id')
				  ->on('players')
				  ->onDelete('cascade');
			$table->foreign('team_id')
				  ->references('id')
				  ->on('teams')
				  ->onDelete('cascade');		
		});

		Schema::create('games', function(Blueprint $table){
			$table->increments('id');
			$table->datetime('time');
			$table->integer('team_a_id')->unsigned();
			$table->foreign('team_a_id')
				  ->references('id')
				  ->on('teams')
				  ->onDelete('cascade');
			$table->integer('team_b_id')->unsigned();
			$table->foreign('team_b_id')
				  ->references('id')
				  ->on('teams')
				  ->onDelete('cascade');
			$table->integer('tournament_id')->unsigned();
			$table->foreign('tournament_id')
				  ->references('id')
				  ->on('tournaments')
				  ->onDelete('cascade');
			$table->integer('winner')->unsigned();
			$table->foreign('winner')
				  ->references('id')
				  ->on('teams')
				  ->onDelete('cascade');
			$table->tinyInteger('team_a_score')->unsigned();
			$table->tinyInteger('team_b_score')->unsigned();
		});

		Schema::create('player_in_game', function(Blueprint $table){
			$table->tinyInteger('goals')->unsigned()->default(0);
			$table->integer('player_id')->unsigned();
			$table->foreign('player_id')
				  ->references('id')
				  ->on('players')
				  ->onDelete('cascade');
			$table->integer('game_id')->unsigned();
			$table->foreign('game_id')
				  ->references('id')
				  ->on('games')
				  ->onDelete('cascade');		
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('player_in_team');
		Schema::drop('games');
		Schema::drop('player_team');
		Schema::drop('players');
		Schema::drop('teams');
		Schema::drop('tournaments');

	}

}
