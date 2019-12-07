<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Type_client extends Model
{
    /**
     * Modelo type_client, donde se almacenan los distintos tipos de clientes en el sistema
     *
     * @return \Illuminate\Http\Response
     */
    protected $table = 'tbl_typeclient';
    protected $primaryKey = 'tycl_id';
    public $timestamps = false;
    protected $fillable = [
        'tycl_title',
        'tycl_description'
    ];

}