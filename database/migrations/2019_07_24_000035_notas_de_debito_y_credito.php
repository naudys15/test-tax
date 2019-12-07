<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NotasDeDebitoYCredito extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_creditanddebitnotes', function (Blueprint $table) {
            $table->increments('cadn_id');
            $table->string('cadn_consecutive_code', 25);
            $table->integer('clie_id')->unsigned();
            $table->foreign('clie_id')->references('clie_id')->on('tbl_clients')->onDelete('cascade');
            $table->integer('puin_id')->unsigned()->nullable();
            $table->foreign('puin_id')->references('puin_id')->on('tbl_purchaseinvoices')->onDelete('cascade');
            $table->integer('sain_id')->unsigned()->nullable();
            $table->foreign('sain_id')->references('sain_id')->on('tbl_saleinvoices')->onDelete('cascade');
            $table->date('cadn_date');
            $table->date('cadn_upload_date');
            $table->integer('tydo_id')->unsigned();
            $table->foreign('tydo_id')->references('tydo_id')->on('tbl_typedocument')->onDelete('cascade');
            $table->string('cadn_reason', 255)->nullable();
            $table->double('cadn_amount_bt');
            $table->double('cadn_tax_amount');
            $table->double('cadn_total');
            // $table->double('cadn_received_iva_total');
            // $table->double('cadn_paid_out_iva_total');
            // $table->double('cadn_expenses_total');
            $table->string('cadn_change_type', 5)->nullable();
            $table->double('cadn_change_value');
            $table->string('cadn_file_name', 255)->nullable();
            $table->string('cadn_file_url', 255)->nullable();
            $table->boolean('cadn_uploaded_manually');
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
        Schema::dropIfExists('tbl_creditanddebitnotes');
    }
}
