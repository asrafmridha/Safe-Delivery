<?php

namespace Database\Seeders;

use App\Models\RiderRunDetail;
use Illuminate\Database\Seeder;

class RiderRunDetailSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        RiderRunDetail::truncate();
        RiderRunDetail::create([
            'parcel_run_id' => 1,
            'parcel_id'     => 1,
            'run_type'      => 1,
            'run_type'      => 1,
        ]);
    }
}
