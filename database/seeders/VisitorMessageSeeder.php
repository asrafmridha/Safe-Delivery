<?php

namespace Database\Seeders;

use App\Models\VisitorMessage;
use Illuminate\Database\Seeder;

class VisitorMessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        VisitorMessage::truncate();
        VisitorMessage::create(
            [
                'name'              => 'Visitor Name',
                'email'             => 'visitor@email.com',
                'subject'           => 'Visitor Email Subject',
                'message'           => 'Visitor Message',
                'updated_admin_id'  => 1,
            ]
        );
    }
}
