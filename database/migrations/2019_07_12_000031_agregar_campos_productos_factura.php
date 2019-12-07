<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AgregarCamposProductosFactura extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_productsinvoices', function (Blueprint $table) {
            $table->double('prin_amount_tax');
            $table->double('prin_total');
            $table->double('prin_credit_fiscal');
            $table->string('prin_iva_sale', 25);
        }); 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_productsinvoices', function (Blueprint $table) {
            $table->dropColumn('prin_amount_tax');
            $table->dropColumn('prin_total');
            $table->dropColumn('prin_credit_fiscal');
            $table->dropColumn('prin_iva_sale');
        });
    }
}
