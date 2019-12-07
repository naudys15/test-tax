<?php

use Illuminate\Database\Seeder;
use App\Models\Type_document;

class TypeDocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ty_do = new Type_document();
        $ty_do->tydo_id = 1;
        $ty_do->tydo_title = 'Factura electrónica';
        $ty_do->tydo_description = 'Factura electrónica';
        $ty_do->save();

        $ty_do = new Type_document();
        $ty_do->tydo_id = 2;
        $ty_do->tydo_title = 'Factura de compra';
        $ty_do->tydo_description = 'Factura de compra';
        $ty_do->save();

        $ty_do = new Type_document();
        $ty_do->tydo_id = 3;
        $ty_do->tydo_title = 'Factura de venta';
        $ty_do->tydo_description = 'Factura de venta';
        $ty_do->save();

        $ty_do = new Type_document();
        $ty_do->tydo_id = 4;
        $ty_do->tydo_title = 'Factura de gasto';
        $ty_do->tydo_description = 'Factura de gasto';
        $ty_do->save();


        $ty_do = new Type_document();
        $ty_do->tydo_id = 5;
        $ty_do->tydo_title = 'Nota de débito';
        $ty_do->tydo_description = 'Nota de débito';
        $ty_do->save();

        $ty_do = new Type_document();
        $ty_do->tydo_id = 6;
        $ty_do->tydo_title = 'Nota de crédito';
        $ty_do->tydo_description = 'Nota de crédito';
        $ty_do->save();
    }
}

