<?php

namespace Database\Seeders;

use App\Models\Area;
use Illuminate\Database\Seeder;

class AreaSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        Area::truncate();
        Area::create(
            [
                'name'             => 'PanthaPath',
                'post_code'        => '1001',
                'upazila_id'       => 1,
                'district_id'      => 1,
                'updated_admin_id' => 1,
            ]
        );
    }
}
