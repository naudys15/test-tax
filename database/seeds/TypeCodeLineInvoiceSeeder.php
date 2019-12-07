<?php

use Illuminate\Database\Seeder;
use App\Models\Type_code_line_invoice;

class TypeCodeLineInvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $type_code_line_invoice = new Type_code_line_invoice();
        $type_code_line_invoice->tylc_id = 1;
        $type_code_line_invoice->tylc_code = '01';
        $type_code_line_invoice->tylc_description = 'Genera crédito IVA';
        $type_code_line_invoice->save();

        $type_code_line_invoice = new Type_code_line_invoice();
        $type_code_line_invoice->tylc_id = 2;
        $type_code_line_invoice->tylc_code = '02';
        $type_code_line_invoice->tylc_description = 'Genera Crédito parcial del IVA';
        $type_code_line_invoice->save();

        $type_code_line_invoice = new Type_code_line_invoice();
        $type_code_line_invoice->tylc_id = 3;
        $type_code_line_invoice->tylc_code = '03';
        $type_code_line_invoice->tylc_description = 'Bienes de Capital';
        $type_code_line_invoice->save();

        $type_code_line_invoice = new Type_code_line_invoice();
        $type_code_line_invoice->tylc_id = 4;
        $type_code_line_invoice->tylc_code = '04';
        $type_code_line_invoice->tylc_description = 'Gasto corriente no genera crédito';
        $type_code_line_invoice->save();

        $type_code_line_invoice = new Type_code_line_invoice();
        $type_code_line_invoice->tylc_id = 5;
        $type_code_line_invoice->tylc_code = '05';
        $type_code_line_invoice->tylc_description = 'Proporcionalidad';
        $type_code_line_invoice->save();
    }
}
