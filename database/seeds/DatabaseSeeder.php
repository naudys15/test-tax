<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(ModulesSeeder::class);
        $this->call(SubmodulesSeeder::class);
        $this->call(TypeClientSeeder::class);
        $this->call(TypeDocumentSeeder::class);
        $this->call(TypeReportSeeder::class);
        $this->call(TypeDocumentIdSeeder::class);
        $this->call(TypeMeasureUnitSeeder::class);
        $this->call(TypePaymentMethodSeeder::class);
        $this->call(TypeCodeLineInvoiceSeeder::class);
        $this->call(TypeSaleTermsSeeder::class);
        //$this->call(TypeDocumentReferenceSeeder::class);
        $this->call(TypeTaxIvaSeeder::class);
        $this->call(TypeTaxSeeder::class);
        $this->call(DataCostaRica::class);
        $this->call(ClientSeeder::class);
        $this->call(ConfigurationSeeder::class);
        $this->call(EndOfMonthSeeder::class);
    }
}
