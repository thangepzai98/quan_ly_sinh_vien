<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateAssignmentsTable.
 */
class CreateAssignmentsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('assignments', function(Blueprint $table) {
            $table->increments('id');
			$table->integer('subject_id')->unsigned();
			$table->integer('class_id')->unsigned();
			$table->integer('lecturer_id')->unsigned();
			$table->tinyInteger('semester')->comment('học kỳ');
			$table->foreign('subject_id')->references('id')->on('subjects');
			$table->foreign('class_id')->references('id')->on('classes');
			$table->foreign('lecturer_id')->references('id')->on('lecturers');
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
		Schema::drop('assignments');
	}
}
