<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration{

    public function up(){
        Schema::create('customers', function(Blueprint $table){
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->unsignedBigInteger('client_id');
            $table->timestamps();

            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    public function down(){
        Schema::dropIfExists('customers');
    }
}
