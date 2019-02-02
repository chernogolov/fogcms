<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttrsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attrs', function(Blueprint $table) {
            $table->increments('id');
            $table->string('type')->index();
            $table->string('modificator', 255)->nullable();
            $table->string('name', 255);
            $table->string('title', 255);
            $table->string('meta', 255)->nullable();
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
        Schema::drop('attrs');
    }
}
