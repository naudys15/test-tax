<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TipoImpuestoIva extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_typeivatax', function (Blueprint $table) {
            //
            $table->increments('tiva_id');
            $table->string('tiva_code', 2);
            $table->string('tiva_percentage', 4);
            $table->string('tiva_description', 100);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_typeivatax');
    }
}
