<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AgregarTotalesYProrrataPorFactura extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::table('tbl_invoices', function (Blueprint $table) {
        //     $table->double('invo_amount_bt')->nullable();
        //     $table->double('invo_tax_amount')->nullable();
        //     $table->double('invo_total')->nullable();
        //     $table->double('invo_received_tax_amount')->nullable();
        //     $table->double('invo_paid_out_tax_amount_total')->nullable();
        //     $table->double('invo_paid_out_tax_amount_two_percent_total')->nullable();
        //     $table->double('invo_paid_out_tax_amount_four_percent_total')->nullable();
        //     $table->double('invo_paid_out_tax_amount_eight_percent_total')->nullable();
        //     $table->double('invo_paid_out_tax_amount_exempt_with_fiscal_credit_total')->nullable();
        //     $table->double('invo_expenses_amount')->nullable();
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::table('tbl_invoices', function (Blueprint $table) {
        //     $table->dropColumn('invo_amount_bt');
        //     $table->dropColumn('invo_tax_amount');
        //     $table->dropColumn('invo_total');
        //     $table->dropColumn('invo_received_tax_amount');
        //     $table->dropColumn('invo_paid_out_tax_amount_total');
        //     $table->dropColumn('invo_paid_out_tax_amount_two_percent_total');
        //     $table->dropColumn('invo_paid_out_tax_amount_four_percent_total');
        //     $table->dropColumn('invo_paid_out_tax_amount_eight_percent_total');
        //     $table->dropColumn('invo_paid_out_tax_amount_exempt_with_fiscal_credit_total');
        //     $table->dropColumn('invo_expenses_amount');
        // });
    }
}
