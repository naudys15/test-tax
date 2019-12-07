<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Distritos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_districts', function (Blueprint $table) {
            $table->increments('dist_id');
            $table->integer('coun_id')->unsigned();
            $table->foreign('coun_id')->references('coun_id')->on('tbl_countries')->onDelete('cascade');
            $table->integer('prov_id')->unsigned();
            $table->foreign('prov_id')->references('prov_id')->on('tbl_provinces')->onDelete('cascade');
            $table->integer('cant_id')->unsigned();
            $table->foreign('cant_id')->references('cant_id')->on('tbl_cantons')->onDelete('cascade');
            $table->string('dist_description', 100);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_districts');
    }
}
