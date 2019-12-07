<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class End_of_month extends Model
{
    /**
     * Modelo end_of_month, donde se guarda la información de cierre de mes del cliente
     *
     * @return \Illuminate\Http\Response
     */
    protected $table = 'tbl_endofmonth';
    protected $primaryKey = 'conf_id';
    public $timestamps = true;
    protected $fillable = [
        'clie_id',
        'enom_start_date',
        'enom_end_date',
        // 'enom_year',
        // 'enom_month',
        'enom_name',
        'enom_description',
        'enom_type_period'
    ];

}