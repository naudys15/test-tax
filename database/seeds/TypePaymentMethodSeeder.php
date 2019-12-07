<?php

use Illuminate\Database\Seeder;
use App\Models\Type_payment_method;

class TypePaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ty_pm = new Type_payment_method();
        $ty_pm->typm_id = 1;
        $ty_pm->typm_code = '01';
        $ty_pm->typm_description = 'Efectivo';
        $ty_pm->save();

        $ty_pm = new Type_payment_method();
        $ty_pm->typm_id = 2;
        $ty_pm->typm_code = '02';
        $ty_pm->typm_description = 'Tarjeta';
        $ty_pm->save();
        
        $ty_pm = new Type_payment_method();
        $ty_pm->typm_id = 3;
        $ty_pm->typm_code = '03';
        $ty_pm->typm_description = 'Cheque';
        $ty_pm->save();

        $ty_pm = new Type_payment_method();
        $ty_pm->typm_id = 4;
        $ty_pm->typm_code = '04';
        $ty_pm->typm_description = 'Transferencia – depósito bancario';
        $ty_pm->save();

        $ty_pm = new Type_payment_method();
        $ty_pm->typm_id = 5;
        $ty_pm->typm_code = '05';
        $ty_pm->typm_description = 'Recaudado por terceros';
        $ty_pm->save();

        $ty_pm = new Type_payment_method();
        $ty_pm->typm_id = 6;
        $ty_pm->typm_code = '99';
        $ty_pm->typm_description = 'Otros';
        $ty_pm->save();
    }
}
