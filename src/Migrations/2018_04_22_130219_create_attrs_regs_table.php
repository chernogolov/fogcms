<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttrsRegsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('attrs_regs', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('attr_id')->unsigned()->index();
            $table->integer('regs_id')->unsigned()->index();
            $table->integer('count_values')->default(0);
            $table->tinyInteger('is_filter')->index()->default(0);
            $table->tinyInteger('is_public')->index()->default(0);

            $table->foreign('attr_id')->references('id')->on('attrs')->onDelete('cascade');
            $table->foreign('regs_id')->references('id')->on('regs')->onDelete('cascade');

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
        Schema::drop('attrs_regs');
    }
}
