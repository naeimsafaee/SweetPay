<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSecretToPaymentsTable extends Migration{

    public function up(){
        Schema::table('payments', function(Blueprint $table){
            $table->text('secret')->nullable()->after('link');
        });
    }

    public function down(){
        Schema::table('payments', function(Blueprint $table){
            //
        });
    }
}
