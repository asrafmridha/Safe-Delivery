<?php

namespace Database\Seeders;

use App\Models\ParcelStep;
use Illuminate\Database\Seeder;

class ParcelStepSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        ParcelStep::truncate();
        ParcelStep::create(
            [
                'title'             => 'Example Parcel Step Title',
                'short_details'    => 'Example Parcel Step Short Details',
                'long_details'     => 'Example Parcel Step Long Details',
                'image'            => '15963474337DKfqm2d6hRtB3Y97522.jpg',
                'created_admin_id' => 1,
            ]
        );
    }
}
