<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ClientesInfoFiscal extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_clientproratainfo', function (Blueprint $table) {
            $table->increments('clpi_id');
            $table->integer('clie_id')->unsigned()->unique();
            $table->foreign('clie_id')->references('clie_id')->on('tbl_clients')->onDelete('cascade');
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
        Schema::dropIfExists('tbl_clientproratainfo');
    }
}
