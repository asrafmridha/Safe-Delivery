<?php

namespace Database\Seeders;

use App\Models\Slider;
use Illuminate\Database\Seeder;

class SliderSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        Slider::truncate();
        Slider::create(
            [
                'title'            => 'Demo',
                'details'          => 'Demo Details',
                'image'            => '15963474337DKfqm2d6hRtB3Y97522.jpg',
                'created_admin_id' => 1,
            ]
        );
    }
}
