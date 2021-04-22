<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPunchTime extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('punch_times', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->dateTime('on_time');
            $table->dateTime('off_time')->nullable();
            $table->string('on_ip');
            $table->string('off_ip')->nullable();
            $table->string('on_remark')->nullable();
            $table->string('off_remark')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('punch_times');
    }
}
