<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Reportes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_reports', function (Blueprint $table) {
            $table->increments('repo_id');
            $table->integer('tyre_id')->unsigned();
            $table->foreign('tyre_id')->references('tyre_id')->on('tbl_typereport')->onDelete('cascade');
            $table->integer('clie_id')->unsigned();
            $table->foreign('clie_id')->references('clie_id')->on('tbl_clients')->onDelete('cascade');
            $table->date('repo_date');
            $table->string('repo_file_name', 100);
            $table->string('repo_file_pdf', 255);
            $table->string('repo_file_excel', 255)->nullable();
            $table->string('repo_file_xml', 255)->nullable();
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
        Schema::dropIfExists('tbl_reports');
    }
}
