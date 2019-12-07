<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Modules_client extends Model
{
    /**
     * Modelo modules_client, donde se almacenan los módulos accesibles por el cliente en el sistema
     *
     * @return \Illuminate\Http\Response
     */
    protected $table = 'tbl_modulesclient';
    protected $primaryKey = 'mocl_id';
    public $timestamps = true;
    protected $fillable = [
        'clie_id',
        'subm_id',
        'mocl_status'
    ];

}