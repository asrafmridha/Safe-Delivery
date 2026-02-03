<?php

namespace Database\Seeders;

use App\Models\ServiceAreaSetting;
use Illuminate\Database\Seeder;

class ServiceAreaSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ServiceAreaSetting::truncate();
        ServiceAreaSetting::insert([
            [
                'service_area_id'  => 1,
                'created_admin_id' => 1,
            ],
        ]);
        $ServiceAreaSetting = ServiceAreaSetting::first();
        $ServiceAreaSetting->weight_packages()->sync([
            1 => [
                'service_area_setting_id' => 1,
                'service_area_id' => 2,
                'rate' => 10.20,
            ]
        ]);


    }
}
