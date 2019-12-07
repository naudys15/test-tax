<?php

use Illuminate\Database\Seeder;
use App\Models\Modules;

class ModulesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $module = new Modules();
        $module->modu_id = 1;
        $module->modu_title = 'Facturas';
        $module->modu_description = 'Módulo de facturas';
        $module->modu_visible = 1;
        $module->save();
    }
}
