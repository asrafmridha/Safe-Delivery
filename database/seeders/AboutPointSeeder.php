<?php

namespace Database\Seeders;

use App\Models\AboutPoint;
use Illuminate\Database\Seeder;

class AboutPointSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        AboutPoint::truncate();
        AboutPoint::create(
            [
                'title'             => 'Example About Point Name',
                'details'          => 'Example About Point Short Details',
                'image'            => '15963474337DKfqm2d6hRtB3Y97522.jpg',
                'created_admin_id' => 1,
            ]
        );
    }
}
