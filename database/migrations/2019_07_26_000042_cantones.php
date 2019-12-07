<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Cantones extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_cantons', function (Blueprint $table) {
            $table->increments('cant_id');
            $table->integer('coun_id')->unsigned();
            $table->foreign('coun_id')->references('coun_id')->on('tbl_countries')->onDelete('cascade');
            $table->integer('prov_id')->unsigned();
            $table->foreign('prov_id')->references('prov_id')->on('tbl_provinces')->onDelete('cascade');
            $table->string('cant_description', 100);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_cantons');
    }
}
