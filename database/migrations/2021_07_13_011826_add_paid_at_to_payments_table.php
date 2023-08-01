<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaidAtToPaymentsTable extends Migration{

    public function up(){
        Schema::table('payments', function(Blueprint $table){
            $table->date('paid_at')->nullable()->after('secret');
        });
    }

    public function down(){
        Schema::table('payments', function(Blueprint $table){
            //
        });
    }
}
