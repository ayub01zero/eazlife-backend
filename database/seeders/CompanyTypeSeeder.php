<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompanyTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('company_types')->insert([
            'name' => 'Restaurant',
            'group' => true,
        ]);
        DB::table('company_types')->insert([
            'name' => 'Shop',
            'group' => false,
        ]);
        DB::table('company_types')->insert([
            'name' => 'Service',
            'group' => false,
        ]);
    }
}
