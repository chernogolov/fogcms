<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRegsUsersField1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('regs_users', function(Blueprint $table) {
            $table->tinyInteger('email')->default(0);
            $table->tinyInteger('push')->default(0);
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
        Schema::table('regs_users', function(Blueprint $table) {
            $table->dropColumn('email');
            $table->dropColumn('push');
        });
    }
}
