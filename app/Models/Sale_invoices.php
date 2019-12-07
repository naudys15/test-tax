<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale_invoices extends Model
{
    /**
     * Modelo sale_invoices, donde se almacenan las facturas de ventas de los clientes
     *
     * @return \Illuminate\Http\Response
     */
    protected $table = 'tbl_saleinvoices';
    protected $primaryKey = 'sain_id';
    public $timestamps = true;
    protected $fillable = [
        'clie_id',
        'sain_consecutive_code',
        'sain_unique_code',
        'sain_activity_code',
        'sain_date', 
        'sain_upload_date',
        'tydi_client_id',
        'sain_client_document_number',
        'sain_client_name',
        'tyst_id',
        'typm_id',
        'tydo_id',
        'sain_amount_bt',
        'sain_tax_amount',
        'sain_total',
        'sain_amount_one_percent_total',
        'sain_amount_two_percent_total',
        'sain_amount_four_percent_total',
        'sain_amount_eight_percent_total',
        'sain_amount_thirteen_percent_total',
        'sain_amount_exempt_with_fiscal_credit_total',
        'sain_amount_exempt_without_fiscal_credit_total',
        'sain_amount_total',
        'sain_change_type', 
        'sain_change_value', 
        'sain_file_name',
        'sain_file_url',
        'sain_uploaded_manually',
        'sain_exempt'
    ];

    //Relación Cliente
    public function client()
    {
        return $this->hasOne('App\Models\Clients', 'clie_id', 'clie_id');
    }
}