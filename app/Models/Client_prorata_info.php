<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client_prorata_info extends Model
{
    /**
     * Modelo client_prorata_info, donde se almacenan la información de proporcionalidad de los clientes
     *
     * @return \Illuminate\Http\Response
     */
    protected $table = 'tbl_clientproratainfo';
    protected $primaryKey = 'clpi_id';
    public $timestamps = true;
    protected $fillable = [
        'clie_id',
        'clpi_type_prorata',
        'clpi_proportionality_general_prorata',
        'clpi_proportionality_special_one_percent_prorata',
        'clpi_proportionality_special_two_percent_prorata',
        'clpi_proportionality_special_four_percent_prorata',
        'clpi_proportionality_special_thirteen_percent_prorata',
        'clpi_proportionality_special_exempt_with_credit_prorata',
        'clpi_proportionality_special_exempt_without_credit_prorata',
        'clpi_total_prorata',
        'clpi_proportionality_special_reduced_credit_percent',
        'clpi_proportionality_special_full_credit_percent',
        'clpi_year',
        'clpi_description'
    ];

}