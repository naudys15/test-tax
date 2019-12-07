<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AgregarTipoCierreDeMes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_endofmonth', function (Blueprint $table) {
            $table->string('enom_type_period', 25);
        }); 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_endofmonth', function (Blueprint $table) {
            $table->dropColumn('enom_type_period');
        });
    }
}
