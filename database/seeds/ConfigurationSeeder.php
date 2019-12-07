<?php

use Illuminate\Database\Seeder;
use App\Models\Configurations;

class ConfigurationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $configuration = new Configurations();
        $configuration->conf_id = 1;
        $configuration->clie_id = 1;
        $configuration->conf_iva_sale = "13%";
        $configuration->conf_dolar_value = 582.82;
        $configuration->save();
    }
}
