<?php

namespace Database\Seeders;

use App\Models\NewsLetter;
use Illuminate\Database\Seeder;

class NewsLetterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        NewsLetter::truncate();
        NewsLetter::create(
            [
                'email'             => 'visitor@email.com',
                'updated_admin_id'  => 1,
            ]
        );
    }
}
