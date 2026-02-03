<?php

namespace Database\Seeders;

use App\Models\Rider;
use Illuminate\Database\Seeder;

class RiderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Rider::truncate();
        Rider::create(
            [
                'name'           => 'Example Rider',
                'email'          => 'example@rider.com',
                'password'       => bcrypt('12345'),
                'store_password' => '12345',
                'contact_number' => '018182882938',
                'district_id'    => 1,
                'upazila_id'     => 1,
                'area_id'        => 1,
                'branch_id'      => 1,
                'date'           => date('Y-m-d'),
            ]
        );
    }
}
