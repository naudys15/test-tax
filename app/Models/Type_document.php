<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Type_document extends Model
{
    /**
     * Modelo type_document, donde se almacenan los distintos tipos de documentos que se pueden subir al sistema
     *
     * @return \Illuminate\Http\Response
     */
    protected $table = 'tbl_typedocument';
    protected $primaryKey = 'tydo_id';
    public $timestamps = false;
    protected $fillable = [
        'tydo_title',
        'tydo_description'
    ];

}