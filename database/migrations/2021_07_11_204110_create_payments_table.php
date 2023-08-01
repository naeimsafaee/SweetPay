<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration{

    public function up(){
        Schema::create('payments', function(Blueprint $table){
            $table->id();
            $table->string('name');
            $table->string('invoice');
            $table->string('amount');
            $table->unsignedBigInteger('customer_id');
            $table->text('address');
            $table->text('pdf_file')->nullable();
            $table->unsignedBigInteger('client_id');
            $table->integer('status')->default(1);
            $table->timestamps();

            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    public function down(){
        Schema::dropIfExists('payments');
    }
}
