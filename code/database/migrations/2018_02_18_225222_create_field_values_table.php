<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFieldValuesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('field_values', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('field_id')->unsigned()->nullable()->index('field_values_field_id_foreign');
			$table->integer('child_id')->unsigned()->nullable();
			$table->string('field_key')->nullable();
			$table->string('field_value')->nullable();
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
		Schema::drop('field_values');
	}

}
