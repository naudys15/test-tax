<?php

use Illuminate\Database\Seeder;

class DataCostaRica extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $path = 'database/sql/dataCostaRica.sql';
        DB::unprepared(file_get_contents($path));
    }
}
