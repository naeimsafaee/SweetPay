<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWalletsTable extends Migration{

    public function up(){
        Schema::create('wallets', function(Blueprint $table){
            $table->id();
            $table->string('name');
            $table->string('company_name');
            $table->string('bank_name');
            $table->string('registration_number');
            $table->string('bic_number');
            $table->string('iban_number');
            $table->string('vat_number');
            $table->text('address');
            $table->unsignedBigInteger('client_id');
            $table->timestamps();

            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    public function down(){
        Schema::dropIfExists('wallets');
    }
}
