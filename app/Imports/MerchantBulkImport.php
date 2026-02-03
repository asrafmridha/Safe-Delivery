<?php

namespace App\Imports;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Branch;
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

class MerchantBulkImport implements
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
            $import_merchant = [];
            $m_id = null;
            foreach ($rows as $row) {
                $lastMerchant = Merchant::orderBy('id', 'desc')->first();

                if ($m_id) {
                    $get_serial = explode("-", $m_id);
                    $current_serials = $get_serial[1] + 1;
                    $m_id = 'M-' . str_pad($current_serials, 4, '0', STR_PAD_LEFT);
                } else if (!empty($lastMerchant)) {
                    $get_serial = explode("-", $lastMerchant->m_id);
                    $current_serials = $get_serial[1] + 1;
                    $m_id = 'M-' . str_pad($current_serials, 4, '0', STR_PAD_LEFT);
                } else {
                    $m_id = 'M-0001';
                }

                $district = District::where("name", trim($row['district_name']))->first();
                $area = Area::where("name", trim($row['area_name']))->first();
                $branch = Branch::where("name", trim($row['branch_name']))->first();

                $data = [
                    'm_id' => $m_id,
                    'name' => $row['name'],
                    'email' => $row['email'],
                    "password" => $row['password'],
                    "company_name" => $row['companyname'],
                    "address" => $row['address'],
                    "business_address" => $row['business_address'],
                    "fb_url" => $row['fb_url'],
                    "web_url" => $row['web_url'],
                    "contact_number" => $row['contact_number'],
                    "district_id" => $district ? $district->id : 0,
                    "area_id" => $area ? $area->id : 0,
                    "branch_id" => $branch ? $branch->id : 0,
                ];

                $import_merchant[] = $data;
            }

            \session(['import_merchant' => $import_merchant]);
        }

    }


    public
    function rules(): array
    {
        return [];
    }

    public
    function chunkSize(): int
    {
        return 1000;
    }

    public
    static function afterImport(AfterImport $event)
    {
    }

    public
    function onFailure(Failure...$failure)
    {
    }

}
