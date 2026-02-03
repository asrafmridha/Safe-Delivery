<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Admin::truncate();
        Admin::create(
            [
                'name'     => 'Admin',
                'email'    => 'admin@gmail.com',
                'password' => bcrypt('12345'),
                'type'     => 1,
                'photo'    => '15963474337DKfqm2d6hRtB3Y97522.png',
            ]
        );
    }
}
