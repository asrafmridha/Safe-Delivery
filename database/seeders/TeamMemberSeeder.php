<?php

namespace Database\Seeders;

use App\Models\TeamMember;
use Illuminate\Database\Seeder;

class TeamMemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TeamMember::truncate();
        TeamMember::create(
            [
                'name'              => 'Example Name',
                'designation_id'    => 1,
                'image'             => '15963474337DKfqm2d6hRtB3Y97522.jpg',
                'created_admin_id'  => 1,
            ]
        );
    }
}
