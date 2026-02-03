<?php

namespace Database\Seeders;

use App\Models\Branch;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Branch::truncate();
        Branch::create(
            [
                'name'           => 'Example Branch',
                'email'          => 'example@branch.com',
                'password'       => bcrypt('12345'),
                'store_password' => '12345',
                'contact_number' => '018182882938',
                'district_id'    => 1,
                'upazila_id'     => 1,
                'area_id'        => 1,
                'date'           => date('Y-m-d'),
            ]
        );
    }
}
