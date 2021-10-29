<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GSTCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tbl_gst_codes')->insert([
            [
                'state_name' => 'Jammu & Kashmir',
                'gst_code' => 1
            ],
            [
                'state_name' => 'Himachal Pradesh',
                'gst_code' => 2
            ],
            [
                'state_name' => 'Punjab',
                'gst_code' => 3
            ],
            [
                'state_name' => 'Chandigarh',
                'gst_code' => 4
            ],
            [
                'state_name' => 'Uttarakhand',
                'gst_code' => 5
            ],
            [
                'state_name' => 'Haryana',
                'gst_code' => 6
            ],
            [
                'state_name' => 'Delhi',
                'gst_code' => 7
            ],
            [
                'state_name' => 'Rajasthan',
                'gst_code' => 8
            ],
            [
                'state_name' => 'Uttar Pradesh',
                'gst_code' => 9
            ],
            [
                'state_name' => 'Bihar',
                'gst_code' => 10
            ],
            [
                'state_name' => 'Sikkim',
                'gst_code' => 11
            ],
            [
                'state_name' => 'Arunachal Pradesh',
                'gst_code' => 12
            ],
            [
                'state_name' => 'Nagaland',
                'gst_code' => 13
            ],
            [
                'state_name' => 'Manipur',
                'gst_code' => 14
            ],
            [
                'state_name' => 'Mizoram',
                'gst_code' => 15
            ],
            [
                'state_name' => 'Tripura',
                'gst_code' => 16
            ],
            [
                'state_name' => 'Meghalaya',
                'gst_code' => 17
            ],
            [
                'state_name' => 'Assam',
                'gst_code' => 18
            ],
            [
                'state_name' => 'West Bengal',
                'gst_code' => 19
            ],
            [
                'state_name' => 'Jharkhand',
                'gst_code' => 20
            ],
            [
                'state_name' => 'Orissa',
                'gst_code' => 21
            ],
            [
                'state_name' => 'Chhatisgarh',
                'gst_code' => 22
            ],
            [
                'state_name' => 'Madhya Pradesh',
                'gst_code' => 23
            ],
            [
                'state_name' => 'Gujarat',
                'gst_code' => 24
            ],
            [
                'state_name' => 'Daman & Diu',
                'gst_code' => 25
            ],
            [
                'state_name' => 'Dadra & Nagar Haveli',
                'gst_code' => 26
            ],
            [
                'state_name' => 'Maharashtra',
                'gst_code' => 27
            ],
            [
                'state_name' => 'Andhra Pradesh (Old)',
                'gst_code' => 28
            ],
            [
                'state_name' => 'Karnataka',
                'gst_code' => 29
            ],
            [
                'state_name' => 'Goa',
                'gst_code' => 30
            ],
            [
                'state_name' => 'Lakshadweep',
                'gst_code' => 31
            ],
            [
                'state_name' => 'Kerala',
                'gst_code' => 32
            ],
            [
                'state_name' => 'Tamil Nadu',
                'gst_code' => 33
            ],
            [
                'state_name' => 'Puducherry',
                'gst_code' => 34
            ],
            [
                'state_name' => 'Andaman & Nicobar Islands',
                'gst_code' => 35
            ],
            [
                'state_name' => 'Telengana',
                'gst_code' => 36
            ],
            [
                'state_name' => 'Andhra Pradesh (New)',
                'gst_code' => 37
            ]
        ]);
    }
}
