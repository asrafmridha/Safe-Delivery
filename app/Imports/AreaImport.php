<?php

namespace App\Imports;

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

class AreaImport implements
ToCollection,
WithHeadingRow,
SkipsOnError,
WithValidation,
SkipsOnFailure,
WithChunkReading,
ShouldQueue,
WithEvents {
    use Importable, SkipsErrors, SkipsFailures, RegistersEventListeners;

    public function collection(Collection $rows) {

        foreach ($rows as $row) {
            // dd($row);


            $admin          = auth()->guard('admin')->user()->id;
            $district_name  = trim($row['district']);
            // $upazila_name   = trim($row['thana']);
            $area_name      = trim($row['area']);
            $post_code      = trim($row['post_code']);
            $post_code      = !empty($post_code) ? $post_code : '1200';




            if ($district_name != null  && $area_name != null && $post_code != null) {
            // if ($district_name != null && $upazila_name != null && $area_name != null && $post_code != null) {

                $district = District::where(['name' => $district_name])->first();
                if(!$district){
                    $district =  District::create([
                        'name'             => $district_name,
                        'service_area_id'  => 1,
                        'created_admin_id' => $admin,
                    ]);
                }



                // $upazila = Upazila::where(['name' => $upazila_name])->first();
                // if(!$upazila){
                //     $upazila =  Upazila::create([
                //         'name'             => $upazila_name,
                //         'district_id'      => $district->id,
                //         'created_admin_id' => $admin,
                //     ]);
                // }


                $area = Area::where(['name' => $area_name])->first();
                if(!$area){
                    $area =  Area::create([
                        'name'             => $area_name,
                        'post_code'        => $post_code,
                        // 'upazila_id'       => $district->id,
                        'upazila_id'      => 0,
                        'district_id'      => $district->id,
                        'created_admin_id' => $admin,
                    ]);
                }

            }

        }

    }

    public function rules(): array
    {
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
