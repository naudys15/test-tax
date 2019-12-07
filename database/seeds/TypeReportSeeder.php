<?php

use Illuminate\Database\Seeder;
use App\Models\Type_report;

class TypeReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ty_re = new Type_report();
        $ty_re->tyre_id = 1;
        $ty_re->tyre_title = 'Iva';
        $ty_re->tyre_description = 'D-104 Iva';
        $ty_re->save();

        $ty_re = new Type_report();
        $ty_re->tyre_id = 2;
        $ty_re->tyre_title = 'Renta';
        $ty_re->tyre_description = 'D-101 Renta';
        $ty_re->save();

        $ty_re = new Type_report();
        $ty_re->tyre_id = 3;
        $ty_re->tyre_title = 'Proveedores';
        $ty_re->tyre_description = 'D-151 Proveedores';
        $ty_re->save();
    }
}
