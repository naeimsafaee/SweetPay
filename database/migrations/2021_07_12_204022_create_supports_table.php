<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupportsTable extends Migration{

    public function up(){
        Schema::create('clients_supports', function(Blueprint $table){
            $table->id();
            $table->unsignedBigInteger('client_id');
            $table->text('text');
            $table->boolean('is_admin')->default(true);
            $table->timestamps();

            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    public function down(){
        Schema::dropIfExists('supports');
    }
}
