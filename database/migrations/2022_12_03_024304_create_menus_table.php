<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Enums\MenuPositionEnum;

/**
 * Class CreateMenusTable.
 */
class CreateMenusTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('appearance_menus', function(Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('parent_id')->nullable();
            $table->text('url');
            $table->json('name');
            $table->string('icon')->nullable();
            $table->integer('position')->default(MenuPositionEnum::PRIMARY_MENU);
            $table->integer('order')->default(0);
            $table->boolean('active')->default(true);
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
		Schema::drop('appearance_menus');
	}
}
