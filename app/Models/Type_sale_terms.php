<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Type_sale_terms extends Model
{
    /**
     * Modelo type_sale_terms, donde se almacenan los distintos tipos de condiciones de venta que se pueden obtener de las facturas
     *
     * @return \Illuminate\Http\Response
     */
    protected $table = 'tbl_typesaleterms';
    protected $primaryKey = 'tyst_id';
    public $timestamps = false;
    protected $fillable = [
        'tyst_code',
        'tyst_description'
    ];

}