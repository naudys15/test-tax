<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ResumenDeMes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_summaryofmonth', function (Blueprint $table) {
            $table->increments('suom_id');
            $table->integer('clie_id')->unsigned();
            $table->foreign('clie_id')->references('clie_id')->on('tbl_clients')->onDelete('cascade');
            $table->string('suom_year');
            $table->string('suom_month');
            $table->double('suom_received_iva');
            $table->double('suom_paid_out_iva');
            $table->double('suom_expenses_iva');
            $table->double('suom_fiscal_credit');
            $table->double('suom_fiscal_debit');
            $table->double('suom_iva_to_pay');
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
        Schema::dropIfExists('tbl_summaryofmonth');
    }
}
