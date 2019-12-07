<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModulosCliente extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_modulesclient', function (Blueprint $table) {
            //
            $table->increments('mocl_id');
            $table->integer('clie_id')->unsigned();
            $table->foreign('clie_id')->references('clie_id')->on('tbl_clients')->onDelete('cascade');
            $table->integer('subm_id')->unsigned();
            $table->foreign('subm_id')->references('subm_id')->on('tbl_submodules')->onDelete('cascade');
            $table->integer('mocl_status');
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
        Schema::dropIfExists('tbl_modulesclient');
    }
}
