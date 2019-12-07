<?php

use Illuminate\Database\Seeder;
use App\Models\Type_document_id;

class TypeDocumentIdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ty_di = new Type_document_id();
        $ty_di->tydi_id = 1;
        $ty_di->tydi_code = '01';
        $ty_di->tydi_description = 'Cédula Física';
        $ty_di->save();

        $ty_di = new Type_document_id();
        $ty_di->tydi_id = 2;
        $ty_di->tydi_code = '02';
        $ty_di->tydi_description = 'Cédula Jurídica';
        $ty_di->save();
        
        $ty_di = new Type_document_id();
        $ty_di->tydi_id = 3;
        $ty_di->tydi_code = '03';
        $ty_di->tydi_description = 'DIMEX';
        $ty_di->save();

        $ty_di = new Type_document_id();
        $ty_di->tydi_id = 4;
        $ty_di->tydi_code = '04';
        $ty_di->tydi_description = 'NITE';
        $ty_di->save();
    }
}
