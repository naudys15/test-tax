<?php

use Illuminate\Database\Seeder;
use App\Models\Type_measure_unit;

class TypeMeasureUnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ty_mu = new Type_measure_unit();
        $ty_mu->tymu_id = 1;
        $ty_mu->tymu_title = 'Al';
        $ty_mu->tymu_description = 'Alquiler de uso habitacional';
        $ty_mu->save();

        $ty_mu = new Type_measure_unit();
        $ty_mu->tymu_id = 2;
        $ty_mu->tymu_title = 'Alc';
        $ty_mu->tymu_description = 'Alquiler de uso comercial';
        $ty_mu->save();
        
        $ty_mu = new Type_measure_unit();
        $ty_mu->tymu_id = 3;
        $ty_mu->tymu_title = 'Cm';
        $ty_mu->tymu_description = 'Comisiones';
        $ty_mu->save();

        $ty_mu = new Type_measure_unit();
        $ty_mu->tymu_id = 4;
        $ty_mu->tymu_title = 'I';
        $ty_mu->tymu_description = 'Intereses';
        $ty_mu->save();

        $ty_mu = new Type_measure_unit();
        $ty_mu->tymu_id = 5;
        $ty_mu->tymu_title = 'Os';
        $ty_mu->tymu_description = 'Otro tipo de servicio';
        $ty_mu->save();

        $ty_mu = new Type_measure_unit();
        $ty_mu->tymu_id = 6;
        $ty_mu->tymu_title = 'Sp';
        $ty_mu->tymu_description = 'Servicios Profesionales';
        $ty_mu->save();

        $ty_mu = new Type_measure_unit();
        $ty_mu->tymu_id = 7;
        $ty_mu->tymu_title = 'Spe';
        $ty_mu->tymu_description = 'Servicios personales';
        $ty_mu->save();

        $ty_mu = new Type_measure_unit();
        $ty_mu->tymu_id = 8;
        $ty_mu->tymu_title = 'St';
        $ty_mu->tymu_description = 'Servicios técnicos';
        $ty_mu->save();

        $ty_mu = new Type_measure_unit();
        $ty_mu->tymu_id = 9;
        $ty_mu->tymu_title = '1';
        $ty_mu->tymu_description = 'Uno (indice de refracción)';
        $ty_mu->save();

        $ty_mu = new Type_measure_unit();
        $ty_mu->tymu_id = 10;
        $ty_mu->tymu_title = '\'';
        $ty_mu->tymu_description = 'Minuto';
        $ty_mu->save();

        $ty_mu = new Type_measure_unit();
        $ty_mu->tymu_id = 11;
        $ty_mu->tymu_title = '\'\'';
        $ty_mu->tymu_description = 'Segundo';
        $ty_mu->save();

        $ty_mu = new Type_measure_unit();
        $ty_mu->tymu_id = 12;
        $ty_mu->tymu_title = 'ºC';
        $ty_mu->tymu_description = 'Grado Celsius';
        $ty_mu->save();

        $ty_mu = new Type_measure_unit();
        $ty_mu->tymu_id = 13;
        $ty_mu->tymu_title = '1/m';
        $ty_mu->tymu_description = '1 por metro';
        $ty_mu->save();

        $ty_mu = new Type_measure_unit();
        $ty_mu->tymu_id = 14;
        $ty_mu->tymu_title = 'A';
        $ty_mu->tymu_description = 'Ampere';
        $ty_mu->save();

        $ty_mu = new Type_measure_unit();
        $ty_mu->tymu_id = 15;
        $ty_mu->tymu_title = 'A/m';
        $ty_mu->tymu_description = 'Ampere por metro';
        $ty_mu->save();

        $ty_mu = new Type_measure_unit();
        $ty_mu->tymu_id = 16;
        $ty_mu->tymu_title = 'A/m2';
        $ty_mu->tymu_description = 'Ampere por metro cuadrado';
        $ty_mu->save();

        $ty_mu = new Type_measure_unit();
        $ty_mu->tymu_id = 17;
        $ty_mu->tymu_title = 'B';
        $ty_mu->tymu_description = 'Bel';
        $ty_mu->save();

        $ty_mu = new Type_measure_unit();
        $ty_mu->tymu_id = 18;
        $ty_mu->tymu_title = 'Bq';
        $ty_mu->tymu_description = 'Becquerel';
        $ty_mu->save();

        $ty_mu = new Type_measure_unit();
        $ty_mu->tymu_id = 19;
        $ty_mu->tymu_title = 'C';
        $ty_mu->tymu_description = 'Coulomb';
        $ty_mu->save();

        $ty_mu = new Type_measure_unit();
        $ty_mu->tymu_id = 20;
        $ty_mu->tymu_title = 'C/kg';
        $ty_mu->tymu_description = 'Coulomb por kilogramo';
        $ty_mu->save();

        $ty_mu = new Type_measure_unit();
        $ty_mu->tymu_id = 21;
        $ty_mu->tymu_title = 'C/m2';
        $ty_mu->tymu_description = 'Coulomb por metro cuadrado';
        $ty_mu->save();

        $ty_mu = new Type_measure_unit();
        $ty_mu->tymu_id = 22;
        $ty_mu->tymu_title = 'C/m3';
        $ty_mu->tymu_description = 'Coulomb por metro cúbico';
        $ty_mu->save();

        $ty_mu = new Type_measure_unit();
        $ty_mu->tymu_id = 23;
        $ty_mu->tymu_title = 'Cd';
        $ty_mu->tymu_description = 'Candela';
        $ty_mu->save();

        $ty_mu = new Type_measure_unit();
        $ty_mu->tymu_id = 24;
        $ty_mu->tymu_title = 'Cd/m2';
        $ty_mu->tymu_description = 'Candela por metro cuadrado';
        $ty_mu->save();

        $ty_mu = new Type_measure_unit();
        $ty_mu->tymu_id = 25;
        $ty_mu->tymu_title = 'cm';
        $ty_mu->tymu_description = 'Centímetro';
        $ty_mu->save();

        $ty_mu = new Type_measure_unit();
        $ty_mu->tymu_id = 26;
        $ty_mu->tymu_title = 'd';
        $ty_mu->tymu_description = 'Día';
        $ty_mu->save();

        $ty_mu = new Type_measure_unit();
        $ty_mu->tymu_id = 27;
        $ty_mu->tymu_title = 'eV';
        $ty_mu->tymu_description = 'Electronvolt';
        $ty_mu->save();

        $ty_mu = new Type_measure_unit();
        $ty_mu->tymu_id = 28;
        $ty_mu->tymu_title = 'F';
        $ty_mu->tymu_description = 'Farad';
        $ty_mu->save();

        $ty_mu = new Type_measure_unit();
        $ty_mu->tymu_id = 29;
        $ty_mu->tymu_title = 'F/m';
        $ty_mu->tymu_description = 'Farad por metro';
        $ty_mu->save();

        $ty_mu = new Type_measure_unit();
        $ty_mu->tymu_id = 30;
        $ty_mu->tymu_title = 'g';
        $ty_mu->tymu_description = 'Gramo';
        $ty_mu->save();

        $ty_mu = new Type_measure_unit();
        $ty_mu->tymu_id = 31;
        $ty_mu->tymu_title = 'Gal';
        $ty_mu->tymu_description = 'Galon';
        $ty_mu->save();

        $ty_mu = new Type_measure_unit();
        $ty_mu->tymu_id = 32;
        $ty_mu->tymu_title = 'Gy';
        $ty_mu->tymu_description = 'Gray';
        $ty_mu->save();

        $ty_mu = new Type_measure_unit();
        $ty_mu->tymu_id = 33;
        $ty_mu->tymu_title = 'Gy/s';
        $ty_mu->tymu_description = 'Gray por segundo';
        $ty_mu->save();

        $ty_mu = new Type_measure_unit();
        $ty_mu->tymu_id = 34;
        $ty_mu->tymu_title = 'H';
        $ty_mu->tymu_description = 'Henry';
        $ty_mu->save();

        $ty_mu = new Type_measure_unit();
        $ty_mu->tymu_id = 35;
        $ty_mu->tymu_title = 'h';
        $ty_mu->tymu_description = 'Hora';
        $ty_mu->save();

        $ty_mu = new Type_measure_unit();
        $ty_mu->tymu_id = 36;
        $ty_mu->tymu_title = 'H/m';
        $ty_mu->tymu_description = 'Henry por metro';
        $ty_mu->save();

        $ty_mu = new Type_measure_unit();
        $ty_mu->tymu_id = 37;
        $ty_mu->tymu_title = 'Hz';
        $ty_mu->tymu_description = 'Hertz';
        $ty_mu->save();

        $ty_mu = new Type_measure_unit();
        $ty_mu->tymu_id = 38;
        $ty_mu->tymu_title = 'J';
        $ty_mu->tymu_description = 'Joule';
        $ty_mu->save();

        $ty_mu = new Type_measure_unit();
        $ty_mu->tymu_id = 39;
        $ty_mu->tymu_title = 'J/(kg·K)';
        $ty_mu->tymu_description = 'Joule por kilogramo kelvin';
        $ty_mu->save();

        $ty_mu = new Type_measure_unit();
        $ty_mu->tymu_id = 40;
        $ty_mu->tymu_title = 'J/(mol·K)';
        $ty_mu->tymu_description = 'Joule por mol kelvin';
        $ty_mu->save();

        $ty_mu = new Type_measure_unit();
        $ty_mu->tymu_id = 41;
        $ty_mu->tymu_title = 'J/K';
        $ty_mu->tymu_description = 'Joule por kelvin';
        $ty_mu->save();

        $ty_mu = new Type_measure_unit();
        $ty_mu->tymu_id = 42;
        $ty_mu->tymu_title = 'J/kg';
        $ty_mu->tymu_description = 'Joule por kilogramo';
        $ty_mu->save();

        $ty_mu = new Type_measure_unit();
        $ty_mu->tymu_id = 43;
        $ty_mu->tymu_title = 'J/m3';
        $ty_mu->tymu_description = 'Joule por metro cúbico';
        $ty_mu->save();

        $ty_mu = new Type_measure_unit();
        $ty_mu->tymu_id = 44;
        $ty_mu->tymu_title = 'J/mol';
        $ty_mu->tymu_description = 'Joule por mol';
        $ty_mu->save();

        $ty_mu = new Type_measure_unit();
        $ty_mu->tymu_id = 45;
        $ty_mu->tymu_title = 'K';
        $ty_mu->tymu_description = 'Kelvin';
        $ty_mu->save();

        $ty_mu = new Type_measure_unit();
        $ty_mu->tymu_id = 46;
        $ty_mu->tymu_title = 'Kat';
        $ty_mu->tymu_description = 'Katal';
        $ty_mu->save();

        $ty_mu = new Type_measure_unit();
        $ty_mu->tymu_id = 47;
        $ty_mu->tymu_title = 'Kat/m3';
        $ty_mu->tymu_description = 'Katal por metro cúbico';
        $ty_mu->save();

        $ty_mu = new Type_measure_unit();
        $ty_mu->tymu_id = 48;
        $ty_mu->tymu_title = 'Kg';
        $ty_mu->tymu_description = 'Kilogramo';
        $ty_mu->save();

        $ty_mu = new Type_measure_unit();
        $ty_mu->tymu_id = 49;
        $ty_mu->tymu_title = 'Kg/m3';
        $ty_mu->tymu_description = 'Kilogramo por metro cúbico';
        $ty_mu->save();

        $ty_mu = new Type_measure_unit();
        $ty_mu->tymu_id = 50;
        $ty_mu->tymu_title = 'Km';
        $ty_mu->tymu_description = 'Kilometro';
        $ty_mu->save();

        $ty_mu = new Type_measure_unit();
        $ty_mu->tymu_id = 51;
        $ty_mu->tymu_title = 'Kw';
        $ty_mu->tymu_description = 'Kilovatios';
        $ty_mu->save();

        $ty_mu = new Type_measure_unit();
        $ty_mu->tymu_id = 52;
        $ty_mu->tymu_title = 'L';
        $ty_mu->tymu_description = 'Litro';
        $ty_mu->save();

        $ty_mu = new Type_measure_unit();
        $ty_mu->tymu_id = 53;
        $ty_mu->tymu_title = 'Lm';
        $ty_mu->tymu_description = 'Lumen';
        $ty_mu->save();

        $ty_mu = new Type_measure_unit();
        $ty_mu->tymu_id = 54;
        $ty_mu->tymu_title = 'Ln';
        $ty_mu->tymu_description = 'Pulgada';
        $ty_mu->save();

        $ty_mu = new Type_measure_unit();
        $ty_mu->tymu_id = 55;
        $ty_mu->tymu_title = 'Lx';
        $ty_mu->tymu_description = 'Lux';
        $ty_mu->save();

        $ty_mu = new Type_measure_unit();
        $ty_mu->tymu_id = 56;
        $ty_mu->tymu_title = 'm';
        $ty_mu->tymu_description = 'Metro';
        $ty_mu->save();

        $ty_mu = new Type_measure_unit();
        $ty_mu->tymu_id = 57;
        $ty_mu->tymu_title = 'm/s';
        $ty_mu->tymu_description = 'Metro por segundo';
        $ty_mu->save();

        $ty_mu = new Type_measure_unit();
        $ty_mu->tymu_id = 58;
        $ty_mu->tymu_title = 'm/s2';
        $ty_mu->tymu_description = 'Metro por segundo cuadrado';
        $ty_mu->save();

        $ty_mu = new Type_measure_unit();
        $ty_mu->tymu_id = 59;
        $ty_mu->tymu_title = 'm2';
        $ty_mu->tymu_description = 'Metro cuadrado';
        $ty_mu->save();

        $ty_mu = new Type_measure_unit();
        $ty_mu->tymu_id = 60;
        $ty_mu->tymu_title = 'm3';
        $ty_mu->tymu_description = 'metro cúbico';
        $ty_mu->save();

        $ty_mu = new Type_measure_unit();
        $ty_mu->tymu_id = 61;
        $ty_mu->tymu_title = 'min';
        $ty_mu->tymu_description = 'Minuto';
        $ty_mu->save();

        $ty_mu = new Type_measure_unit();
        $ty_mu->tymu_id = 62;
        $ty_mu->tymu_title = 'mL';
        $ty_mu->tymu_description = 'Mililitro';
        $ty_mu->save();

        $ty_mu = new Type_measure_unit();
        $ty_mu->tymu_id = 63;
        $ty_mu->tymu_title = 'Mol';
        $ty_mu->tymu_description = 'Mol';
        $ty_mu->save();

        $ty_mu = new Type_measure_unit();
        $ty_mu->tymu_id = 64;
        $ty_mu->tymu_title = 'Mol/m3';
        $ty_mu->tymu_description = 'Mol por metro cúbico';
        $ty_mu->save();

        $ty_mu = new Type_measure_unit();
        $ty_mu->tymu_id = 65;
        $ty_mu->tymu_title = 'N';
        $ty_mu->tymu_description = 'Newton';
        $ty_mu->save();

        $ty_mu = new Type_measure_unit();
        $ty_mu->tymu_id = 66;
        $ty_mu->tymu_title = 'N/m';
        $ty_mu->tymu_description = 'Newton por metro';
        $ty_mu->save();

        $ty_mu = new Type_measure_unit();
        $ty_mu->tymu_id = 67;
        $ty_mu->tymu_title = 'N·m';
        $ty_mu->tymu_description = 'Newton metro';
        $ty_mu->save();

        $ty_mu = new Type_measure_unit();
        $ty_mu->tymu_id = 68;
        $ty_mu->tymu_title = 'Np';
        $ty_mu->tymu_description = 'Neper';
        $ty_mu->save();

        $ty_mu = new Type_measure_unit();
        $ty_mu->tymu_id = 69;
        $ty_mu->tymu_title = 'º';
        $ty_mu->tymu_description = 'Grado';
        $ty_mu->save();

        $ty_mu = new Type_measure_unit();
        $ty_mu->tymu_id = 70;
        $ty_mu->tymu_title = 'Oz';
        $ty_mu->tymu_description = 'Onzas';
        $ty_mu->save();

        $ty_mu = new Type_measure_unit();
        $ty_mu->tymu_id = 71;
        $ty_mu->tymu_title = 'Pa';
        $ty_mu->tymu_description = 'Pascal';
        $ty_mu->save();

        $ty_mu = new Type_measure_unit();
        $ty_mu->tymu_id = 72;
        $ty_mu->tymu_title = 'Pa·s';
        $ty_mu->tymu_description = 'Pascal segundo';
        $ty_mu->save();

        $ty_mu = new Type_measure_unit();
        $ty_mu->tymu_id = 73;
        $ty_mu->tymu_title = 'Rad';
        $ty_mu->tymu_description = 'Radian';
        $ty_mu->save();

        $ty_mu = new Type_measure_unit();
        $ty_mu->tymu_id = 74;
        $ty_mu->tymu_title = 'Rad/s';
        $ty_mu->tymu_description = 'Radián por segundo';
        $ty_mu->save();

        $ty_mu = new Type_measure_unit();
        $ty_mu->tymu_id = 75;
        $ty_mu->tymu_title = 'Rad/s2';
        $ty_mu->tymu_description = 'Radián por segundo cuadrado';
        $ty_mu->save();

        $ty_mu = new Type_measure_unit();
        $ty_mu->tymu_id = 76;
        $ty_mu->tymu_title = 's';
        $ty_mu->tymu_description = 'Segundo';
        $ty_mu->save();

        $ty_mu = new Type_measure_unit();
        $ty_mu->tymu_id = 77;
        $ty_mu->tymu_title = 'S';
        $ty_mu->tymu_description = 'Siemens';
        $ty_mu->save();

        $ty_mu = new Type_measure_unit();
        $ty_mu->tymu_id = 78;
        $ty_mu->tymu_title = 'Sr';
        $ty_mu->tymu_description = 'Estereorradián';
        $ty_mu->save();

        $ty_mu = new Type_measure_unit();
        $ty_mu->tymu_id = 79;
        $ty_mu->tymu_title = 'Sv';
        $ty_mu->tymu_description = 'Sievert';
        $ty_mu->save();

        $ty_mu = new Type_measure_unit();
        $ty_mu->tymu_id = 80;
        $ty_mu->tymu_title = 'T';
        $ty_mu->tymu_description = 'Tesla';
        $ty_mu->save();

        $ty_mu = new Type_measure_unit();
        $ty_mu->tymu_id = 81;
        $ty_mu->tymu_title = 'u';
        $ty_mu->tymu_description = 'Unidad de masa atómica unificada';
        $ty_mu->save();

        $ty_mu = new Type_measure_unit();
        $ty_mu->tymu_id = 82;
        $ty_mu->tymu_title = 'Ua';
        $ty_mu->tymu_description = 'Unidad astronómica';
        $ty_mu->save();

        $ty_mu = new Type_measure_unit();
        $ty_mu->tymu_id = 83;
        $ty_mu->tymu_title = 'Unid';
        $ty_mu->tymu_description = 'Unidad';
        $ty_mu->save();

        $ty_mu = new Type_measure_unit();
        $ty_mu->tymu_id = 84;
        $ty_mu->tymu_title = 'V';
        $ty_mu->tymu_description = 'Volt';
        $ty_mu->save();

        $ty_mu = new Type_measure_unit();
        $ty_mu->tymu_id = 85;
        $ty_mu->tymu_title = 'V/m';
        $ty_mu->tymu_description = 'Volt por metro';
        $ty_mu->save();

        $ty_mu = new Type_measure_unit();
        $ty_mu->tymu_id = 86;
        $ty_mu->tymu_title = 'W';
        $ty_mu->tymu_description = 'Watt';
        $ty_mu->save();

        $ty_mu = new Type_measure_unit();
        $ty_mu->tymu_id = 87;
        $ty_mu->tymu_title = 'W/(m·K)';
        $ty_mu->tymu_description = 'Watt por metro kevin';
        $ty_mu->save();

        $ty_mu = new Type_measure_unit();
        $ty_mu->tymu_id = 88;
        $ty_mu->tymu_title = 'W/(m2·sr)';
        $ty_mu->tymu_description = 'Watt por metro cuadrado estereorradián';
        $ty_mu->save();
        
        $ty_mu = new Type_measure_unit();
        $ty_mu->tymu_id = 89;
        $ty_mu->tymu_title = 'W/m2';
        $ty_mu->tymu_description = 'Watt por metro cuadrado';
        $ty_mu->save();
        
        $ty_mu = new Type_measure_unit();
        $ty_mu->tymu_id = 90;
        $ty_mu->tymu_title = 'W/sr';
        $ty_mu->tymu_description = 'Watt por estereorradián';
        $ty_mu->save();
        
        $ty_mu = new Type_measure_unit();
        $ty_mu->tymu_id = 91;
        $ty_mu->tymu_title = 'Wb';
        $ty_mu->tymu_description = 'Weber';
        $ty_mu->save();
        
        $ty_mu = new Type_measure_unit();
        $ty_mu->tymu_id = 92;
        $ty_mu->tymu_title = 'Ω';
        $ty_mu->tymu_description = 'Ohm';
        $ty_mu->save();
        
        $ty_mu = new Type_measure_unit();
        $ty_mu->tymu_id = 93;
        $ty_mu->tymu_title = 'Otros';
        $ty_mu->tymu_description = 'Otros';
        $ty_mu->save();

    }
}
