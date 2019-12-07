<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AgregarProrrataGeneralYEspecialEnInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_clientproratainfo', function (Blueprint $table) {
            $table->string('clpi_type_prorata', 10)->nullable();
            $table->double('clpi_proportionality_general_prorata')->nullable();
            $table->double('clpi_proportionality_special_one_percent_prorata')->nullable();
            $table->double('clpi_proportionality_special_two_percent_prorata')->nullable();
            $table->double('clpi_proportionality_special_four_percent_prorata')->nullable();
            $table->double('clpi_proportionality_special_thirteen_percent_prorata')->nullable();
            $table->double('clpi_proportionality_special_exempt_with_credit_prorata')->nullable();
            $table->double('clpi_proportionality_special_exempt_without_credit_prorata')->nullable();
        }); 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_clientproratainfo', function (Blueprint $table) {
            $table->dropColumn('clpi_type_prorata');
            $table->dropColumn('clpi_proportionality_general_prorata');
            $table->dropColumn('clpi_proportionality_special_one_percent_prorata');
            $table->dropColumn('clpi_proportionality_special_two_percent_prorata');
            $table->dropColumn('clpi_proportionality_special_four_percent_prorata');
            $table->dropColumn('clpi_proportionality_special_thirteen_percent_prorata');
            $table->dropColumn('clpi_proportionality_special_exempt_with_credit_prorata');
            $table->dropColumn('clpi_proportionality_special_exempt_without_credit_prorata');
        });
    }
}
