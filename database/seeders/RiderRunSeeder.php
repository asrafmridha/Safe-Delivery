<?php

namespace Database\Seeders;

use App\Models\RiderRun;
use Illuminate\Database\Seeder;

class RiderRunSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        RiderRun::truncate();
        RiderRun::create([
            'run_invoice'      => 'RUN-00001',
            'branch_id'        => 1,
            'rider_id'         => 1,
            'date'             => '2020-12-12',
            'time'             => '12:00:00',
            'total_run_parcel' => 1,
            'run_type'         => 1,
        ]);
    }
}
