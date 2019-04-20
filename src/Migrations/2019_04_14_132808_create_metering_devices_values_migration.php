<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMeteringDevicesValuesMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('metering_devices_values', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('account_number')->index();
            $table->dateTime('SendDate')->index();
            $table->float('Value');
            $table->string('ValueSource')->nullable();
            $table->string('CalculationMethodDescription')->nullable();
            $table->dateTime('ValueDate')->index();
            $table->string('ServiceName')->index();
            $table->string('MeteringDeviceModel')->nullable();
            $table->string('MeteringDeviceNumber')->index();
            $table->float('Volume')->nullable();
            $table->string('ScaleName')->nullable();
            $table->timestamps();

            $table->unique(['ValueDate', 'MeteringDeviceNumber','ScaleName'], 'vd-mdn-sn');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('metering_devices_values');
    }
}
