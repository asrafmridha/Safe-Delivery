<?php

namespace Database\Seeders;

use App\Models\Feature;
use Illuminate\Database\Seeder;

class FeatureSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        Feature::truncate();
        Feature::create(
            [
                'title'            => 'Example Feature Title',
                'heading'          => 'Example Feature Heading',
                'details'          => 'Example Feature  Details',
                'image'            => '15963474337DKfqm2d6hRtB3Y97522.jpg',
                'created_admin_id' => 1,
            ]
        );
    }
}
