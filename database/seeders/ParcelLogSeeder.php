<?php

namespace Database\Seeders;

use App\Models\ParcelLog;
use Illuminate\Database\Seeder;

class ParcelLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ParcelLog::truncate();
        ParcelLog::create([
                'parcel_id'               => 1,
                'merchant_id'             => 1,
                'date'                    => '2020-12-12',
                'time'                    => '12:00:00',
                'status'                  => 1,
        ]);
    }
}
