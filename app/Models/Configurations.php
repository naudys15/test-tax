<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Configurations extends Model
{
    /**
     * Modelo configurations, donde se almacena la configuración del cliente
     *
     * @return \Illuminate\Http\Response
     */
    protected $table = 'tbl_configurations';
    protected $primaryKey = 'conf_id';
    public $timestamps = true;
    protected $fillable = [
        'clie_id',
        'conf_iva_sale',
        'conf_dolar_value'
    ];

}