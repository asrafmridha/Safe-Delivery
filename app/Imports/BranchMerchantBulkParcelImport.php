<?php

namespace App\Imports;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\District;
use App\Models\Merchant;
use App\Models\MerchantServiceAreaCharge;
use App\Models\MerchantServiceAreaReturnCharge;
use App\Models\Parcel;
use App\Models\Upazila;
use App\Models\User;
use App\Models\WeightPackage;
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

class BranchMerchantBulkParcelImport implements
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

    protected $rider_id = 0;
    protected $date = '';
    protected $note = null;

    public function __construct($rider_id,$date,$note)
    {
        $this->rider_id = $rider_id;
        $this->date = $date;
        $this->note = $note;
    }

    public function collection(Collection $rows)
    {
        $rider_id = $this->rider_id;
        $date = $this->date;
        $note = $this->note;

        if (count($rows)) {
            $branch_user_id = auth()->guard('branch')->user()->id;
            $branch_id = auth()->guard('branch')->user()->branch->id;
            $lastParcel = Parcel::orderBy('id', 'desc')->first();

            $currentDate = date("ymd");

            if (!empty($lastParcel)) {
                $get_serial = substr($lastParcel->parcel_invoice, 9, 30);
                $random_string = strtoupper(Controller::generateRandomString(3));
                $get_serial = strtoupper(base_convert(base_convert($get_serial, 36, 10) + 1, 10, 36));
                $parcel_invoice = $currentDate . $random_string . str_pad($get_serial, 4, '0', STR_PAD_LEFT);
            } else {
                $parcel_invoice = $currentDate . 'ANZ0001';
            }

            $parcel_count = 0;
            $parcel_data=[];

            foreach ($rows as $row) {

                $merchant_id = isset($row['merchant_id']) ? trim($row['merchant_id']) : null;
                $merchant_order_id = isset($row['order_id']) ? trim($row['order_id']) : null;
                $customer_name = isset($row['name']) ? trim($row['name']) : null;
                $customer_contact_number = isset($row['phone']) ? trim($row['phone']) : null;
                $customer_address = isset($row['address']) ? trim($row['address']) : null;
                $area_name = isset($row['area']) ? trim($row['area']) : null;
                $product_details = isset($row['product_details']) ? trim($row['product_details']) : null;
                $weight = isset($row['weight']) ? trim($row['weight']) : null;
                $remark = isset($row['remark']) ? trim($row['remark']) : null;
                $collection_amount = isset($row['collection_amount']) ? floatval($row['collection_amount']) : null;

                $merchant = Merchant::where('m_id', $merchant_id)->first();

                if ($merchant) {

                    if ($merchant_id != null && $customer_name != null
                        && $customer_contact_number != null
                        && $customer_address != null && $area_name != null) {

                        if ($parcel_count != 0) {
                            $get_serial = substr($parcel_invoice, 9, 30);
                            $random_string = strtoupper(Controller::generateRandomString(3));
                            $get_serial = strtoupper(base_convert(base_convert($get_serial, 36, 10) + 1, 10, 36));
                            $parcel_invoice = $currentDate . $random_string . str_pad($get_serial, 4, '0', STR_PAD_LEFT);

                        }

                        $parcel_count++;

                        // Set District, Upazila, Area ID and Merchant Service Area Charge
                        $merchant_service_area_charge = 0;
                        $merchant_service_area_return_charge = 0;
                        $weight_package_charge = 0;
                        $cod_percent = $merchant->cod_charge;
                        $district_id = 0;
                        $upazila_id = 0;
                        $area_id = 0;
                        $service_area_id = 0;

                        $area = Area::with('upazila')->where('name', $area_name)->first();

                        if ($area) {
                            $district_id = $area->district_id;
                            $upazila_id = $area->upazila_id;
                            $area_id = $area->id;
                            $service_area_id = $area->upazila->district->service_area_id;

                            if (is_null($cod_percent)) {
                                $cod_percent = $area->upazila->district->service_area->cod_charge;
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

                        }

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

                        $collection_amount = $collection_amount ?? 0;
                        $import_parcel = \session()->has('import_parcel') ? \session()->get('import_parcel') : [];
                        $merchant = Merchant::where('id', $merchant->id)->first();

                        $data = [
                            'm_id' => $merchant->m_id,
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
                            'total_collect_amount' => $collection_amount ?? 0,
                            'parcel_note' => $remark,
                            'delivery_option_id' => 1,
                            'pickup_branch_id' => $merchant->branch_id,
                            'rider_id' => $rider_id,
                        ];

                        $parcel_data[] = $data;
                    }

                }

            }

            $import_parcel['parcel']=$parcel_data;
            $import_parcel['rider_id'] = $rider_id;
            $import_parcel['date'] = $date;
            $import_parcel['note'] = $note;
            \session(['import_parcel' => $import_parcel]);

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

    public function onFailure(Failure...$failure)
    {
    }

}
