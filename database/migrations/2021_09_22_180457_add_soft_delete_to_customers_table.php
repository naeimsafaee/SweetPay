<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSoftDeleteToCustomersTable extends Migration
{

    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            //
        });
    }
}
