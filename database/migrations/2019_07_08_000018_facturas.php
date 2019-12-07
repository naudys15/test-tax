<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Facturas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('tbl_invoices', function (Blueprint $table) {
        //     $table->increments('invo_id');
        //     $table->string('invo_consecutive_code', 25);
        //     $table->string('invo_unique_code', 50);
        //     $table->string('invo_activity_code', 50);
        //     $table->integer('tydi_sender_id')->unsigned();
        //     $table->foreign('tydi_sender_id')->references('tydi_id')->on('tbl_typedocumentid')->onDelete('cascade');
        //     $table->string('invo_sender_document_number', 11);
        //     $table->string('invo_sender_name', 255);
        //     $table->integer('tydi_receiver_id')->unsigned();
        //     $table->foreign('tydi_receiver_id')->references('tydi_id')->on('tbl_typedocumentid')->onDelete('cascade');
        //     $table->string('invo_receiver_document_number', 11);
        //     $table->string('invo_receiver_name', 255);
        //     $table->integer('tyst_id')->unsigned();
        //     $table->foreign('tyst_id')->references('tyst_id')->on('tbl_typesaleterms')->onDelete('cascade');
        //     $table->integer('typm_id')->unsigned();
        //     $table->foreign('typm_id')->references('typm_id')->on('tbl_paymentmethod')->onDelete('cascade');
        //     $table->integer('clie_id')->unsigned();
        //     $table->foreign('clie_id')->references('clie_id')->on('tbl_clients')->onDelete('cascade');
        //     $table->integer('tydo_id')->unsigned();
        //     $table->foreign('tydo_id')->references('tydo_id')->on('tbl_typedocument')->onDelete('cascade');
        //     $table->date('invo_date');
        //     $table->date('invo_upload_date');
        //     $table->string('invo_change_type', 5);
        //     $table->double('invo_change_value');
        //     $table->string('invo_file_name', 255);
        //     $table->string('invo_file_url', 255);
        //     $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('tbl_invoices');
    }
}
