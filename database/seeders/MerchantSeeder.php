<?php

namespace Database\Seeders;

use App\Models\Merchant;
use Illuminate\Database\Seeder;

class MerchantSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        Merchant::truncate();
        Merchant::create(
            [
                'name'           => 'Example Merchant',
                'email'          => 'example@merchant.com',
                'password'       => bcrypt('12345'),
                'store_password' => '12345',
                'company_name'   => 'Example Company',
                'contact_number' => '018182882938',
                'district_id'    => 1,
                'upazila_id'     => 1,
                'area_id'        => 1,
                'date'           => date('Y-m-d'),
            ]
        );
    }
}
