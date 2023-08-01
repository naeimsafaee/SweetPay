<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWalletToClientsTable extends Migration{

    public function up(){
        Schema::table('clients', function(Blueprint $table){
            $table->string('wallet')->default(0)->after('phone');
            $table->timestamp('requested_at')->nullable()->after('wallet');
            $table->unsignedBigInteger('wallet_id')->nullable()->after('requested_at');
        });
    }

    public function down(){
        Schema::table('clients', function(Blueprint $table){
            //
        });
    }
}
