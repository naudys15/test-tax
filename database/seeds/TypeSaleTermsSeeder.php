<?php

use Illuminate\Database\Seeder;
use App\Models\Type_sale_terms;

class TypeSaleTermsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ty_st = new Type_sale_terms();
        $ty_st->tyst_id = 1;
        $ty_st->tyst_code = '01';
        $ty_st->tyst_description = 'Contado';
        $ty_st->save();

        $ty_st = new Type_sale_terms();
        $ty_st->tyst_id = 2;
        $ty_st->tyst_code = '02';
        $ty_st->tyst_description = 'Crédito';
        $ty_st->save();
        
        $ty_st = new Type_sale_terms();
        $ty_st->tyst_id = 3;
        $ty_st->tyst_code = '03';
        $ty_st->tyst_description = 'Consignación';
        $ty_st->save();

        $ty_st = new Type_sale_terms();
        $ty_st->tyst_id = 4;
        $ty_st->tyst_code = '04';
        $ty_st->tyst_description = 'Apartado';
        $ty_st->save();

        $ty_st = new Type_sale_terms();
        $ty_st->tyst_id = 5;
        $ty_st->tyst_code = '05';
        $ty_st->tyst_description = 'Arrendamiento con opción de compra';
        $ty_st->save();

        $ty_st = new Type_sale_terms();
        $ty_st->tyst_id = 6;
        $ty_st->tyst_code = '06';
        $ty_st->tyst_description = 'Arrendamiento en función financiera';
        $ty_st->save();
        
        $ty_st = new Type_sale_terms();
        $ty_st->tyst_id = 7;
        $ty_st->tyst_code = '07';
        $ty_st->tyst_description = 'Cobro a favor de un tercero';
        $ty_st->save();

        $ty_st = new Type_sale_terms();
        $ty_st->tyst_id = 8;
        $ty_st->tyst_code = '08';
        $ty_st->tyst_description = 'Servicios prestados al Estado a crédito';
        $ty_st->save();

        $ty_st = new Type_sale_terms();
        $ty_st->tyst_id = 9;
        $ty_st->tyst_code = '09';
        $ty_st->tyst_description = 'Pago del servicios prestado al Estado';
        $ty_st->save();

        $ty_st = new Type_sale_terms();
        $ty_st->tyst_id = 10;
        $ty_st->tyst_code = '99';
        $ty_st->tyst_description = 'Otros';
        $ty_st->save();
    }
}
