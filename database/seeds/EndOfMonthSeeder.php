<?php

use Illuminate\Database\Seeder;
use App\Models\End_of_month;

class EndOfMonthSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $endofmonth = new End_of_month();
        $endofmonth->enom_id = 1;
        $endofmonth->clie_id = 1;
        $endofmonth->enom_start_date = "2019-07-01";
        $endofmonth->enom_end_date = "2019-07-31";
        $endofmonth->enom_name = "Julio 2019";
        $endofmonth->enom_description = "Período de Julio 2019";
        $endofmonth->enom_type_period = "monthly";
        $endofmonth->save();
    }
}
