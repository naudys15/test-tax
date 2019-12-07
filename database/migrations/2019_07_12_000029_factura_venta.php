<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FacturaVenta extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_saleinvoices', function (Blueprint $table) {
            $table->increments('sain_id');
            $table->integer('clie_id')->unsigned();
            $table->foreign('clie_id')->references('clie_id')->on('tbl_clients')->onDelete('cascade');
            $table->string('sain_consecutive_code', 25);
            $table->string('sain_unique_code', 50)->nullable();
            $table->string('sain_activity_code', 50);
            $table->date('sain_date');
            $table->date('sain_upload_date');
            $table->integer('tydi_client_id')->unsigned();
            $table->foreign('tydi_client_id')->references('tydi_id')->on('tbl_typedocumentid')->onDelete('cascade');
            $table->string('sain_client_document_number', 11);
            $table->string('sain_client_name', 255);
            $table->integer('tyst_id')->unsigned()->nullable();
            $table->foreign('tyst_id')->references('tyst_id')->on('tbl_typesaleterms')->onDelete('cascade');
            $table->integer('typm_id')->unsigned()->nullable();
            $table->foreign('typm_id')->references('typm_id')->on('tbl_paymentmethod')->onDelete('cascade');
            $table->integer('tydo_id')->unsigned();
            $table->foreign('tydo_id')->references('tydo_id')->on('tbl_typedocument')->onDelete('cascade');
            $table->double('sain_amount_bt');
            $table->double('sain_tax_amount');
            $table->double('sain_total');
            $table->double('sain_amount_one_percent_total');
            $table->double('sain_amount_two_percent_total');
            $table->double('sain_amount_four_percent_total');
            $table->double('sain_amount_eight_percent_total');
            $table->double('sain_amount_thirteen_percent_total');
            $table->double('sain_amount_exempt_with_fiscal_credit_total');
            $table->double('sain_amount_exempt_without_fiscal_credit_total');
            $table->double('sain_amount_total');
            $table->string('sain_change_type', 5)->nullable();
            $table->double('sain_change_value');
            $table->string('sain_file_name', 255)->nullable();
            $table->string('sain_file_url', 255)->nullable();
            $table->boolean('sain_uploaded_manually');
            $table->integer('sain_exempt');
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
        Schema::dropIfExists('tbl_saleinvoices');
    }
}
