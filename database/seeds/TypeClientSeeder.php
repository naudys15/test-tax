<?php

use Illuminate\Database\Seeder;
use App\Models\Type_client;

class TypeClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ty_cl = new Type_client();
        $ty_cl->tycl_id = 1;
        $ty_cl->tycl_title = 'Admin';
        $ty_cl->tycl_description = 'admin';
        $ty_cl->save();

        $ty_cl = new Type_client();
        $ty_cl->tycl_id = 2;
        $ty_cl->tycl_title = 'Cliente';
        $ty_cl->tycl_description = 'cliente';
        $ty_cl->save();
    }
}
