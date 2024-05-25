<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FulfillmentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('fulfillment_types')->insert([
            'name' => 'delivery',
        ]);
        DB::table('fulfillment_types')->insert([
            'name' => 'pickup',
        ]);
        DB::table('fulfillment_types')->insert([
            'name' => 'reservation',
        ]);
        DB::table('fulfillment_types')->insert([
            'name' => 'onlocation',
        ]);
        DB::table('fulfillment_types')->insert([
            'name' => 'call',
        ]);
    }
}
