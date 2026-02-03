<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ItemType;
use App\Models\Merchant;
use App\Models\Area;
use App\Models\Branch;
use App\Models\District;
use App\Models\MerchantServiceAreaCharge;
use App\Models\Rider;
use App\Models\Parcel;

use App\Models\ServiceType;
use App\Models\ServiceArea;
use App\Models\Upazila;
use App\Models\WeightPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use function PHPUnit\Framework\isNull;
use App\Models\MerchantServiceAreaReturnCharge;
use App\Models\MerchantServiceAreaCodCharge;

class HomeController extends Controller {

    public function getDistricts(Request $request) {
        $districts = District::where('status', 1)
            ->select('id', 'name', 'service_area_id', 'home_delivery', 'lock_down_service', 'status')
            ->get();

        if ($districts) {
            return response()->json([
                'success'   => 200,
                'message'   => "Districts information found.",
                'districts' => $districts,
            ], 200);
        }

        return response()->json([
            'success' => 401,
            'message' => "Districts not found.",
            'error'   => "Districts not found.",
        ], 401);
    }

    public function getDistrict(Request $request) {

        $validator = Validator::make($request->all(), [
            'district_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 401,
                'message' => "Validation Error.",
                'error'   => $validator->errors(),
            ], 401);
        }

        $district = District::where([
            ['id', '=', $request->district_id],
            ['status', '=', 1],
        ])
            ->select('id', 'name', 'service_area_id', 'home_delivery', 'lock_down_service', 'status')
            ->first();

        if ($district) {
            return response()->json([
                'success'   => 200,
                'message'   => "District information found.",
                'districts' => $district,
            ], 200);
        }

        return response()->json([
            'success' => 401,
            'message' => "District not found.",
            'error'   => "District not found.",
        ], 401);
    }

    public function getUpazilas(Request $request) {
        $validator = Validator::make($request->all(), [
            'district_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 401,
                'message' => "Validation Error.",
                'error'   => $validator->errors(),
            ], 401);
        }

        $upazilas = Upazila::select('id', 'name')
            ->where([
                ['district_id', '=', $request->district_id],
                ['status', '=', 1],
            ])
            ->select('id', 'name', 'district_id', 'status')
            ->get();

        if ($upazilas->count() > 0) {
            return response()->json([
                'success'  => 200,
                'message'  => "Upazilas information found.",
                'upazilas' => $upazilas,
            ], 200);
        }

        return response()->json([
            'success' => 401,
            'message' => "Upazilas not found.",
            'error'   => "Upazilas not found.",
        ], 401);
    }

    public function getUpazila(Request $request) {

        $validator = Validator::make($request->all(), [
            'upazila_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 401,
                'message' => "Validation Error.",
                'error'   => $validator->errors(),
            ], 401);
        }

        $upazila = Upazila::where([
            ['id', '=', $request->upazila_id],
            ['status', '=', 1],
        ])
            ->select('id', 'name', 'district_id', 'status')
            ->first();

        if ($upazila) {
            return response()->json([
                'success' => 200,
                'message' => "Upazila information found.",
                'upazila' => $upazila,
            ], 200);
        }

        return response()->json([
            'success' => 401,
            'message' => "Upazila not found.",
            'error'   => "Upazila not found.",
        ], 401);
    }

    public function getServiceAreas(Request $request) {

        // $areas = Area::join('upazilas', 'upazilas.id', '=', 'areas.upazila_id')
        //                 ->join('districts', 'districts.id', '=', 'upazilas.district_id')
        //                 ->join('service_areas', 'service_areas.id', '=', 'districts.service_area_id')
        //                 ->select(
        //                     'areas.id',
        //                     'areas.name as coverage_area',
        //                     'areas.post_code',
        //                     'upazilas.name as upazila_name',
        //                     'districts.name as district_name',
        //                     'service_areas.name as service_area_name',
        //                     'service_areas.cod_charge as cod_charge'
        //                 )
        //                 ->get();

        $areas = Area::join('districts', 'districts.id', '=', 'areas.district_id')
                        ->join('service_areas', 'service_areas.id', '=', 'districts.service_area_id')
                        ->select(
                            'areas.id',
                            'areas.name as coverage_area',
                            'areas.post_code',
                            'districts.name as district_name',
                            'service_areas.name as service_area_name',
                            'service_areas.cod_charge as cod_charge'
                        )
                        ->get();
                        // ->paginate(10);


        if ($areas->count() > 0) {
            return response()->json([
                'success' => 200,
                'message' => "Areas information found.",
                'coverage_areas'   => $areas,
            ], 200);
        }

        return response()->json([
            'success' => 401,
            'message' => "Areas not found.",
            'error'   => "Areas not found.",
        ], 401);
    }

    public function getAreas(Request $request) {

        $validator = Validator::make($request->all(), [
            'district_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 401,
                'message' => "Validation Error.",
                'error'   => $validator->errors(),
            ], 401);
        }

        $areas = Area::select('id', 'name')
            ->where([
                ['district_id', '=', $request->district_id],
                ['status', '=', 1],
            ])
            ->select('id', 'name', 'post_code', 'district_id', 'upazila_id', 'status')
            ->get();

        if ($areas->count() > 0) {
            return response()->json([
                'success' => 200,
                'message' => "Areas information found.",
                'areas'   => $areas,
            ], 200);
        }

        return response()->json([
            'success' => 401,
            'message' => "Areas not found.",
            'error'   => "Areas not found.",
        ], 401);
    }

    public function getArea(Request $request) {
        $validator = Validator::make($request->all(), [
            'area_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 401,
                'message' => "Validation Error.",
                'error'   => $validator->errors(),
            ], 401);
        }

        $area = Area::where([
            ['id', '=', $request->area_id],
            ['status', '=', 1],
        ])
            ->select('id', 'name', 'post_code', 'district_id', 'upazila_id', 'status')
            ->first();

        if ($area) {
            return response()->json([
                'success' => 200,
                'message' => "Area information found.",
                'area'    => $area,
            ], 200);
        }

        return response()->json([
            'success' => 401,
            'message' => "Area not found.",
            'error'   => "Area not found.",
        ], 401);
    }

    public function getBranches(Request $request) {
        $branches = Branch::where('status', 1)
            ->select('id', 'name', 'email', 'image', 'address',
                'contact_number', 'district_id', 'upazila_id', 'area_id', 'status')
            ->get();

        if ($branches->count() > 0) {
            return response()->json([
                'success'  => 200,
                'message'  => "Branches information found.",
                'branches' => $branches,
            ], 200);
        }

        return response()->json([
            'success' => 401,
            'message' => "Branches not found.",
            'error'   => "Branches not found.",
        ], 401);
    }

    public function getBranch(Request $request) {

        $validator = Validator::make($request->all(), [
            'branch_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 401,
                'message' => "Validation Error.",
                'error'   => $validator->errors(),
            ], 401);
        }

        $branch = Branch::where([
            ['id', '=', $request->branch_id],
            ['status', '=', 1],
        ])
            ->select('id', 'name', 'email', 'image', 'address',
                'contact_number', 'district_id', 'upazila_id', 'area_id', 'status')
            ->first();

        if ($branch) {
            return response()->json([
                'success' => 200,
                'message' => "Branch information found.",
                'branch'  => $branch,
            ], 200);
        }

        return response()->json([
            'success' => 401,
            'message' => "Branch not found.",
            'error'   => "Branch not found.",
        ], 401);
    }

    public function getRiders(Request $request) {

        $validator = Validator::make($request->all(), [
            'branch_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 401,
                'message' => "Validation Error.",
                'error'   => $validator->errors(),
            ], 401);
        }

        $riders = Rider::where([
            ['branch_id', '=', $request->branch_id],
            ['status', '=', 1],
        ])
            ->select('id', 'name', 'email', 'image', 'address',
                'contact_number', 'branch_id', 'district_id', 'upazila_id', 'area_id', 'status')
            ->get();

        if ($riders->count() > 0) {
            return response()->json([
                'success' => 200,
                'message' => "Riders information found.",
                'riders'  => $riders,
            ], 200);
        }

        return response()->json([
            'success' => 401,
            'message' => "Riders not found.",
            'error'   => "Riders not found.",
        ], 401);
    }

    public function getRider(Request $request) {

        $validator = Validator::make($request->all(), [
            'rider_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 401,
                'message' => "Validation Error.",
                'error'   => $validator->errors(),
            ], 401);
        }

        $rider = Rider::where([
            ['id', '=', $request->rider_id],
            ['status', '=', 1],
        ])
            ->select('id', 'name', 'email', 'image', 'address',
                'contact_number', 'branch_id', 'district_id', 'upazila_id', 'area_id', 'status')
            ->first();

        if ($rider) {
            return response()->json([
                'success' => 200,
                'message' => "Rider information found.",
                'rider'   => $rider,
            ], 200);
        }

        return response()->json([
            'success' => 401,
            'message' => "Rider not found.",
            'error'   => "Rider not found.",
        ], 401);
    }

    public function getWeightPackages(Request $request) {

        $weightPackages = WeightPackage::where([
            ['status', '=', 1],
        ])
            ->select('id', 'name', 'title', 'weight_type', 'details',
                'rate', 'status')
            ->get();

        if ($weightPackages->count() > 0) {
            return response()->json([
                'success'        => 200,
                'message'        => "Weight Packages information found.",
                'weightPackages' => $weightPackages,
            ], 200);
        }

        return response()->json([
            'success' => 401,
            'message' => "Weight Packages not found.",
            'error'   => "Weight Packages not found.",
        ], 401);
    }



    public function getWeightPackage(Request $request) {

        $validator = Validator::make($request->all(), [
            'weight_package_id' => 'required',
            'district_id'       => 'sometimes',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 401,
                'message' => "Validation Error.",
                'error'   => $validator->errors(),
            ], 401);
        }

        $weightPackage = WeightPackage::where([
            ['id', '=', $request->weight_package_id],
            ['status', '=', 1],
        ])
            ->select('id', 'name', 'title', 'weight_type', 'details',
                'rate', 'status')
            ->first();

        if ($weightPackage) {
            return response()->json([
                'success'       => 200,
                'message'       => "Weight Package information found.",
                'weightPackage' => $weightPackage,
            ], 200);
        }

        return response()->json([
            'success' => 401,
            'message' => "Weight Package not found.",
            'error'   => "Weight Package not found.",
        ], 401);
    }

    public function getDistrictWeightPackageRate(Request $request) {

        $validator = Validator::make($request->all(), [
            'district_id'       => 'required',
            'weight_package_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 401,
                'message' => "Validation Error.",
                'error'   => $validator->errors(),
            ], 401);
        }

        $district = District::where('id', $request->district_id)->first();

        if (!empty($district)) {
            $service_area_id = $district->service_area_id;
            $weightPackage   = WeightPackage::with([
                'service_area' => function ($query) use ($service_area_id) {
                    $query->where('service_area_id', '=', $service_area_id);
                },
            ])
                ->where([
                    ['status', '=', 1],
                    ['id', '=', $request->weight_package_id],
                ])
                ->first();

            if ($weightPackage) {
                $rate = $weightPackage->rate;

                if (!empty($weightPackage->service_area)) {
                    $rate = $weightPackage->service_area->rate;
                }

                return response()->json([
                    'success' => 200,
                    'message' => "District Weight Package Rate found.",
                    'weight_package_name'    => $weightPackage->name,
                    'weight_package_title'    => $weightPackage->title,
                    'rate'    => $rate,
                ], 200);
            }

        }

        return response()->json([
            'success' => 401,
            'message' => "District Weight Package Rate not found.",
            'error'   => "District Weight Package Rate not found.",
        ], 401);
    }





    public function getMerchantUpazilaWeightPackageCODAndCharge(Request $request) {

        $validator = Validator::make($request->all(), [
            'merchant_id' => 'required',
            'district_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 401,
                'message' => "Validation Error.",
                'error'   => $validator->errors(),
            ], 401);
        }

        $district = District::where('id', $request->district_id)->first();
        $merchant = Merchant::where('id', $request->merchant_id)->select("id", "cod_charge","district_id")->first();

        if (!empty($district)) {
            $charge             = $district->service_area->default_charge;

            $return_charge      = 0;
            // $upazilas = Upazila::where('district_id', $request->district_id)
            //     ->select('id', 'name', 'district_id', 'status')
            //     ->get();
            $service_area_id = $district->service_area_id;

            $weightPackages = WeightPackage::with([
                    'service_area' => function ($query) use ($service_area_id) {
                        $query->where('service_area_id', '=', $service_area_id);
                        $query->select('weight_package_id',  'rate');
                    },
                ])
                ->where(['status' => 1])
                ->orderBy('weight_type', 'asc')
                ->select('id', 'name', 'title', 'weight_type', 'details', 'rate')
                ->get();

            $serviceTypes = ServiceType::where('service_area_id', $service_area_id)
                ->where(['status' => 1])
                ->select('id', 'title', 'rate','service_area_id')
                ->get();

            $itemTypes = ItemType::where('service_area_id', $service_area_id)
                ->where(['status' => 1])
                ->select('id', 'title', 'rate','service_area_id')
                ->get();

            $newWeightPackages = [];

            foreach($weightPackages as $weightPackage){

                $rate = $weightPackage->rate;
                if(!empty($weightPackage->service_area)){
                    $rate = $weightPackage->service_area->rate;
                }
                $newWeightPackages[] = [
                    'id'        => $weightPackage->id,
                    'name'      => $weightPackage->name,
                    'title'     => $weightPackage->title,
                    'weight_type'     => $weightPackage->weight_type,
                    'details'     => $weightPackage->details,
                    'rate'      => $rate,
                ];
            }


            $merchantServiceAreaCharge = MerchantServiceAreaCharge::where([
                'service_area_id' => $service_area_id,
                'merchant_id'     => $request->merchant_id,
            ])->first();

            if ($merchantServiceAreaCharge) {
                $charge = $merchantServiceAreaCharge->charge;
            }
            
            
            
            
             if($merchant->district_id!=1){
                if ($merchant->district_id == $request->input('district_id')) {
                    $serviceArea = ServiceArea::where('id', 1)->first();
                    $charge = $serviceArea->default_charge;

                    $merchantServiceAreaCharge = MerchantServiceAreaCharge::where([
                        'service_area_id' => 1,
                        'merchant_id' => $request->merchant_id,
                    ])->first();
                } else {
                    $serviceArea = ServiceArea::where('id', 3)->first();
                    $charge = $serviceArea->default_charge;

                    //                        $charge = $district->service_area->default_charge;

                    $merchantServiceAreaCharge = MerchantServiceAreaCharge::where([
                        'service_area_id' => 3,
                        'merchant_id' => $request->merchant_id,
                    ])->first();
                }
                if ($merchantServiceAreaCharge) {
                    $charge = $merchantServiceAreaCharge->charge;
                }

                $merchant_service_area_charge = $charge;

            }   
            
            
            
            
            

            $merchantServiceAreaReturnCharge = MerchantServiceAreaReturnCharge::where([
                'service_area_id' => $district->service_area_id,
                'merchant_id'     => $request->merchant_id,
            ])->first();


            //Set Default Return Charge 1/2 of Delivery Charge
            $return_charge = $charge/2;
            if ($merchantServiceAreaReturnCharge) {
                //Set Return Charge Merchant Wise
                $return_charge = $merchantServiceAreaReturnCharge->return_charge;
            }

            $code_charge_percent = $district->service_area->cod_charge;
            if($code_charge_percent != 0){

                $merchantServiceAreaCodCharge = MerchantServiceAreaCodCharge::where([
                    'service_area_id' => $district->service_area_id,
                    'merchant_id'     => $request->merchant_id,
                ])->first();

                if ($merchantServiceAreaCodCharge) {
                    $code_charge_percent = $merchantServiceAreaCodCharge->cod_charge;
                }

            }


            return response()->json([
                'success'               => 200,
                'message'               => "Merchant Weight Package, Cod Charge and Merchant Delivery Charge result found.",
                'serviceTypes'        => $serviceTypes,
                'itemTypes'        => $itemTypes,
                'weightPackages'        => $newWeightPackages,
                'charge'                => $charge,
                'return_charge'         => $return_charge,
                'cod_charge_percent'    => $code_charge_percent,
            ], 200);
        }

        return response()->json([
            'success' => 401,
            'message' => "Merchant Upazila, Weight Package, Cod Charge and Merchant Delivery Charge not found.",
            'error'   => "District Weight Package Rate not found.",
        ], 401);
    }
    
    
        public function parcelStatus(Request $request) {
        $parcel_invoice = $request->input('parcel_invoice');
        $merchant_order_id = $request->input('merchant_order_id');


        $merchant = auth()->guard('merchant_api')->user();
        $merchant_id = $merchant->id;
//        dd($merchant_id);
        $parcel = Parcel::with('district', 'upazila', 'area', 'merchant',
            'weight_package', 'pickup_branch', 'pickup_rider',
            'delivery_branch', 'delivery_rider')
            ->where('merchant_id',$merchant_id)
            ->where(function ($query) use ($parcel_invoice,$merchant_order_id) {
                $query->where('parcel_invoice', "$parcel_invoice");
                $query->orWhere('merchant_order_id', "$merchant_order_id");
            })
            ->first();
//            ->toSql();
//        dd($parcel);

        if ($parcel){
            $parcelStatus = returnParcelStatusNameForMerchant($parcel->status, $parcel->delivery_type, $parcel->payment_type);

            $data=[
                'parcel_invoice'=>$parcel->parcel_invoice,
                'merchant_order_id'=>$parcel->merchant_order_id,
                'customer_name'=>$parcel->customer_name,
                'customer_contact_number'=>$parcel->customer_contact_number,
                'district'=>$parcel->district->name,
                'area'=>$parcel->area->name,
                'customer_address'=>$parcel->customer_address,
                'total_collect_amount'=>$parcel->total_collect_amount,
                'collected'=>$parcel->customer_collect_amount,
                'total_charge'=>$parcel->total_charge,
                'status'=>$parcelStatus['status_name'],
            ];

            return response()->json([
                'success'        => 200,
                'message'        => "Parcel information found.",
                'parcel_info' => $data,
            ], 200);


        }else{
            return response()->json([
                'success' => 404,
                'message' => "Parcel not found.",
                'error'   => "Parcel not found.",
            ], 404);
        }
    }

}


