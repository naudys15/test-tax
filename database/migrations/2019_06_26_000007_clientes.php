<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Clientes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_clients', function (Blueprint $table) {
            //
            $table->increments('clie_id');
            $table->integer('tycl_id')->unsigned();
            $table->foreign('tycl_id')->references('tycl_id')->on('tbl_typeclient')->onDelete('cascade');
            $table->string('clie_firstname', 100);
            $table->string('clie_lastname', 100)->nullable();
            $table->string('clie_email', 50);
            $table->string('clie_username', 50);
            $table->string('clie_password', 100);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_clients');
    }
}
