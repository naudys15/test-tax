<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reports extends Model
{
    /**
     * Modelo reports, donde se almacenan los reportes emitidos por el cliente en el sistema
     *
     * @return \Illuminate\Http\Response
     */
    protected $table = 'tbl_reports';
    protected $primaryKey = 'repo_id';
    public $timestamps = true;
    protected $fillable = [
        'tyre_id',
        'clie_id',
        'repo_date',
        'repo_file_name',
        'repo_file_pdf',
        'repo_file_excel',
        'repo_file_xml'
    ];

}