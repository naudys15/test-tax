<?php

use Illuminate\Database\Seeder;
use App\Models\Type_tax;

class TypeTaxSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tax = new Type_tax();
        $tax->tax_id = 1;
        $tax->tax_code = '01';
        $tax->tax_description = 'Impuesto al Valor Agregado';
        $tax->save();

        $tax = new Type_tax();
        $tax->tax_id = 2;
        $tax->tax_code = '02';
        $tax->tax_description = 'Impuesto Selectivo de Consumo';
        $tax->save();

        $tax = new Type_tax();
        $tax->tax_id = 3;
        $tax->tax_code = '03';
        $tax->tax_description = 'Impuesto Único a los Combustibles';
        $tax->save();

        $tax = new Type_tax();
        $tax->tax_id = 4;
        $tax->tax_code = '04';
        $tax->tax_description = 'Impuesto específico de Bebidas Alcohólicas';
        $tax->save();

        $tax = new Type_tax();
        $tax->tax_id = 5;
        $tax->tax_code = '05';
        $tax->tax_description = 'Impuesto Específico sobre las bebidas envasadas sin contenido alcohólico y jabones de tocador';
        $tax->save();

        $tax = new Type_tax();
        $tax->tax_id = 6;
        $tax->tax_code = '06';
        $tax->tax_description = 'Impuesto a los Productos de Tabaco';
        $tax->save();

        $tax = new Type_tax();
        $tax->tax_id = 7;
        $tax->tax_code = '07';
        $tax->tax_description = 'IVA (cálculo especial)';
        $tax->save();

        $tax = new Type_tax();
        $tax->tax_id = 8;
        $tax->tax_code = '08';
        $tax->tax_description = 'IVA Régimen de Bienes Usados (Factor)';
        $tax->save();

        $tax = new Type_tax();
        $tax->tax_id = 9;
        $tax->tax_code = '12';
        $tax->tax_description = 'Impuesto Específico al Cemento';
        $tax->save();

        $tax = new Type_tax();
        $tax->tax_id = 10;
        $tax->tax_code = '99';
        $tax->tax_description = 'Otros';
        $tax->save();
    }
}
