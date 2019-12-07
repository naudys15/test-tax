<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AgregarDatosAdicionalesCliente extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_clients', function (Blueprint $table) {
            $table->string('clie_phonenumber', 20)->nullable();
            $table->integer('clie_dni')->unsigned()->nullable();
            $table->integer('tydi_id')->unsigned()->nullable();
            $table->foreign('tydi_id')->references('tydi_id')->on('tbl_typedocumentid')->onDelete('cascade');
            $table->string('clie_business_name', 50)->nullable();
            $table->integer('clie_legal_dni')->nullable();
            $table->integer('coun_id')->unsigned()->nullable();
            $table->foreign('coun_id')->references('coun_id')->on('tbl_countries')->onDelete('cascade');
            $table->integer('prov_id')->unsigned()->nullable();
            $table->foreign('prov_id')->references('prov_id')->on('tbl_provinces')->onDelete('cascade');
            $table->integer('cant_id')->unsigned()->nullable();
            $table->foreign('cant_id')->references('cant_id')->on('tbl_cantons')->onDelete('cascade');
            $table->integer('dist_id')->unsigned()->nullable();
            $table->foreign('dist_id')->references('dist_id')->on('tbl_districts')->onDelete('cascade');
            $table->string('clie_address', 255)->nullable();
        }); 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_clients', function (Blueprint $table) {
            $table->dropColumn('clie_phonenumber');
            $table->dropColumn('clie_dni');
            $table->dropColumn('tydi_id');
            $table->dropColumn('clie_business_name');
            $table->dropColumn('clie_legal_dni');
            $table->dropColumn('coun_id');
            $table->dropColumn('prov_id');
            $table->dropColumn('cant_id');
            $table->dropColumn('dist_id');
            $table->dropColumn('clie_address');
        }); 
    }
}
