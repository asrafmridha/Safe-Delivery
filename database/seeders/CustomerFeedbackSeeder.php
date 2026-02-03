<?php

namespace Database\Seeders;

use App\Models\CustomerFeedback;
use Illuminate\Database\Seeder;

class CustomerFeedbackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CustomerFeedback::truncate();
        CustomerFeedback::create(
            [
                'name'              => 'Example Customer Name',
                'company'           => 'Example Customer Company',
                'feedback'          => 'Example Customer Company',
                'image'             => '15963474337DKfqm2d6hRtB3Y97522.jpg',
                'created_admin_id'  => 1,
            ]
        );
    }
}
