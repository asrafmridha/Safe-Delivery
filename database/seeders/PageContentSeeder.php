<?php

namespace Database\Seeders;

use App\Models\PageContent;
use Illuminate\Database\Seeder;

class PageContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PageContent::truncate();
        PageContent::create(
            [
                'page_type'         => 1,
                'short_details'    => 'Example Parcel Step Short Details',
                'long_details'     => 'Example Parcel Step Long Details',
                'image'            => '15963474337DKfqm2d6hRtB3Y97522.jpg',
                'created_admin_id' => 1,
            ]
        );
    }
}
