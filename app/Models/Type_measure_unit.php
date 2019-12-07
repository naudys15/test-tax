<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Type_measure_unit extends Model
{
    /**
     * Modelo type_measure_unit, donde se almacenan los distintos tipos de unidades de medida que se pueden obtener de las facturas
     *
     * @return \Illuminate\Http\Response
     */
    protected $table = 'tbl_typemeasureunit';
    protected $primaryKey = 'tymu_id';
    public $timestamps = false;
    protected $fillable = [
        'tymu_title',
        'tymu_description'
    ];

}