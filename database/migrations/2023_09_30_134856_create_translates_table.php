<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateTranslatesTable.
 */
class CreateTranslatesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('translates', function(Blueprint $table) {
            $table->id();
            $table->string('language', 10);
            $table->unsignedBigInteger('reference_id')->index();
            $table->string('reference_type')->index();
            $table->longText('data');
            $table->timestamps();

            $table->foreign('language')->on('languages')->references('code')->cascadeOnDelete();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('translates');
	}
}
