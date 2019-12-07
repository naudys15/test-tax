<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Summary_of_month extends Model
{
    /**
     * Modelo summary_of_month, donde se guarda la información tributaria de mes del cliente
     *
     * @return \Illuminate\Http\Response
     */
    protected $table = 'tbl_summaryofmonth';
    protected $primaryKey = 'suom_id';
    public $timestamps = true;
    protected $fillable = [
        'clie_id',
        'suom_year',
        'suom_month',
        'suom_received_iva',
        'suom_paid_out_iva',
        'suom_expenses_iva',
        'suom_fiscal_credit',
        'suom_fiscal_debit',
        'suom_paid_out_one_visible',
        'suom_paid_out_two_visible',
        'suom_paid_out_four_visible',
        'suom_paid_out_eight_visible',
        'suom_paid_out_thirteen_visible',
        'suom_paid_out_visible_total',
        'suom_paid_out_one_prorata',
        'suom_paid_out_two_prorata',
        'suom_paid_out_four_prorata',
        'suom_paid_out_eight_prorata',
        'suom_paid_out_thirteen_prorata',
        'suom_paid_out_prorata_total',
        'suom_type_prorata',
        'suom_iva_to_pay',
        'suom_exempt_with_credit',
        'suom_exempt_without_credit'
    ];
    
}