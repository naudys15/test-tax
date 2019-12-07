<?php

use Illuminate\Database\Seeder;
use App\Models\Type_tax_iva;

class TypeTaxIvaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ti_va = new Type_tax_iva();
        $ti_va->tiva_id = 1;
        $ti_va->tiva_code = '01';
        $ti_va->tiva_percentage = '0%';
        $ti_va->tiva_description = 'Tarifa 0%';
        $ti_va->save();

        $ti_va = new Type_tax_iva();
        $ti_va->tiva_id = 2;
        $ti_va->tiva_code = '02';
        $ti_va->tiva_percentage = '1%';
        $ti_va->tiva_description = 'Tarifa reducida 1%';
        $ti_va->save();

        $ti_va = new Type_tax_iva();
        $ti_va->tiva_id = 3;
        $ti_va->tiva_code = '03';
        $ti_va->tiva_percentage = '2%';
        $ti_va->tiva_description = 'Tarifa reducida 2%';
        $ti_va->save();

        $ti_va = new Type_tax_iva();
        $ti_va->tiva_id = 4;
        $ti_va->tiva_code = '04';
        $ti_va->tiva_percentage = '4%';
        $ti_va->tiva_description = 'Tarifa reducida 4%';
        $ti_va->save();

        $ti_va = new Type_tax_iva();
        $ti_va->tiva_id = 5;
        $ti_va->tiva_code = '05';
        $ti_va->tiva_percentage = '0%';
        $ti_va->tiva_description = 'Transitorio 0%';
        $ti_va->save();

        $ti_va = new Type_tax_iva();
        $ti_va->tiva_id = 6;
        $ti_va->tiva_code = '06';
        $ti_va->tiva_percentage = '4%';
        $ti_va->tiva_description = 'Transitorio 4%';
        $ti_va->save();

        $ti_va = new Type_tax_iva();
        $ti_va->tiva_id = 7;
        $ti_va->tiva_code = '07';
        $ti_va->tiva_percentage = '8%';
        $ti_va->tiva_description = 'Transitorio 8%';
        $ti_va->save();

        $ti_va = new Type_tax_iva();
        $ti_va->tiva_id = 8;
        $ti_va->tiva_code = '08';
        $ti_va->tiva_percentage = '13%';
        $ti_va->tiva_description = 'Tarifa general 13%';
        $ti_va->save();
    }
}
