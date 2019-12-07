<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Type_tax extends Model
{
    /**
     * Modelo type_tax, donde se almacenan los distintos tipos de impuestos que se pueden calcular en al sistema
     *
     * @return \Illuminate\Http\Response
     */
    protected $table = 'tbl_typetax';
    protected $primaryKey = 'tax_id';
    public $timestamps = false;
    protected $fillable = [
        'tax_code',
        'tax_description'
    ];

}