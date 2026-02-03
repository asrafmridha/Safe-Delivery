<?php

namespace Database\Seeders;

use App\Models\SocialLink;
use Illuminate\Database\Seeder;

class SocialLinkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SocialLink::truncate();
        SocialLink::create(
            [
                'name'              => 'Facebook',
                'url'               => "https://facebook.com",
                'icon'             => 'fab fa-facebook',
                'created_admin_id'  => 1,
            ]
        );
    }
}
