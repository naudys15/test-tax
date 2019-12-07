<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FacturaCompra extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_purchaseinvoices', function (Blueprint $table) {
            $table->increments('puin_id');
            $table->integer('clie_id')->unsigned();
            $table->foreign('clie_id')->references('clie_id')->on('tbl_clients')->onDelete('cascade');
            $table->string('puin_consecutive_code', 25);
            $table->string('puin_unique_code', 50)->nullable();
            $table->string('puin_activity_code', 50);
            $table->date('puin_date');
            $table->date('puin_upload_date');
            $table->integer('tydi_provider_id')->unsigned();
            $table->foreign('tydi_provider_id')->references('tydi_id')->on('tbl_typedocumentid')->onDelete('cascade');
            $table->string('puin_provider_document_number', 11);
            $table->string('puin_provider_name', 255);
            $table->double('puin_amount_bt');
            $table->double('puin_tax_amount');
            $table->double('puin_total');
            $table->double('puin_received_iva_total');
            $table->double('puin_paid_out_iva_one_percent');
            $table->double('puin_paid_out_iva_two_percent');
            $table->double('puin_paid_out_iva_four_percent');
            $table->double('puin_paid_out_iva_eight_percent');
            $table->double('puin_paid_out_iva_thirteen_percent');
            $table->double('puin_paid_out_iva_one_percent_prorata');
            $table->double('puin_paid_out_iva_two_percent_prorata');
            $table->double('puin_paid_out_iva_four_percent_prorata');
            $table->double('puin_paid_out_iva_eight_percent_prorata');
            $table->double('puin_paid_out_iva_thirteen_percent_prorata');
            $table->double('puin_paid_out_iva_exempt_with_credit');
            $table->double('puin_paid_out_iva_exempt_without_credit');
            $table->double('puin_paid_out_iva_total_prorata');
            $table->double('puin_paid_out_iva_total');
            $table->double('puin_expenses_total');
            $table->integer('tyst_id')->unsigned()->nullable();
            $table->foreign('tyst_id')->references('tyst_id')->on('tbl_typesaleterms')->onDelete('cascade');
            $table->integer('typm_id')->unsigned()->nullable();
            $table->foreign('typm_id')->references('typm_id')->on('tbl_paymentmethod')->onDelete('cascade');
            $table->integer('tydo_id')->unsigned();
            $table->foreign('tydo_id')->references('tydo_id')->on('tbl_typedocument')->onDelete('cascade');
            $table->integer('enom_id')->unsigned();
            $table->foreign('enom_id')->references('enom_id')->on('tbl_endofmonth')->onDelete('cascade');
            $table->string('puin_change_type', 5)->nullable();
            $table->double('puin_change_value');
            $table->string('puin_file_name', 255)->nullable();
            $table->string('puin_file_url', 255)->nullable();
            $table->boolean('puin_uploaded_manually');
            $table->integer('puin_exempt');
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
        Schema::dropIfExists('tbl_purchaseinvoices');
    }
}
