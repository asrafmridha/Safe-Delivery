<?php

namespace Database\Seeders;

use App\Models\WeightPackage;
use Illuminate\Database\Seeder;

class WeightPackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        WeightPackage::truncate();
        WeightPackage::create(
            [
                'name'              => 'Example Weight Package',
                'details'           => 'Example Weight Package Details',
                'rate'              => 20.50,
                'created_admin_id'  => 1,
            ]
        );
    }
}
