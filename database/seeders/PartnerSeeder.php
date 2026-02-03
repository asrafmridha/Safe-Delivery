<?php

namespace Database\Seeders;

use App\Models\Partner;
use Illuminate\Database\Seeder;

class PartnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Partner::truncate();
        Partner::create(
            [
                'name'              => 'Example Name',
                'image'             => '15963474337DKfqm2d6hRtB3Y97522.jpg',
                'url'               => 'https://stitbd.com/',
                'created_admin_id'  => 1,
            ]
        );
    }
}
