<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttrsMediaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('attrs_media', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('attr_id')->unsigned()->index();
            $table->integer('record_id')->unsigned()->index();
            $table->string('value', 255);
            $table->string('meta', 255)->nullable();

            $table->foreign('attr_id')->references('id')->on('attrs')->onDelete('cascade');
            $table->foreign('record_id')->references('id')->on('records')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::drop('attrs_media');
    }
}
