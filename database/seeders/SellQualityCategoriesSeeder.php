<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SellQualityCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tbl_sell_quality_categories')->insert([
            [
                'sell_category_name' => 'Grey'
            ],
            [
                'sell_category_name' => 'Beam'
            ],
            [
                'sell_category_name' => 'Roll'
            ],
        ]);
    }
}
