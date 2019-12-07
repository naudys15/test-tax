<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Clients;
use App\Models\Type_client;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rol_client = Type_client::where('tycl_description','cliente')->first();
        $client = new Clients();
        $client->clie_id = 1;
        $client->tycl_id = 1;
        $client->clie_firstname = 'Prueba';
        $client->clie_lastname = 'del Sistema';
        $client->clie_dni = 123456789;
        $client->tydi_id = 1;
        $client->clie_phonenumber = '581234567891';
        $client->clie_email = 'pruebadelsistema@prueba.com';
        $client->clie_username = 'prueba01';
        $client->clie_password = Hash::make('12345');
        $client->clie_business_name = 'Prueba Empresa';
        $client->clie_legal_dni = 123456789;
        $client->coun_id = 1;
        $client->prov_id = 1;
        $client->cant_id = 1;
        $client->dist_id = 1;
        $client->clie_address = "Prueba";
        $client->save();
    }
}
