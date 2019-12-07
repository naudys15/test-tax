<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Provincias extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_provinces', function (Blueprint $table) {
            $table->increments('prov_id');
            $table->integer('coun_id')->unsigned();
            $table->foreign('coun_id')->references('coun_id')->on('tbl_countries')->onDelete('cascade');
            $table->string('prov_description', 100);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_provinces');
    }
}
