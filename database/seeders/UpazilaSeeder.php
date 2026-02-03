<?php

namespace Database\Seeders;

use App\Models\Upazila;
use Illuminate\Database\Seeder;

class UpazilaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Upazila::truncate();
        Upazila::create(
            [
                'name'             => 'Kalabagan',
                'district_id'      => 1,
                'updated_admin_id' => 1,
            ]
        );
    }
}
