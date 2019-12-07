<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Provinces extends Model
{
    /**
     * Modelo provinces, donde se almacenan las provincias, estados o departamentos de un país
     *
     * @return \Illuminate\Http\Response
     */
    protected $table = 'tbl_provinces';
    protected $primaryKey = 'prov_id';
    public $timestamps = true;
    protected $fillable = [
        //'coun_id',
        'prov_description'
    ];

}