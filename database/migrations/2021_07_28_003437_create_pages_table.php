<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagesTable extends Migration{

    public function up(){
        Schema::create('pages', function(Blueprint $table){
            $table->id();
            $table->string('slug');
            $table->text('text');
            $table->string('title');
            $table->timestamps();
        });
    }

    public function down(){
        Schema::dropIfExists('pages');
    }
}
