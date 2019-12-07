<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Clients extends Model
{
    /**
     * Modelo clients, donde se almacenan los clientes del sistema
     *
     * @return \Illuminate\Http\Response
     */
    protected $table = 'tbl_clients';
    protected $primaryKey = 'clie_id';
    public $timestamps = true;
    protected $fillable = [
        'clie_firstname', 
        'clie_lastname',
        'clie_dni',
        'tydi_id',
        'clie_phonenumber',
        'clie_username',
        'clie_email',
        'clie_password',
        'tycl_id',
        'clie_business_name',
        'clie_legal_dni',
        'coun_id',
        'prov_id',
        'cant_id',
        'dist_id',
        'clie_address'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'clie_password',
    ];

}