<?php

namespace Database\Seeders;

use App\Models\Parcel;
use Illuminate\Database\Seeder;

class ParcelSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        Parcel::truncate();
        Parcel::create([
            'parcel_invoice'          => '10001',
            'merchant_id'             => 1,
            'date'                    => '2020-12-12',
            'customer_name'           => 'Customer Name',
            'customer_address'        => 'Customer Address',
            'customer_contact_number' => '01813158551',
            'product_details'         => 'Printer, Mobile',
            'customer_district_id'    => 1,
            'customer_upazila_id'     => 1,
            'customer_area_id'        => 1,
            'weight_package_id'       => 1,
            'delivery_charge'         => 10,
            'cod_percent'             => 1,
            'cod_charge'              => 10,
            'total_charge'            => 20,
            'delivery_option_id'      => 1,
            'pick_branch_id'          => 1,
            'parcel_date'             => '2020-12-12',
            'status'                  => 1,
        ]);
    }
}
