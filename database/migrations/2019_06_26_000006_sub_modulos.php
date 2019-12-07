<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SubModulos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_submodules', function (Blueprint $table) {
            //
            $table->increments('subm_id');
            $table->string('subm_title', 50);
            $table->string('subm_description', 100);
            $table->integer('modu_id')->unsigned();
            $table->foreign('modu_id')->references('modu_id')->on('tbl_modules')->onDelete('cascade');
            $table->integer('subm_visible');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_submodules');
    }
}
