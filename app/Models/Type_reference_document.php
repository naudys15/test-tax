<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Type_reference_document extends Model
{
    /**
     * Modelo type_reference_document, donde se almacenan los distintos tipos de documentos de referencia que admiten las notas de crédito y débito
     *
     * @return \Illuminate\Http\Response
     */
    protected $table = 'tbl_typerefdoc';
    protected $primaryKey = 'tyrd_id';
    public $timestamps = false;
    protected $fillable = [
        'tyrd_code',
        'tyrd_description'
    ];

}