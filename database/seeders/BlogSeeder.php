<?php

namespace Database\Seeders;

use App\Models\Blog;
use Illuminate\Database\Seeder;

class BlogSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        Blog::truncate();
        Blog::create(
            [
                'title'            => 'Example Blog Name',
                'title'            => str_slug('Example Blog Name'),
                'short_details'    => 'Example Blog Short Details',
                'long_details'     => 'Example Blog Long Details',
                'image'            => '15963474337DKfqm2d6hRtB3Y97522.jpg',
                'date'             => '2020/11/20',
                'created_admin_id' => 1,
            ]
        );
    }
}
