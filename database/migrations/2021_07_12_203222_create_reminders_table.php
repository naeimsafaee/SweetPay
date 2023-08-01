<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRemindersTable extends Migration{

    public function up(){
        Schema::create('reminders', function(Blueprint $table){
            $table->id();
            $table->unsignedBigInteger('client_id');
            $table->boolean('is_3_day')->default(false);
            $table->boolean('is_7_day')->default(false);
            $table->text('template');
            $table->boolean('is_sms')->default(false);
            $table->boolean('is_email')->default(false);
            $table->timestamps();

            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    public function down(){
        Schema::dropIfExists('reminders');
    }
}
