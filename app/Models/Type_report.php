<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Type_report extends Model
{
    /**
     * Modelo type_report, donde se almacenan los distintos tipos de reportes que se pueden generar en el sistema
     *
     * @return \Illuminate\Http\Response
     */
    protected $table = 'tbl_typereport';
    protected $primaryKey = 'tyre_id';
    public $timestamps = false;
    protected $fillable = [
        'tyre_title',
        'tyre_description'
    ];

}