<?php

namespace Database\Seeders;

use App\Models\District;
use Illuminate\Database\Seeder;

class DistrictSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        District::truncate();
        District::create(
            [
                'name'            => 'Dhaka',
                'created_admin_id' => 1,
            ]
        );
    }
}
