<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoices extends Model
{
    /**
     * Modelo invoices, donde se almacenan las facturas de los clientes
     *
     * @return \Illuminate\Http\Response
     */
    protected $table = 'tbl_invoices';
    protected $primaryKey = 'invo_id';
    public $timestamps = true;
    protected $fillable = [
        'invo_consecutive_code',
        'invo_unique_code',
        'invo_activity_code',
        'tydi_sender_id',
        'invo_sender_document_number',
        'invo_sender_name',
        'tydi_receiver_id',
        'invo_receiver_document_number',
        'invo_receiver_name',
        'tyst_id',
        'typm_id',
        'clie_id', 
        'tydo_id', 
        'invo_date', 
        'invo_upload_date', 
        'invo_change_type', 
        'invo_change_value', 
        'invo_file_name',
        'invo_file_url',
        'invo_exempt',
        'invo_amount_bt',
        'invo_tax_amount',
        'invo_total',
        'invo_received_tax_amount',
        'invo_paid_out_tax_amount_total',
        'invo_paid_out_tax_amount_two_percent_total',
        'invo_paid_out_tax_amount_four_percent_total',
        'invo_paid_out_tax_amount_eight_percent_total',
        'invo_paid_out_tax_amount_exempt_with_fiscal_credit_total',
        'invo_expenses_amount',

    ];

    //Relación Cliente
    public function client()
    {
        return $this->hasOne('App\Models\Clients', 'clie_id', 'clie_id');
    }
}