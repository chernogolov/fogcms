<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChargeDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('charge_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('charge_id')->index();
            $table->string('ServiceName')->nullable();
            $table->integer('Tariff')->index();
            $table->integer('WelfareCharge')->index();
            $table->integer('HouseVolume')->index();
            $table->integer('IndividualVolume')->index();
            $table->integer('HouseCharge')->index();
            $table->integer('HouseRecalculation')->index();
            $table->integer('IndividualCharge')->index();
            $table->integer('IndividualRecalculation')->index();
            $table->integer('BudgetaryChargePart')->index();
            $table->integer('TotalCharge')->index();
            $table->timestamps();

            $table->foreign('charge_id')->references('id')->on('charges')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('charge_details');
    }
}
