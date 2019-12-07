<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Credit_and_debit_notes extends Model
{
    /**
     * Modelo credit_and_debit_notes, donde se almacenan las notas de crédito y débito de las facturas de los clientes
     *
     * @return \Illuminate\Http\Response
     */
    protected $table = 'tbl_creditanddebitnotes';
    protected $primaryKey = 'cadn_id';
    public $timestamps = true;
    protected $fillable = [
        'clie_id',
        'sain_id',
        'puin_id',
        'cadn_consecutive_code',
        'cadn_date', 
        'cadn_upload_date',
        'tydo_id',
        'cadn_reason',
        'cadn_amount_bt',
        'cadn_tax_amount',
        'cadn_total',
        // 'cadn_received_iva_total',
        // 'cadn_paid_out_iva_total',
        // 'cadn_expenses_total',
        'cadn_change_type', 
        'cadn_change_value', 
        'cadn_file_name',
        'cadn_file_url',
        'cadn_uploaded_manually'
    ];

}