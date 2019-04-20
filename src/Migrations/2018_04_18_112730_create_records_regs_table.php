<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecordsRegsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        // create relations for tickets directories
        Schema::create('records_regs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('records_id')->unsigned();
            $table->foreign('records_id')->references('id')->on('records')->onDelete('cascade');
            $table->integer('regs_id')->unsigned();
            $table->foreign('regs_id')->references('id')->on('regs')->onDelete('cascade');
            $table->index('records_id');
            $table->index('regs_id');
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
        Schema::drop('records_regs');
    }
}
