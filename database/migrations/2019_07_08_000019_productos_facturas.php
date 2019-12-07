<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ProductosFacturas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_productsinvoices', function (Blueprint $table) {
            $table->increments('prin_id');
            $table->integer('tylc_id')->unsigned()->nullable();
            $table->foreign('tylc_id')->references('tylc_id')->on('tbl_typelinecode')->onDelete('cascade');
            $table->integer('tymu_id')->unsigned()->nullable();
            $table->foreign('tymu_id')->references('tymu_id')->on('tbl_typemeasureunit')->onDelete('cascade');
            $table->integer('tiva_id')->unsigned();
            $table->foreign('tiva_id')->references('tiva_id')->on('tbl_typeivatax')->onDelete('cascade');
            $table->integer('prin_exoneration')->nullable();
            $table->double('prin_discount')->nullable();
            $table->integer('prin_quantity')->unsigned();
            $table->double('prin_amount_bt');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_productsinvoices');
    }
}
