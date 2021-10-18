<?php

namespace Database\Seeders;

use App\Models\Address;
use Illuminate\Database\Seeder;

class AddressesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Address::truncate();
        (new Address)->fill([
            "zip" => "1010032",
            "pref" => 13,
            "city" => "千代田区",
            "addr" => "岩本町",
        ])->save();
        (new Address)->fill([
            "zip" => "0600061",
            "pref" => 1,
            "city" => "札幌市中央区",
            "addr" => "南一条西",
        ])->save();
    }
}
