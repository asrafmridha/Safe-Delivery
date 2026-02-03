<?php

namespace Database\Seeders;

use App\Models\ServiceArea;
use Illuminate\Database\Seeder;

class ServiceAreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ServiceArea::truncate();
        ServiceArea::create(
            [
                'name'              => 'Example Service Area',
                'details'           => 'Example Service Area Details',
                'created_admin_id'  => 1,
            ]
        );
    }
}
