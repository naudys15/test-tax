<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AgregarCreditoReducidoYPlenoInfoClienteProrrata extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_clientproratainfo', function (Blueprint $table) {
            $table->double('clpi_proportionality_special_full_credit_percent')->nullable();
            $table->double('clpi_proportionality_special_reduced_credit_percent')->nullable();
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
            $table->dropColumn('clpi_proportionality_special_full_credit_percent');
            $table->dropColumn('clpi_proportionality_special_reduced_credit_percent');
        });
    }
}
