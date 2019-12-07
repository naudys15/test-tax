<?php

use Illuminate\Database\Seeder;
use App\Models\Submodules;

class SubmodulesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $submodule = new Submodules();
        $submodule->subm_id = 1;
        $submodule->subm_title = 'Carga de facturas';
        $submodule->subm_description = 'Venta';
        $submodule->modu_id = 1;
        $submodule->subm_visible = 1;
        $submodule->save();
    }
}
