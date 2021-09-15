<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InwardQualityCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tbl_inward_quality_categories')->insert([
            [
                'inward_category_name' => 'Yarn'
            ],
            [
                'inward_category_name' => 'Grey'
            ]
        ]);
    }
}
