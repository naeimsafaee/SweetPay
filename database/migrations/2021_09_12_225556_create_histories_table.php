<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoriesTable extends Migration{

    public function up(){
        Schema::create('histories', function(Blueprint $table){
            $table->id();
            $table->unsignedBigInteger('payment_id');
            $table->unsignedBigInteger('customer_id');
            $table->boolean('has_paid')->default(false);
            $table->timestamps();
        });
    }

    public function down(){
        Schema::dropIfExists('histories');
    }
}
