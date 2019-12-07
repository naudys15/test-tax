<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ConfiguracionesSistema extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_configurations', function (Blueprint $table) {
            $table->increments('conf_id');
            $table->integer('clie_id')->unsigned();
            $table->foreign('clie_id')->references('clie_id')->on('tbl_clients')->onDelete('cascade');
            $table->string('conf_iva_sale', 25);
            $table->double('conf_dolar_value');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_configurations');
    }
}
