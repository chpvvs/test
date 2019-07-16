<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLanguages extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('languages', function($table){
			$table->engine = "InnoDB";
			$table->increments('id');
			$table->string('code', 2)->unique();
			$table->boolean('default')->default(0);
			$table->boolean('default_admin')->default(0);
			$table->boolean('hidden')->default(1);
			$table->string('name', 25)->nullable();
			$table->integer('order')->default(0);
		});
		DB::table('languages')->insert(
			array(
				'code' => 'ru',
				'default' => 1,
				'default_admin' => 1,
				'hidden' => 0,
				'name' => 'Русский'
			)
		);
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::drop('languages');
	}

}
