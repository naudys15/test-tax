<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Districts extends Model
{
    /**
     * Modelo districts, donde se almacenan los distritos de un canton
     *
     * @return \Illuminate\Http\Response
     */
    protected $table = 'tbl_districts';
    protected $primaryKey = 'dist_id';
    public $timestamps = true;
    protected $fillable = [
        //'coun_id',
        'prov_id',
        'cant_id',
        'dist_description'
    ];

}