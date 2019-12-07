<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Modules extends Model
{
    /**
     * Modelo modules, donde se almacenan los módulos accesibles en el sistema
     *
     * @return \Illuminate\Http\Response
     */
    protected $table = 'tbl_modules';
    protected $primaryKey = 'modu_id';
    public $timestamps = false;
    protected $fillable = [
        'modu_title',
        'modu_description'
    ];

}