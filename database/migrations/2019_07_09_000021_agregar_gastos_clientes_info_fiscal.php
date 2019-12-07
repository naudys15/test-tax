<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AgregarGastosClientesInfoFiscal extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*Schema::table('tbl_clientinfotax', function (Blueprint $table) {
            $table->double('clti_expenses_amount')->nullable();
        }); */
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        /*Schema::table('tbl_clientinfotax', function (Blueprint $table) {
            $table->dropColumn('clti_expenses_amount');
        });*/
    }
}
