<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Type_code_line_invoice extends Model
{
    /**
     * Modelo Type_code_line_invoice, donde se almacenan los distintos tipos de códigos presentes en las lineas de detalle de cada factura
     *
     * @return \Illuminate\Http\Response
     */
    protected $table = 'tbl_typelinecode';
    protected $primaryKey = 'tylc_id';
    public $timestamps = false;
    protected $fillable = [
        'tylc_code',
        'tylc_description'
    ];

}