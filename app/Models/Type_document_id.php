<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Type_document_id extends Model
{
    /**
     * Modelo type_document_id, donde se almacenan los distintos tipos de documentos de identidad que se pueden obtener de las facturas
     *
     * @return \Illuminate\Http\Response
     */
    protected $table = 'tbl_typedocumentid';
    protected $primaryKey = 'tydi_id';
    public $timestamps = false;
    protected $fillable = [
        'tydi_code',
        'tydi_description'
    ];

}