<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AgregarExentoFactura extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::table('tbl_invoices', function (Blueprint $table) {
        //     $table->integer('invo_exempt')->nullable();
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
        //     $table->dropColumn('invo_exempt');
        // });
    }
}
