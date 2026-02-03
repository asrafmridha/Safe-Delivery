<?php

namespace App\Imports;

use App\Models\Area;
use App\Models\User;
use App\Models\Parcel;
use App\Models\Upazila;
use App\Models\District;
use App\Models\ItemType;
use App\Models\Merchant;
use App\Models\RiderRun;
use App\Models\ParcelLog;
use App\Models\ServiceType;
use App\Models\WeightPackage;
use App\Models\RiderRunDetail;
use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;
use function PHPUnit\Framework\isNull;
use App\Models\MerchantServiceAreaCharge;
use Maatwebsite\Excel\Events\AfterImport;
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;

use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use App\Models\MerchantServiceAreaReturnCharge;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;

class MerchantBulkParcelImport implements
    ToCollection,
    WithHeadingRow,
    SkipsOnError,
    WithValidation,
    SkipsOnFailure,
    WithChunkReading,
    ShouldQueue,
    WithEvents
{
    use Importable, SkipsErrors, SkipsFailures, RegistersEventListeners;

    public function __construct()
    {
    }

    public function collection(Collection $rows)
    {
        if (count($rows)) {
            $merchant_id = auth()->guard('merchant')->user()->id;


            /*
            $currentDate    = date("Ymd");
            if(!empty($lastParcel)){
                $get_serial = explode("-", $lastParcel->parcel_invoice);
                $current_serials = $get_serial[1] +1;
                $parcel_invoice = $currentDate.'-'.str_pad($current_serials, 5, '0', STR_PAD_LEFT);
            }
            else{
                $parcel_invoice = $currentDate.'-00001';
            }
            */
            $currentDate = date("ymd");


            $parcel_count = 0;
            foreach ($rows as $row) {
                // dd($row);
                $merchant_order_id = isset($row['order_id']) ? $row['order_id'] : null;
                $customer_name = isset($row['name']) ? $row['name'] : null;
                $customer_contact_number = isset($row['phone']) ? $row['phone'] : null;
                $customer_address = isset($row['address']) ? $row['address'] : null;
                $area_name = isset($row['area']) ? $row['area'] : null;
                $product_details = isset($row['product_details']) ? $row['product_details'] : null;
                $weight = isset($row['weight']) ? $row['weight'] : null;
                // dd($weight);

                // $item = isset($row['item_type']) ? $row['item_type'] : null;
                // $service = isset($row['service_type']) ? $row['service_type'] : null;
                // dd($item,$service);

                $remark = isset($row['remark']) ? $row['remark'] : null;
                $collection_amount = isset($row['collection_amount']) ? $row['collection_amount'] : null;
                $merchant = Merchant::where('id', $merchant_id)->first();
                if ($merchant) {
                    if (
                        $merchant_id != null && $customer_name != null
                        && $customer_contact_number != null
                        && $customer_address != null && $area_name != null
                    ) {

                        /*if($parcel_count != 0){
                            $get_serial         = explode("-", $parcel_invoice);
                            $current_serials    = $get_serial[1] +1;
                            $parcel_invoice     = $currentDate.'-'.str_pad($current_serials, 5, '0', STR_PAD_LEFT);
                        }*/

                        $lastParcel = Parcel::orderBy('id', 'desc')->first();
                        $random_string = strtoupper(Controller::generateRandomString(3));
                        if (!empty($lastParcel)) {
                            $get_serial = substr($lastParcel->parcel_invoice, 9, 30);
                            $get_serial = strtoupper(base_convert(base_convert($get_serial, 36, 10) + 1, 10, 36));
                            $parcel_invoice = $currentDate . $random_string . str_pad($get_serial, 4, '0', STR_PAD_LEFT);
                        } else {
                            $parcel_invoice = $currentDate . $random_string . "001";
                        }
                        $parcel_count++;

                        // Set District, Upazila, Area ID and Merchant Service Area Charge
                        $merchant_service_area_charge = 0;
                        $merchant_service_area_return_charge = 0;
                        $weight_package_charge = 0;

                        // $item_type_charge = 0;
                        // $service_type_charge = 0;

                        $cod_percent = $merchant->cod_charge ?? 0;
                        $district_id = 0;
                        $upazila_id = 0;
                        $area_id = 0;
                        $service_area_id = 0;

                        $area = Area::with('district')->where('name', $area_name)->first();
                        //                        dd($area);
                        if ($area) {
                            $district_id = $area->district_id;
                            $upazila_id = $area->upazila_id;
                            $area_id = $area->id;
                            $service_area_id = $area->district->service_area_id;
                            if (is_null($cod_percent)) {
                                $cod_percent = $area->district->service_area->cod_charge;
                            }

                            $merchantServiceAreaCharge = MerchantServiceAreaCharge::where([
                                'service_area_id' => $service_area_id,
                                'merchant_id' => $merchant->id,
                            ])->first();

                            $merchantServiceAreaReturnCharge = MerchantServiceAreaReturnCharge::where([
                                'service_area_id' => $service_area_id,
                                'merchant_id' => $merchant->id,
                            ])->first();


                            if ($merchantServiceAreaCharge && !empty($merchantServiceAreaCharge->charge)) {
                                $merchant_service_area_charge = $merchantServiceAreaCharge->charge;
                            }

                            if ($merchantServiceAreaReturnCharge && !empty($merchantServiceAreaReturnCharge->return_charge)) {
                                $merchant_service_area_return_charge = $merchantServiceAreaReturnCharge->return_charge;
                            }
                        } else {
                            $merchant_service_area_charge = 60;
                        }
                        // Weight Package Charge
                        //                        dd($service_area_id);
                        $weightPackageId = null;
                        if ($weight) {

                            $weightPackage = WeightPackage::with([
                                'service_area' => function ($query) use ($service_area_id) {
                                    $query->where('service_area_id', '=', $service_area_id);
                                },
                            ])
                                ->where(['name' => $weight])
                                ->orWhere(['wp_id' => $weight])
                                ->first();
                            if ($weightPackage) {
                                $weightPackageId = $weightPackage->id;
                            }
                        }




                        // // Item Package Charge
                        // // dd($service_area_id);
                        // $itemTypeId = null;
                        // //  dd($item);
                        // if ($item) {

                        //     $itemType = ItemType::with([
                        //         // 'service_area' => function ($query) use ($service_area_id) {
                        //         //     $query->where('service_area_id', '=', $service_area_id);
                        //         // },
                        //     ])
                        //         ->where(['title' => $item])
                        //         //->orWhere(['id' => $item])
                        //         ->first();

                        //     // dd($itemType);


                        //     if ($itemType) {
                        //         $itemTypeId = $itemType->id;
                        //         // dd( $itemTypeId);
                        //     }
                        // }



                        //     // Service Package Charge
                        // $serviceTypeId = null;
                        // if ($service) {

                        //     $serviceType = ServiceType::with([

                        //         // 'service_area' => function ($query) use ($service_area_id) {
                        //         //     $query->where('service_area_id', '=', $service_area_id);
                        //         // },
                        //     ])
                        //         ->where(['title' => $service])
                            
                        //         ->first();
                        //     if ($serviceType) {
                        //         $serviceTypeId = $serviceType->id;
                        //     }
                        // }


                        // dd($itemTypeId,$serviceTypeId);

                        // Set Merchant Insert Parcel Calculation

                        $collection_amount = $collection_amount ?? 0;



                        $import_parcel = \session()->has('import_parcel') ? \session()->get('import_parcel') : [];

                        //  dd($import_parcel);


                        //insert data in session

                        //  dd($itemTypeId);
                        $data = [
                            'merchant_id' => $merchant->id,
                            'date' => date('Y-m-d'),
                            'merchant_order_id' => $merchant_order_id,
                            'customer_name' => $customer_name,
                            'customer_address' => $customer_address,
                            'customer_contact_number' => $customer_contact_number,
                            'product_details' => $product_details,
                            'district_id' => $district_id,
                            'area_id' => $area_id,
                            'weight_package_id' => $weightPackageId,

                            // 'item_type_id' => $itemTypeId,
                            // 'service_type_id' => $serviceTypeId,

                            'total_collect_amount' => $collection_amount ?? 0,
                            'parcel_note' => $remark,
                            'delivery_option_id' => 1,
                            'pickup_branch_id' => $merchant->branch_id,
                        ];

                        // dd($data);


                        $import_parcel[] = $data;
                        // $import_parcel_apu[] = $data;

                        // dd($import_parcel);


                        // dd($import_parcel);
                        \session(['import_parcel' => $import_parcel]);




                        /*$parcel = Parcel::create($data);

                        // Insert Parcel Log
                        $parcel_log = [
                            'parcel_id' => $parcel->id,
                            'merchant_id' => $merchant->id,
                            'pickup_branch_id' => $merchant->branch_id,
                            'date' => date('Y-m-d'),
                            'time' => date('H:i:s'),
                            'status' => 1,
                        ];
                        ParcelLog::create($parcel_log);*/
                    }
                }
            }
        }
    }

    public function rules(): array
    {
        return [];
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    public static function afterImport(AfterImport $event)
    {
    }

    public function onFailure(Failure ...$failure)
    {
    }
}
