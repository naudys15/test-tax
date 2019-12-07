<?php

use Illuminate\Database\Seeder;
use App\Models\Type_reference_document;

class TypeDocumentReferenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $type_document_reference = new Type_reference_document();
        $type_document_reference->tyrd_id = 1;
        $type_document_reference->tyrd_code = '01';
        $type_document_reference->tyrd_description = 'Factura electrónica';
        $type_document_reference->save();

        $type_document_reference = new Type_reference_document();
        $type_document_reference->tyrd_id = 2;
        $type_document_reference->tyrd_code = '02';
        $type_document_reference->tyrd_description = 'Nota de débito electrónica';
        $type_document_reference->save();

        $type_document_reference = new Type_reference_document();
        $type_document_reference->tyrd_id = 3;
        $type_document_reference->tyrd_code = '03';
        $type_document_reference->tyrd_description = 'Nota de crédito electrónica';
        $type_document_reference->save();

        $type_document_reference = new Type_reference_document();
        $type_document_reference->tyrd_id = 4;
        $type_document_reference->tyrd_code = '04';
        $type_document_reference->tyrd_description = 'Tiquete electrónico';
        $type_document_reference->save();

        $type_document_reference = new Type_reference_document();
        $type_document_reference->tyrd_id = 5;
        $type_document_reference->tyrd_code = '05';
        $type_document_reference->tyrd_description = 'Nota de despacho';
        $type_document_reference->save();

        $type_document_reference = new Type_reference_document();
        $type_document_reference->tyrd_id = 6;
        $type_document_reference->tyrd_code = '06';
        $type_document_reference->tyrd_description = 'Contrato';
        $type_document_reference->save();

        $type_document_reference = new Type_reference_document();
        $type_document_reference->tyrd_id = 7;
        $type_document_reference->tyrd_code = '07';
        $type_document_reference->tyrd_description = 'Procedimiento';
        $type_document_reference->save();

        $type_document_reference = new Type_reference_document();
        $type_document_reference->tyrd_id = 8;
        $type_document_reference->tyrd_code = '08';
        $type_document_reference->tyrd_description = 'Comprobante emitido en contingencia';
        $type_document_reference->save();

        $type_document_reference = new Type_reference_document();
        $type_document_reference->tyrd_id = 9;
        $type_document_reference->tyrd_code = '09';
        $type_document_reference->tyrd_description = 'Devolución mercadería';
        $type_document_reference->save();

        $type_document_reference = new Type_reference_document();
        $type_document_reference->tyrd_id = 10;
        $type_document_reference->tyrd_code = '10';
        $type_document_reference->tyrd_description = 'Sustituye factura rechazada por el Ministerio de Hacienda';
        $type_document_reference->save();

        $type_document_reference = new Type_reference_document();
        $type_document_reference->tyrd_id = 11;
        $type_document_reference->tyrd_code = '11';
        $type_document_reference->tyrd_description = 'Sustituye factura rechazada por el Receptor del comprobante';
        $type_document_reference->save();

        $type_document_reference = new Type_reference_document();
        $type_document_reference->tyrd_id = 12;
        $type_document_reference->tyrd_code = '12';
        $type_document_reference->tyrd_description = 'Sustituye Factura de exportación';
        $type_document_reference->save();

        $type_document_reference = new Type_reference_document();
        $type_document_reference->tyrd_id = 13;
        $type_document_reference->tyrd_code = '13';
        $type_document_reference->tyrd_description = 'Facturación mes vencido';
        $type_document_reference->save();

        $type_document_reference = new Type_reference_document();
        $type_document_reference->tyrd_id = 14;
        $type_document_reference->tyrd_code = '99';
        $type_document_reference->tyrd_description = 'Otros';
        $type_document_reference->save();
    }
}
