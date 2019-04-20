<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_records', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('record_id')->unsigned()->index();
            $table->integer('user_id')->unsigned()->index();

            $table->foreign('record_id')->references('id')->on('records')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_records');
    }
}
