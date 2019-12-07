<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ImpuestosFacturas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('tbl_taxesinvoices', function (Blueprint $table) {
        //     $table->increments('tain_id');
        //     $table->integer('invo_id')->unsigned();
        //     $table->foreign('invo_id')->references('invo_id')->on('tbl_invoices')->onDelete('cascade');
        //     $table->integer('tax_id')->unsigned();
        //     $table->foreign('tax_id')->references('tax_id')->on('tbl_typetax')->onDelete('cascade');
        //     $table->float('tain_amount_bt');
        //     $table->float('tain_total');
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('tbl_taxesinvoices');
    }
}
