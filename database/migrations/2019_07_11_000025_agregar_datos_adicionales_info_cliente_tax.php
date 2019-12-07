<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AgregarDatosAdicionalesInfoClienteTax extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_clientproratainfo', function (Blueprint $table) {
            $table->double('clpi_total_prorata')->nullable();
            $table->string('clpi_year', 4)->nullable();
            $table->string('clpi_description', 255)->nullable();
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
            $table->dropColumn('clpi_total_prorata');
            $table->dropColumn('clpi_year');
            $table->dropColumn('clpi_description');
        });
    }
}
