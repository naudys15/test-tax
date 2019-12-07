<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Taxes_invoices extends Model
{
    /**
     * Modelo taxes_invoices, donde se almacenan los distintos porcentajes de impuesto presentes en una factura
     *
     * @return \Illuminate\Http\Response
     */
    protected $table = 'tbl_taxesinvoices';
    protected $primaryKey = 'tain_id';
    public $timestamps = false;
    protected $fillable = [
        'invo_id',
        'tax_id',
        'tain_amount_bt',
        'tain_total'
    ];

}