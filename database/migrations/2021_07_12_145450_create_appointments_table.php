<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppointmentsTable extends Migration{

    public function up(){
        Schema::create('appointments', function(Blueprint $table){
            $table->increments('id');
            $table->string('name');
            $table->string('send_to');
            $table->dateTime('when');
            $table->integer('timezoneOffset')->nullable();
            $table->dateTime('notificationTime');
            $table->boolean('is_sms')->default(true);
            $table->unsignedBigInteger('payment_id');
            $table->timestamps();

            $table->foreign('payment_id')->references('id')->on('payments')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    public function down(){
        Schema::dropIfExists('appointments');
    }
}
