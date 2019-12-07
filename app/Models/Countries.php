<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Countries extends Model
{
    /**
     * Modelo countries, donde se almacenan los paises
     *
     * @return \Illuminate\Http\Response
     */
    protected $table = 'tbl_countries';
    protected $primaryKey = 'coun_id';
    public $timestamps = true;
    protected $fillable = [
        'coun_description'
    ];

}