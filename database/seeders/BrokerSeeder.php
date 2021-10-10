<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BrokerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tbl_brokers')->insert([
            'broker_name' => 'None',
            'broker_contact_no' => '0000000000'
        ]);                
    }
}
