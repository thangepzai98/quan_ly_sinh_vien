<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateScoresTable.
 */
class CreateScoresTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('scores', function(Blueprint $table) {
            $table->increments('id');
			$table->tinyInteger('score_1')->comment('Điểm chuyên cần');
			$table->tinyInteger('score_2')->comment('Điểm kiểm tra');
			$table->tinyInteger('score_3')->comment('Điểm thi');
			$table->tinyInteger('total_score')->comment('Điểm tổng');
			$table->integer('subject_id')->unsigned();
			$table->integer('student_id')->unsigned();
			$table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
			$table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
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
		Schema::drop('scores');
	}
}
