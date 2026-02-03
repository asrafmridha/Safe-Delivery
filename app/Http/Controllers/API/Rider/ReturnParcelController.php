<?php

namespace App\Http\Controllers\API\Rider;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\Parcel;
use App\Models\ParcelLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\MerchantServiceAreaCharge;
use App\Models\MerchantServiceAreaReturnCharge;
use App\Models\WeightPackage;
use App\Models\RiderRunDetail;
use App\Models\RiderRun;

class ReturnParcelController extends Controller {

    public function getReturnParcelList(Request $request) {
        $rider_id = auth()->guard('rider_api')->user()->id;

        $parcels = Parcel::with([
                'district:id,name',
                'area:id,name',
                'merchant:id,company_name,address,contact_number',
                'weight_package:id,name'
            ])
            ->whereRaw('(return_rider_id = ? and status in (31, 33) )', [$rider_id])
            ->orderBy('id', 'desc')
            ->select(
                'id','parcel_invoice', 'customer_name','customer_address',
                'customer_contact_number', 'total_charge', 'total_collect_amount',
                'district_id', 'merchant_id', 'status'
                )
            ->get();

        $new_parcels = [];

        foreach($parcels as $parcel){
            switch($parcel->status){
                case 1 : $parcel_status     = "Parcel Send Pick Request"; break;
                case 2 : $parcel_status     = "Parcel Hold"; break;
                case 3 : $parcel_status     = "Parcel Cancel"; break;
                case 4 : $parcel_status     = "Parcel Reschedule";  break;
                case 5 : $parcel_status     = "Pickup Run Start"; break;
                case 6 : $parcel_status     = "Pickup Run Create"; break;
                case 7 : $parcel_status     ="Pickup Run Cancel"; break;
                case 8 : $parcel_status     = "Pickup Run Accept Rider";  break;
                case 9 : $parcel_status     = "Pickup Run Cancel Rider"; break;
                case 10 : $parcel_status     = "Pickup Run Complete Rider"; break;
                case 11 : $parcel_status     = "Pickup Complete";  break;
                case 12 : $parcel_status     = "Assign Delivery Branch"; break;
                case 13 : $parcel_status     = "Assign Delivery Branch Cancel"; break;
                case 14 : $parcel_status     = "Assign Delivery Branch Received";  break;
                case 15 : $parcel_status     = "Assign Delivery Branch Reject";  break;
                case 16 : $parcel_status     = "Delivery Run Create"; break;
                case 17 : $parcel_status     = "Delivery Run Start"; break;
                case 18 : $parcel_status     = "Delivery Run Cancel"; break;
                case 19 : $parcel_status     = "Delivery Run Rider Accept"; break;
                case 20 : $parcel_status     = "Delivery Run Rider Reject"; break;
                case 21 : $parcel_status     = "Delivery Rider Delivery"; break;
                case 22 : $parcel_status     = "Delivery Rider Partial Delivery"; break;
                case 23 : $parcel_status     = "Delivery Rider Reschedule";  break;
                case 24 : $parcel_status     = "Delivery Rider Return"; break;
                case 25 : $parcel_status     = "Delivery  Complete"; break;
                case 26 : $parcel_status     = "Return Branch Assign"; break;
                case 27 : $parcel_status     = "Return Branch Assign Cancel"; break;
                case 28 : $parcel_status     = "Return Branch Assign Received"; break;
                case 29 : $parcel_status     = "Return Branch Assign Reject"; break;
                case 30 : $parcel_status     = "Return Branch   Run Create"; break;
                case 31 : $parcel_status     = "Return Branch  Run Start"; break;
                case 32 : $parcel_status     =  "Return Branch  Run Cancel"; break;
                case 33 : $parcel_status     = "Return Rider Accept"; break;
                case 34 : $parcel_status     = "Return Rider Reject"; break;
                case 35 : $parcel_status     = "Return Rider Complete"; break;
                case 36 : $parcel_status     =  "Return Branch  Run Complete";  break;
                default : break;
            }

            $new_parcels[] = [
                'parcel_id'             => $parcel->id,
                'parcel_invoice'        => $parcel->parcel_invoice,
                'customer_name'         => $parcel->customer_name,
                'customer_address'      => $parcel->customer_address,
                'customer_contact_number' => $parcel->customer_contact_number,
                'total_collect_amount'  => $parcel->total_collect_amount,
                'district_name'         => $parcel->district->name,
                'upazila_name'          => $parcel->upazila->name,
                'area_name'             => $parcel->area->name,
                'weight_package_name'   => $parcel->weight_package->name,
                'merchant_id'           => $parcel->merchant->id,
                'merchant_name'         => $parcel->merchant->company_name,
                'merchant_address'      => $parcel->merchant->address,
                'merchant_contact_number' => $parcel->merchant->contact_number,
                'parcel_status'         => $parcel_status,
                'status'                => $parcel->status,
            ];
        }

        return response()->json([
            'success' => 200,
            'message' => "Return Parcel Results",
            'parcels' => $new_parcels,
        ], 200);
    }


    public function getReturnParcel(Request $request) {
        $rider_id   = auth()->guard('rider_api')->user()->id;

        $validator = Validator::make($request->all(), [
            'parcel_id'    => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 401,
                'message' => "Validation Error.",
                'error'   => $validator->errors(),
            ], 401);
        }


        $parcel = Parcel::with([
                'district:id,name',
                'upazila:id,name',
                'area:id,name,post_code',
                'merchant:id,m_id,name,email,image,company_name,address,contact_number',
                'weight_package:id,name,title,weight_type',
                'pickup_branch:id,name,email,address,contact_number',
                'pickup_rider:id,name,email,address,contact_number',
                'delivery_branch:id,name,email,address,contact_number',
                'delivery_rider:id,name,email,address,contact_number',
                'return_branch:id,name,email,address,contact_number',
                'return_rider:id,name,email,address,contact_number',
            ])
            ->whereRaw('(pickup_rider_id = ? and status in (31) )', [$rider_id])
            ->where('id', $request->parcel_id)
            ->first();


        if ($parcel) {

            $parcel_status = "";
            switch($parcel->status){
                case 1 : $parcel_status     = "Parcel Send Pick Request"; break;
                case 2 : $parcel_status     = "Parcel Hold"; break;
                case 3 : $parcel_status     = "Parcel Cancel"; break;
                case 4 : $parcel_status     = "Parcel Reschedule";  break;
                case 5 : $parcel_status     = "Pickup Run Start"; break;
                case 6 : $parcel_status     = "Pickup Run Create"; break;
                case 7 : $parcel_status     ="Pickup Run Cancel"; break;
                case 8 : $parcel_status     = "Pickup Run Accept Rider";  break;
                case 9 : $parcel_status     = "Pickup Run Cancel Rider"; break;
                case 10 : $parcel_status     = "Pickup Run Complete Rider"; break;
                case 11 : $parcel_status     = "Pickup Complete";  break;
                case 12 : $parcel_status     = "Assign Delivery Branch"; break;
                case 13 : $parcel_status     = "Assign Delivery Branch Cancel"; break;
                case 14 : $parcel_status     = "Assign Delivery Branch Received";  break;
                case 15 : $parcel_status     = "Assign Delivery Branch Reject";  break;
                case 16 : $parcel_status     = "Delivery Run Create"; break;
                case 17 : $parcel_status     = "Delivery Run Start"; break;
                case 18 : $parcel_status     = "Delivery Run Cancel"; break;
                case 19 : $parcel_status     = "Delivery Run Rider Accept"; break;
                case 20 : $parcel_status     = "Delivery Run Rider Reject"; break;
                case 21 : $parcel_status     = "Delivery Rider Delivery"; break;
                case 22 : $parcel_status     = "Delivery Rider Partial Delivery"; break;
                case 23 : $parcel_status     = "Delivery Rider Reschedule";  break;
                case 24 : $parcel_status     = "Delivery Rider Return"; break;
                case 25 : $parcel_status     = "Delivery  Complete"; break;
                case 26 : $parcel_status     = "Return Branch Assign"; break;
                case 27 : $parcel_status     = "Return Branch Assign Cancel"; break;
                case 28 : $parcel_status     = "Return Branch Assign Received"; break;
                case 29 : $parcel_status     = "Return Branch Assign Reject"; break;
                case 30 : $parcel_status     = "Return Branch   Run Create"; break;
                case 31 : $parcel_status     = "Return Branch  Run Start"; break;
                case 32 : $parcel_status     =  "Return Branch  Run Cancel"; break;
                case 33 : $parcel_status     = "Return Rider Accept"; break;
                case 34 : $parcel_status     = "Return Rider Reject"; break;
                case 35 : $parcel_status     = "Return Rider Complete"; break;
                case 36 : $parcel_status     =  "Return Branch  Run Complete";  break;
                default : break;
            }
            $parcel->status = $parcel_status;
            $data = [
                'parcel_id'                     =>   $parcel->id,
                'parcel_status'                 =>   $parcel_status,
                'status'                        =>  $parcel->status,
                'parcel_invoice'                =>   $parcel->parcel_invoice,
                'merchant_order_id'             =>   $parcel->merchant_order_id,
                'merchant_order_id'             =>   $parcel->merchant_order_id,
                'customer_name'                 =>   $parcel->customer_name,
                'customer_address'              =>   $parcel->customer_address,
                'customer_contact_number'       =>   $parcel->customer_contact_number,
                'product_details'               =>   $parcel->product_details,
                'area_name'                     =>    $parcel->area->name,
                'area_post_code'                =>    $parcel->area->post_code,
                'upazila_name'                  =>    $parcel->upazila->name,
                'district_name'                 =>    $parcel->district->name,
                'merchant_id'                   =>    $parcel->merchant_id,
                'merchant_name'                 =>    $parcel->merchant->name,
                'merchant_email'                =>    $parcel->merchant->email,
                'merchant_company_name'         =>    $parcel->merchant->company_name,
                'merchant_address'              =>    $parcel->merchant->address,
                'merchant_contact_number'       =>    $parcel->merchant->contact_number,
                'weight_package_name'           =>    $parcel->weight_package->name,
                'pickup_branch_name'            =>    $parcel->pickup_branch->name,
                'pickup_branch_address'         =>    $parcel->pickup_branch->address,
            ];
            return response()->json([
                'success'    => 200,
                'message'    => "Parcel found",
                'parcel'     => $data,
            ], 200);
        }

        return response()->json([
            'success' => 401,
            'message' => "Pickup Parcel Not found",
            'error'   => "Pickup Parcel Not found",
        ], 401);
    }


    public function parcelReturnRequestAccept(Request $request) {
        $rider_id   = auth()->guard('rider_api')->user()->id;

        $validator = Validator::make($request->all(), [
            'parcel_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 401,
                'message' => "Validation Error",
                'error'   => $validator->errors(),
            ], 401);
        }
        \DB::beginTransaction();
        try {
            $riderRunDetail = RiderRunDetail::where([
                    'status'    => 2,
                    'parcel_id' => $request->parcel_id,
                ])
                ->whereHas('rider_run', function ($query) use ($rider_id) {
                    $query->where([
                        ['run_type', '=', 3],
                        ['status',  '=', 2],
                        ['rider_id', '=', $rider_id],
                    ]);
                })
                ->first();

            if ($riderRunDetail) {
                RiderRunDetail::where('id', $riderRunDetail->id)->update([
                    'status' => 4,
                ]);
                $parcel = Parcel::where('id', $request->parcel_id)->first();
                $parcel->update([
                    'status'            => 33,
                    'parcel_date'       => date('Y-m-d'),
                    'return_rider_date' => date('Y-m-d'),
                ]);
                ParcelLog::create([
                    'parcel_id'       => $request->parcel_id,
                    'return_rider_id' => $rider_id,
                    'date'            => date('Y-m-d'),
                    'time'            => date('H:i:s'),
                    'status'          => 33,
                    'delivery_type' => $parcel->delivery_type,
                ]);

                \DB::commit();
                return response()->json([
                    'success'   => 200,
                    'message'   => "Rider Parcel Return Request Accept Successfully",
                ], 200);

            } else {
                return response()->json([
                    'success' => 401,
                    'message' => "Rider Parcel Return Request Accept Unsuccessfully",
                    'error'   => "Rider Parcel Return Request Accept Unsuccessfully",
                ], 401);
            }
        }
        catch (\Exception $e){
            \DB::rollback();
            return response()->json([
                'success' => 401,
                'message' => "Rider Parcel Return Request Accept Unauthorized",
                'error'   => "Rider Parcel Return Request Accept Unauthorized",
            ], 401);
        }
    }


    public function parcelReturnRequestReject(Request $request) {
        $rider_id   = auth()->guard('rider_api')->user()->id;

        $validator = Validator::make($request->all(), [
            'parcel_id' => 'required',
            'note'      => 'sometimes',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 401,
                'message' => "Validation Error",
                'error'   => $validator->errors(),
            ], 401);
        }
        \DB::beginTransaction();
        try {
            $riderRunDetail = RiderRunDetail::where([
                    'status'    => 2,
                    'parcel_id' => $request->parcel_id,
                ])
                ->whereHas('rider_run', function ($query) use ($rider_id) {
                    $query->where([
                        ['run_type', '=', 3],
                        ['status', '=', 2],
                        ['rider_id', '=', $rider_id],
                    ]);
                })
                ->first();

            if ($riderRunDetail) {
                RiderRunDetail::where('id', $riderRunDetail->id)->update([
                    'status' => 5,
                ]);
                $parcel = Parcel::where('id', $request->parcel_id)->first();
                $parcel->update([
                    'status'            => 34,
                    'parcel_date'       => date('Y-m-d'),
                    'return_rider_date' => date('Y-m-d'),
                ]);
                ParcelLog::create([
                    'parcel_id'       => $request->parcel_id,
                    'return_rider_id' => $rider_id,
                    'date'            => date('Y-m-d'),
                    'time'            => date('H:i:s'),
                    'status'          => 34,
                    'delivery_type' => $parcel->delivery_type,
                ]);

                \DB::commit();
                return response()->json([
                    'success'   => 200,
                    'message'   => "Rider Parcel Return Request Reject Successfully",
                ], 200);

            } else {
                return response()->json([
                    'success' => 401,
                    'message' => "Rider Parcel Return Request Reject Unsuccessfully",
                    'error'   => "Rider Parcel Return Request Reject Unsuccessfully",
                ], 401);
            }
        }
        catch (\Exception $e){
            \DB::rollback();
            return response()->json([
                'success' => 401,
                'message' => "Rider Parcel Return Request Reject Unsuccessfully",
                'error'   => "Rider Parcel Return Request Reject Unsuccessfully",
            ], 401);
        }
    }


    public function parcelReturnComplete(Request $request) {
        $rider_id   = auth()->guard('rider_api')->user()->id;

        $validator = Validator::make($request->all(), [
            'parcel_id' => 'required',
            'note'      => 'sometimes',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 401,
                'message' => "Validation Error",
                'error'   => $validator->errors(),
            ], 401);
        }
        \DB::beginTransaction();
        try {
            $riderRunDetail = RiderRunDetail::where([
                    'status'    => 4,
                    'parcel_id' => $request->parcel_id,
                ])
                ->whereHas('rider_run', function ($query) use ($rider_id) {
                    $query->where([
                        ['run_type', '=',3],
                        ['status', '=', 2],
                        ['rider_id', '=', $rider_id],
                    ]);
                })
                ->first();

            if ($riderRunDetail) {

                RiderRun::where('id', $riderRunDetail->rider_run_id)->update([
                    'total_run_complete_parcel' => $riderRunDetail->rider_run->total_run_complete_parcel + 1,
                ]);

                RiderRunDetail::where('id', $riderRunDetail->id)->update([
                    'complete_note'      => $request->note,
                    'complete_date_time' => date('Y-m-d H:i:s'),
                    'status'             => 7,
                ]);

                $parcel = Parcel::where('id', $request->parcel_id)->first();
                $parcel->update([
                    'status'            => 35,
                    'note'       => $request->note,
                    'parcel_date'       => date('Y-m-d'),
                    'return_rider_date' => date('Y-m-d'),
                ]);
                ParcelLog::create([
                    'parcel_id'       => $request->parcel_id,
                    'return_rider_id' => $rider_id,
                    'date'            => date('Y-m-d'),
                    'time'            => date('H:i:s'),
                    'status'          => 35,
                    'delivery_type' => $parcel->delivery_type,
                    'note'       => $request->note,

                ]);

                \DB::commit();
                return response()->json([
                    'success'   => 200,
                    'message'   => "Rider Parcel Return Request Complete Successfully",
                ], 200);

            } else {
                return response()->json([
                    'success' => 401,
                    'message' => "Rider Parcel Return Request Complete Unsuccessfully",
                    'error'   => "Rider Parcel Return Request Complete Unsuccessfully",
                ], 401);
            }
        }
        catch (\Exception $e){
            \DB::rollback();
            return response()->json([
                'success' => 401,
                'message' => "Rider Parcel Return Request Complete Unsuccessfully",
                'error'   => "Rider Parcel Return Request Complete Unsuccessfully",
            ], 401);
        }
    }

}
