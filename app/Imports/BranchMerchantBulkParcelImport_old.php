<?php

namespace App\Imports;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\District;
use App\Models\Upazila;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Events\AfterImport;
use Maatwebsite\Excel\Validators\Failure;
use App\Models\RiderRunDetail;
use App\Models\Parcel;
use App\Models\Merchant;
use App\Models\WeightPackage;
use App\Models\MerchantServiceAreaCharge;
use App\Models\ParcelLog;
use App\Models\RiderRun;

use function PHPUnit\Framework\isNull;
use App\Models\MerchantServiceAreaReturnCharge;

class BranchMerchantBulkParcelImport implements
ToCollection,
WithHeadingRow,
SkipsOnError,
WithValidation,
SkipsOnFailure,
WithChunkReading,
ShouldQueue,
WithEvents {
    use Importable, SkipsErrors, SkipsFailures, RegistersEventListeners;

    protected $rider_run_id = null;
    protected $rider_id = 0;

    public function __construct($rider_run_id, $rider_id) {
        $this->rider_run_id  = $rider_run_id;
        $this->rider_id      = $rider_id;
    }

    public function collection(Collection $rows) {
        // dd($rows);

        $rider_run_id   = $this->rider_run_id;
        $rider_id       = $this->rider_id;
        if(count($rows)){
            $branch_user_id = auth()->guard('branch')->user()->id;
            $branch_id      = auth()->guard('branch')->user()->branch->id;

            $lastParcel     = Parcel::orderBy('id', 'desc')->first();

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
            $currentDate    = date("ymd");
            if(!empty($lastParcel)){
                $get_serial         = substr($lastParcel->parcel_invoice,9,30);
                $random_string      = strtoupper(Controller::generateRandomString(3));
                $get_serial         = strtoupper(base_convert(base_convert($get_serial,36,10)+1,10,36));
                $parcel_invoice = $currentDate.$random_string.str_pad($get_serial, 4, '0', STR_PAD_LEFT);
            }
            else{
                $parcel_invoice = $currentDate.'ANZ0001';
            }


            $parcel_count = 0;


            foreach ($rows as $row) {
               
                
                $merchant_id                = isset($row['merchant_id'])? trim($row['merchant_id']) : null;
                $merchant_order_id          = isset($row['order_id'])? trim($row['order_id']) : null;
                $customer_name              = isset($row['name'])? trim($row['name']) : null;
                $customer_contact_number    = isset($row['phone'])? trim($row['phone']) : null;
                $customer_address           = isset($row['address'])? trim($row['address']) : null;
                $area_name                  = isset($row['area'])? trim($row['area']) : null;
                $product_details            = isset($row['product_details'])? trim($row['product_details']) : null;
                $weight                     = isset($row['weight'])? trim($row['weight']) : null;
                $remark                     = isset($row['remark'])? trim($row['remark']) : null;
                $collection_amount          = isset($row['collection_amount'])? floatval($row['collection_amount']) : null;

                $merchant = Merchant::where('m_id', $merchant_id)->first();
               
                if($merchant){
                     
                    if ($merchant_id != null && $customer_name != null
                        && $customer_contact_number != null 
                        && $customer_address != null && $area_name != null) {
                            

                        if($parcel_count != 0){ 
                            $get_serial         = substr($parcel_invoice,9,30);
                            $random_string      = strtoupper(Controller::generateRandomString(3));
                            $get_serial         = strtoupper(base_convert(base_convert($get_serial,36,10)+1,10,36));
                            $parcel_invoice     = $currentDate.$random_string.str_pad($get_serial, 4, '0', STR_PAD_LEFT);
                         
                        }
                        $parcel_count++;
                        
                        

                        // Set District, Upazila, Area ID and Merchant Service Area Charge
                        $merchant_service_area_charge = 0;
                        $merchant_service_area_return_charge = 0;
                        $weight_package_charge  = 0;
                        $cod_percent            = $merchant->cod_charge;
                        $district_id            = 0;
                        $upazila_id             = 0;
                        $area_id                = 0;
                        $service_area_id        = 0;

                        $area = Area::with('upazila')->where('name', $area_name)->first();
                        if($area){
                            $district_id        = $area->district_id;
                            $upazila_id         = $area->upazila_id;
                            $area_id            = $area->id;
                            $service_area_id    = $area->upazila->district->service_area_id;
                            if(is_null($cod_percent)){
                                $cod_percent     = $area->upazila->district->service_area->cod_charge;
                            }

                            $merchantServiceAreaCharge = MerchantServiceAreaCharge::where([
                                'service_area_id'   =>  $service_area_id,
                                'merchant_id'       =>  $merchant->id,
                            ])->first();

                            $merchantServiceAreaReturnCharge = MerchantServiceAreaReturnCharge::where([
                                'service_area_id'   =>  $service_area_id,
                                'merchant_id'       =>  $merchant->id,
                            ])->first();


                            if ($merchantServiceAreaCharge && !empty($merchantServiceAreaCharge->charge)) {
                                $merchant_service_area_charge = $merchantServiceAreaCharge->charge;
                            }

                            if ($merchantServiceAreaReturnCharge && !empty($merchantServiceAreaReturnCharge->return_charge)) {
                                $merchant_service_area_return_charge = $merchantServiceAreaReturnCharge->return_charge;
                            }
                        }

                        // Weight Package Charge
                        if($weight){
                            $weightPackage = WeightPackage::where('name', $weight)->first();

                            $weightPackage  = WeightPackage::with([
                                'service_area' => function ($query) use ($service_area_id) {
                                    $query->where('service_area_id', '=', $service_area_id);
                                },
                            ])
                            ->where(['name' => $weight])
                            ->orWhere(['wp_id' => $weight])
                            ->first();


                            $weight_package_charge = $weightPackage->rate;
                            if (!empty($weightPackage->service_area)) {
                                $weight_package_charge = $weightPackage->service_area->rate;
                            }
                        }

                        if(empty($weightPackage) || isNull($weight)){
                            $weightPackage  = WeightPackage::with([
                                'service_area' => function ($query) use ($service_area_id) {
                                    $query->where('service_area_id', '=', $service_area_id);
                                },
                            ])
                            ->where(['status' => 1])
                            ->first();

                            $weight_package_charge = $weightPackage->rate;
                            if (!empty($weightPackage->service_area)) {
                                $weight_package_charge = $weightPackage->service_area->rate;
                            }
                        }


                        // Set Merchant Insert Parcel Calculation
                        $delivery_charge    = $merchant_service_area_charge;

                        $cod_charge         = 0;
                        $collection_amount  = $collection_amount ?? 0;
                        if($collection_amount != 0 && $cod_percent != 0){
                            $cod_charge = ($collection_amount/100) * $cod_percent;
                        }
                        $total_charge    = $delivery_charge + $cod_charge + $weight_package_charge;

                        // Insert Parcel
                        $data = [
                            'parcel_invoice'               => $parcel_invoice,
                            'merchant_id'                  => $merchant->id,
                            'date'                         => date('Y-m-d'),
                            'merchant_order_id'            => $merchant_order_id,
                            'customer_name'                => $customer_name,
                            
                            'customer_contact_number'      => $customer_contact_number,
                            'product_details'              => $product_details,
                            'district_id'                  => $district_id,
                            'upazila_id'                   => $upazila_id,
                            'area_id'                      => $area_id,
                            'weight_package_id'            => $weightPackage->id,
                            'delivery_charge'              => $delivery_charge  ?? 0,
                            'weight_package_charge'        => $weightPackage->rate,
                            'merchant_service_area_charge' => $merchant_service_area_charge  ?? 0,
                            'merchant_service_area_return_charge' => $merchant_service_area_return_charge  ?? 0,
                            'total_collect_amount'         => $collection_amount ?? 0,
                            'cod_percent'                  => $cod_percent  ?? 0,
                            'cod_charge'                   => $cod_charge  ?? 0,
                            'total_charge'                 => $total_charge  ?? 0,
                            'parcel_note'                  => $remark,
                            'delivery_option_id'           => 1,
                            'pickup_branch_id'             => $branch_id,
                            'pickup_branch_date'           => date('Y-m-d'),
                            'pickup_rider_id'              => $rider_id,
                            'pickup_rider_date'            => date('Y-m-d'),
                            'pickup_branch_user_id'        => $branch_user_id,
                            'parcel_date'                  => date('Y-m-d'),
                            'status'                       => 10,
                            'customer_address'             => $customer_address,
                        ];
                         
                         
                        $parcel = Parcel::create($data);

                        // Insert Parcel Log
                        $parcel_log = [
                            'parcel_id'        => $parcel->id,
                            'pickup_rider_id'  => $rider_id,
                            'pickup_branch_id'              => $branch_id,
                            'pickup_branch_user_id'         => $branch_user_id,
                            'date'             => date('Y-m-d'),
                            'time'             => date('H:i:s'),
                            'status'           => 10,
                            'delivery_type' => $parcel->delivery_type,
                        ];
                        ParcelLog::create($parcel_log);

                        // Insert Rider Details
                        $rider_run_details = [
                            'rider_run_id'      => $rider_run_id,
                            'parcel_id'         => $parcel->id,
                            'complete_date_time'=>  date('Y-m-d H:i:s'),
                            'status'            => 7,
                        ];
                        RiderRunDetail::create([
                            'rider_run_id'      => $rider_run_id,
                            'parcel_id'         => $parcel->id,
                            'complete_date_time'=>  date('Y-m-d H:i:s'),
                            'status'            => 7,
                        ]);
                    }

                    //$parcel = Parcel::where('id', $parcel_id)->first();
                    // $controllerObj   = new Controller();
                    // $controllerObj->merchantDashboardCounterEvent($merchant_id);
                }
            }

            RiderRun::where('id', $rider_run_id)->update([
                'total_run_parcel'          => $parcel_count,
                'total_run_complete_parcel' => $parcel_count,
            ]);
        }
    }

    public function rules(): array{
        return [];
    }

    public function chunkSize(): int {
        return 1000;
    }

    public static function afterImport(AfterImport $event) {
    }

    public function onFailure(Failure...$failure) {
    } 

}
