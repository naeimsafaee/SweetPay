<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddItemsToPaymentsTable extends Migration{

    public function up(){
        Schema::table('payments', function(Blueprint $table){
            $table->string('link')->unique()->after('pdf_file');
        });
    }

    public function down(){
        Schema::table('payments', function(Blueprint $table){
            //
        });
    }
}
