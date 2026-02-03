<?php

namespace Database\Seeders;

use App\Models\office;
use Illuminate\Database\Seeder;

class OfficeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Office::truncate();
        Office::create(
            [
                'name'              => 'Dhaka Office',
                'contact_number'    => 'Dhaka',
                'email'             => 'dhaka@email.com',
                'address'           => 'Dhaka',
                'created_admin_id'  => 1,
            ]
        );
    }
}
