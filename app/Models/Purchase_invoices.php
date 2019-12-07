<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase_invoices extends Model
{
    /**
     * Modelo purchase_invoices, donde se almacenan las facturas de compras de los clientes
     *
     * @return \Illuminate\Http\Response
     */
    protected $table = 'tbl_purchaseinvoices';
    protected $primaryKey = 'puin_id';
    public $timestamps = true;
    protected $fillable = [
        'clie_id',
        'puin_consecutive_code',
        'puin_unique_code',
        'puin_activity_code',
        'puin_date', 
        'puin_upload_date',
        'tydi_provider_id',
        'puin_provider_document_number',
        'puin_provider_name',
        'tyst_id',
        'typm_id',
        'tydo_id',
        'enom_id',
        'puin_amount_bt',
        'puin_tax_amount',
        'puin_total',
        'puin_received_iva_total',
        'puin_paid_out_iva_one_percent',
        'puin_paid_out_iva_two_percent',
        'puin_paid_out_iva_four_percent',
        'puin_paid_out_iva_eight_percent',
        'puin_paid_out_iva_thirteen_percent',
        'puin_paid_out_iva_one_percent_prorata',
        'puin_paid_out_iva_two_percent_prorata',
        'puin_paid_out_iva_four_percent_prorata',
        'puin_paid_out_iva_eight_percent_prorata',
        'puin_paid_out_iva_thirteen_percent_prorata',
        'puin_paid_out_iva_exempt_with_credit',
        'puin_paid_out_iva_exempt_without_credit',
        'puin_paid_out_iva_total_prorata',
        'puin_paid_out_iva_total',
        'puin_expenses_total',
        'puin_change_type', 
        'puin_change_value', 
        'puin_file_name',
        'puin_file_url',
        'puin_uploaded_manually',
        'puin_exempt'
    ];

    //Relación Cliente
    public function client()
    {
        return $this->hasOne('App\Models\Clients', 'clie_id', 'clie_id');
    }
}