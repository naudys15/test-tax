<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Type_payment_method extends Model
{
    /**
     * Modelo type_payment_method, donde se almacenan los distintos tipos de métodos de pago que se pueden obtener de las facturas
     *
     * @return \Illuminate\Http\Response
     */
    protected $table = 'tbl_paymentmethod';
    protected $primaryKey = 'typm_id';
    public $timestamps = false;
    protected $fillable = [
        'typm_code',
        'typm_description'
    ];

}