<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Submodules extends Model
{
    /**
     * Modelo sub-módulos, donde se almacenan los sub-módulos accesibles por el cliente en el sistema
     *
     * @return \Illuminate\Http\Response
     */
    protected $table = 'tbl_submodules';
    protected $primaryKey = 'subm_id';
    public $timestamps = false;
    protected $fillable = [
        'modu_id',
        'subm_description'
    ];

}