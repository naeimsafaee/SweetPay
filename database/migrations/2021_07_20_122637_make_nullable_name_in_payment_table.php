<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeNullableNameInPaymentTable extends Migration{

    public function up(){
        Schema::table('payments', function(Blueprint $table){
            $table->string('name')->nullable()->change();
        });
    }

    public function down(){
        Schema::table('payment', function(Blueprint $table){
            //
        });
    }
}
