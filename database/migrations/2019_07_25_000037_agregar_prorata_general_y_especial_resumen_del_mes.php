<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AgregarProrataGeneralYEspecialResumenDelMes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_summaryofmonth', function (Blueprint $table) {
            $table->double('suom_paid_out_one_visible');
            $table->double('suom_paid_out_two_visible');
            $table->double('suom_paid_out_four_visible');
            $table->double('suom_paid_out_eight_visible');
            $table->double('suom_paid_out_thirteen_visible');
            $table->double('suom_paid_out_visible_total');
            $table->double('suom_paid_out_one_prorata');
            $table->double('suom_paid_out_two_prorata');
            $table->double('suom_paid_out_four_prorata');
            $table->double('suom_paid_out_eight_prorata');
            $table->double('suom_paid_out_thirteen_prorata');
            $table->double('suom_paid_out_prorata_total');
            $table->string('suom_type_prorata', 25);
            $table->double('suom_exempt_with_credit');
            $table->double('suom_exempt_without_credit');
        }); 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_summaryofmonth', function (Blueprint $table) {
            $table->dropColumn('suom_paid_out_one_visible');
            $table->dropColumn('suom_paid_out_two_visible');
            $table->dropColumn('suom_paid_out_four_visible');
            $table->dropColumn('suom_paid_out_eight_visible');
            $table->dropColumn('suom_paid_out_thirteen_visible');
            $table->dropColumn('suom_paid_out_thirteen_visible_total');
            $table->dropColumn('suom_paid_out_one_prorata');
            $table->dropColumn('suom_paid_out_two_prorata');
            $table->dropColumn('suom_paid_out_four_prorata');
            $table->dropColumn('suom_paid_out_eight_prorata');
            $table->dropColumn('suom_paid_out_thirteen_prorata');
            $table->dropColumn('suom_paid_out_thirteen_prorata_total');
            $table->dropColumn('suom_type_prorata');
            $table->dropColumn('suom_exempt_with_credit');
            $table->dropColumn('suom_exempt_without_credit');
        }); 
    }
}
