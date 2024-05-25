<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompanyTypeFulfillmentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Restaurant has delivery and pick up and reservation
        DB::table('company_type_fulfillment_type')->insert([
            'company_type_id' => 13,
            'fulfillment_type_id' => 13
        ]);
        DB::table('company_type_fulfillment_type')->insert([
            'company_type_id' => 13,
            'fulfillment_type_id' => 14
        ]);
        DB::table('company_type_fulfillment_type')->insert([
            'company_type_id' => 13,
            'fulfillment_type_id' => 15
        ]);

        // Shop has delivery and pick up
        DB::table('company_type_fulfillment_type')->insert([
            'company_type_id' => 14,
            'fulfillment_type_id' => 13
        ]);
        DB::table('company_type_fulfillment_type')->insert([
            'company_type_id' => 14,
            'fulfillment_type_id' => 14
        ]);

        // Service provider has reservation
        DB::table('company_type_fulfillment_type')->insert([
            'company_type_id' => 15,
            'fulfillment_type_id' => 15
        ]);

        // Service provider has onlocation
        DB::table('company_type_fulfillment_type')->insert([
            'company_type_id' => 15,
            'fulfillment_type_id' => 16
        ]);

        // Service provider has call
        DB::table('company_type_fulfillment_type')->insert([
            'company_type_id' => 15,
            'fulfillment_type_id' => 17
        ]);
    }
}
