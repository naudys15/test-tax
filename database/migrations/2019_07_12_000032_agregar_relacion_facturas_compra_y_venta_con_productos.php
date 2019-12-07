<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AgregarRelacionFacturasCompraYVentaConProductos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_productsinvoices', function (Blueprint $table) {
            $table->integer('puin_id')->unsigned()->nullable();
            $table->foreign('puin_id')->references('puin_id')->on('tbl_purchaseinvoices')->onDelete('cascade');
            $table->integer('sain_id')->unsigned()->nullable();
            $table->foreign('sain_id')->references('sain_id')->on('tbl_saleinvoices')->onDelete('cascade');
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
            $table->dropColumn('puin_id');
            $table->dropColumn('sain_id');
        });
    }
}
