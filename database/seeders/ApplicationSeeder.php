<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Application;

class ApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Application::truncate();
        Application::create([
            'name'           => 'Mettro Express',
            'email'          => 'mettro.express@gmail.com',
            'contact_number' => '01713636473',
            'address'        => 'Dhaka',
            'photo'          => '1596912424CM7ItJztFMvv3gN82536.png',
            'admin_id'       => 1,
        ]);
    }
}
