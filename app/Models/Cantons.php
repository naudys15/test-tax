<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cantons extends Model
{
    /**
     * Modelo cantons, donde se almacenan los cantones de una provincia
     *
     * @return \Illuminate\Http\Response
     */
    protected $table = 'tbl_cantons';
    protected $primaryKey = 'cant_id';
    public $timestamps = true;
    protected $fillable = [
        //'coun_id',
        'prov_id',
        'cant_description'
    ];

}