<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class PaymentPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('payment_plan')->insert([
            'string' => 'demo',
            'price' => 0.00,
            'currency' => "EUR",
            'extra' => '',
            'visible' => false
        ]);

        DB::table('payment_plan')->insert([
            'string' => 'standard',
            'price' => 0.00,
            'currency' => "EUR",
            'extra' => 'limited_invoices,client_database,single_user_account,available_languages,free_additional_languages,invoice_styles,tech_support_time',
            'visible' => true
        ]);

        DB::table('payment_plan')->insert([
            'string' => 'gold',
            'price' => 7.99,
            'currency' => "EUR",
            'extra' => 'unlimited_invoices,invoice_copying,monthly_copying_fixed,multiple_firms,client_database,multiple_user_accounts,product_database,available_languages,free_additional_languages,invoice_styles,messaging,tech_support_time',
            'visible' => true
        ]);

        DB::table('payment_plan')->insert([
            'string' => 'diamond',
            'price' => 14.99,
            'currency' => "EUR",
            'extra' => 'unlimited_invoices,invoice_copying,monthly_copying_fixed,multiple_firms,client_database,event_reminder,unlimited_user_accounts,product_database,barcode_registration,available_languages,free_additional_languages,invoice_styles,messaging,utility_cost_registration,tech_support_time',
            'visible' => true
        ]);


        DB::table('payment_plan')->insert([
            'string' => 'universe',
            'price' => 29.99,
            'currency' => "EUR",
            'extra' =>  'booking_app,UVA_app,utility_management_app,accounting_app,24h_tech_support,more_features_coming',
            'visible' => true
        ]);

    }
}
