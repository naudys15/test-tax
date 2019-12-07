<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Products_invoices extends Model
{
    /**
     * Modelo products_invoices, donde se almacenan los distintos productos y sus porcentajes de impuesto en una factura
     *
     * @return \Illuminate\Http\Response
     */
    protected $table = 'tbl_productsinvoices';
    protected $primaryKey = 'prin_id';
    public $timestamps = false;
    protected $fillable = [
        'sain_id',
        'puin_id',
        'tylc_id',
        'tymu_id',
        'tiva_id',
        'prin_name',
        'prin_exoneration',
        'prin_discount',
        'prin_quantity',
        'prin_amount_bt',
        'prin_amount_tax',
        'prin_total',
        'prin_credit_fiscal',
        'prin_iva_sale'
    ];

}