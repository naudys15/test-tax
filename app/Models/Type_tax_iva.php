<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Type_tax_iva extends Model
{
    /**
     * Modelo type_tax_iva, donde se almacenan los distintos tipos de impuestos de iva que se pueden calcular en al sistema
     *
     * @return \Illuminate\Http\Response
     */
    protected $table = 'tbl_typeivatax';
    protected $primaryKey = 'tiva_id';
    public $timestamps = false;
    protected $fillable = [
        'tiva_code',
        'tiva_percentage',
        'tiva_description'
    ];

}