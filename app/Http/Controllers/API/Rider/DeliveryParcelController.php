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

class DeliveryParcelController extends Controller
{

    public function getDeliveryParcelList(Request $request)
    {
        $rider_id = auth()->guard('rider_api')->user()->id;

        $parcels = Parcel::with([
            'district:id,name',
            'upazila:id,name',
            'area:id,name',
            'merchant:id,company_name,address,contact_number',
            'weight_package:id,name', 'parcel_logs:note'
        ])
            ->whereRaw('(delivery_rider_id = ? and status in (17, 19) )', [$rider_id])
            ->orderBy('id', 'desc')
            ->select(
                'id', 'parcel_invoice', 'customer_name', 'customer_address',
                'customer_contact_number', 'total_charge', 'total_collect_amount',
                'district_id', 'merchant_id', 'status', 'parcel_note','customer_collect_amount'
            )
            ->get();

        $new_parcels = [];

        foreach ($parcels as $parcel) {
            switch ($parcel->status) {
                case 1 :
                    $parcel_status = "Parcel Send Pick Request";
                    break;
                case 2 :
                    $parcel_status = "Parcel Hold";
                    break;
                case 3 :
                    $parcel_status = "Parcel Cancel";
                    break;
                case 4 :
                    $parcel_status = "Parcel Reschedule";
                    break;
                case 5 :
                    $parcel_status = "Pickup Run Start";
                    break;
                case 6 :
                    $parcel_status = "Pickup Run Create";
                    break;
                case 7 :
                    $parcel_status = "Pickup Run Cancel";
                    break;
                case 8 :
                    $parcel_status = "Pickup Run Accept Rider";
                    break;
                case 9 :
                    $parcel_status = "Pickup Run Cancel Rider";
                    break;
                case 10 :
                    $parcel_status = "Pickup Run Complete Rider";
                    break;
                case 11 :
                    $parcel_status = "Pickup Complete";
                    break;
                case 12 :
                    $parcel_status = "Assign Delivery Branch";
                    break;
                case 13 :
                    $parcel_status = "Assign Delivery Branch Cancel";
                    break;
                case 14 :
                    $parcel_status = "Assign Delivery Branch Received";
                    break;
                case 15 :
                    $parcel_status = "Assign Delivery Branch Reject";
                    break;
                case 16 :
                    $parcel_status = "Delivery Run Create";
                    break;
                case 17 :
                    $parcel_status = "Delivery Run Start";
                    break;
                case 18 :
                    $parcel_status = "Delivery Run Cancel";
                    break;
                case 19 :
                    $parcel_status = "Delivery Run Rider Accept";
                    break;
                case 20 :
                    $parcel_status = "Delivery Run Rider Reject";
                    break;
                case 21 :
                    $parcel_status = "Delivery Rider Delivery";
                    break;
                case 22 :
                    $parcel_status = "Delivery Rider Partial Delivery";
                    break;
                case 23 :
                    $parcel_status = "Delivery Rider Reschedule";
                    break;
                case 24 :
                    $parcel_status = "Delivery Rider Return";
                    break;
                case 25 :
                    $parcel_status = "Delivery  Complete";
                    break;
                case 26 :
                    $parcel_status = "Return Branch Assign";
                    break;
                case 27 :
                    $parcel_status = "Return Branch Assign Cancel";
                    break;
                case 28 :
                    $parcel_status = "Return Branch Assign Received";
                    break;
                case 29 :
                    $parcel_status = "Return Branch Assign Reject";
                    break;
                case 30 :
                    $parcel_status = "Return Branch   Run Create";
                    break;
                case 31 :
                    $parcel_status = "Return Branch  Run Start";
                    break;
                case 32 :
                    $parcel_status = "Return Branch  Run Cancel";
                    break;
                case 33 :
                    $parcel_status = "Return Rider Accept";
                    break;
                case 34 :
                    $parcel_status = "Return Rider Reject";
                    break;
                case 35 :
                    $parcel_status = "Return Rider Complete";
                    break;
                case 36 :
                    $parcel_status = "Return Branch  Run Complete";
                    break;
                default :
                    break;
            }
            $logs_note = "";
            if ($parcel->parcel_logs) {
                foreach ($parcel->parcel_logs as $parcel_log) {
                    $logs_note .= $parcel_log->note;
                    if (null != $parcel_log->note && "" != $parcel_log->note) {
                        $logs_note .= ", ";
                    }
                }
            }
            $new_parcels[] = [
                'parcel_id' => $parcel->id,
                'parcel_invoice' => $parcel->parcel_invoice,
                'customer_name' => $parcel->customer_name,
                'customer_address' => $parcel->customer_address,
                'customer_contact_number' => $parcel->customer_contact_number,
                'collect_amount' => $parcel->total_collect_amount,
                'amount_to_be_collect' => $parcel->customer_collect_amount,
                'district_name' => $parcel->district->name,
                'upazila_name' => $parcel->upazila->name,
                'area_name' => $parcel->area->name,
                'weight_package_name' => $parcel->weight_package->name,
                'merchant_id' => $parcel->merchant->id,
                'merchant_name' => $parcel->merchant->company_name,
                'merchant_address' => $parcel->merchant->address,
                'merchant_contact_number' => $parcel->merchant->contact_number,
                'parcel_status' => $parcel_status,
                'status' => $parcel->status,
                'parcel_note' => $parcel->parcel_note,
                'logs_note' => $logs_note,
            ];
        }

        return response()->json([
            'success' => 200,
            'message' => "Delivery Parcel Results",
            'parcels' => $new_parcels,
        ], 200);
    }


    public
    function getDeliveryParcel(Request $request)
    {

        $rider_id = auth()->guard('rider_api')->user()->id;

        $validator = Validator::make($request->all(), [
            'parcel_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 401,
                'message' => "Validation Error.",
                'error' => $validator->errors(),
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
            ->whereRaw('(delivery_rider_id = ? and status in (17, 19) )', [$rider_id])
            ->where('id', $request->parcel_id)
            ->first();


        if ($parcel) {

            $parcel_status = "";
            switch ($parcel->status) {
                case 1 :
                    $parcel_status = "Parcel Send Pick Request";
                    break;
                case 2 :
                    $parcel_status = "Parcel Hold";
                    break;
                case 3 :
                    $parcel_status = "Parcel Cancel";
                    break;
                case 4 :
                    $parcel_status = "Parcel Reschedule";
                    break;
                case 5 :
                    $parcel_status = "Pickup Run Start";
                    break;
                case 6 :
                    $parcel_status = "Pickup Run Create";
                    break;
                case 7 :
                    $parcel_status = "Pickup Run Cancel";
                    break;
                case 8 :
                    $parcel_status = "Pickup Run Accept Rider";
                    break;
                case 9 :
                    $parcel_status = "Pickup Run Cancel Rider";
                    break;
                case 10 :
                    $parcel_status = "Pickup Run Complete Rider";
                    break;
                case 11 :
                    $parcel_status = "Pickup Complete";
                    break;
                case 12 :
                    $parcel_status = "Assign Delivery Branch";
                    break;
                case 13 :
                    $parcel_status = "Assign Delivery Branch Cancel";
                    break;
                case 14 :
                    $parcel_status = "Assign Delivery Branch Received";
                    break;
                case 15 :
                    $parcel_status = "Assign Delivery Branch Reject";
                    break;
                case 16 :
                    $parcel_status = "Delivery Run Create";
                    break;
                case 17 :
                    $parcel_status = "Delivery Run Start";
                    break;
                case 18 :
                    $parcel_status = "Delivery Run Cancel";
                    break;
                case 19 :
                    $parcel_status = "Delivery Run Rider Accept";
                    break;
                case 20 :
                    $parcel_status = "Delivery Run Rider Reject";
                    break;
                case 21 :
                    $parcel_status = "Delivery Rider Delivery";
                    break;
                case 22 :
                    $parcel_status = "Delivery Rider Partial Delivery";
                    break;
                case 23 :
                    $parcel_status = "Delivery Rider Reschedule";
                    break;
                case 24 :
                    $parcel_status = "Delivery Rider Return";
                    break;
                case 25 :
                    $parcel_status = "Delivery  Complete";
                    break;
                case 26 :
                    $parcel_status = "Return Branch Assign";
                    break;
                case 27 :
                    $parcel_status = "Return Branch Assign Cancel";
                    break;
                case 28 :
                    $parcel_status = "Return Branch Assign Received";
                    break;
                case 29 :
                    $parcel_status = "Return Branch Assign Reject";
                    break;
                case 30 :
                    $parcel_status = "Return Branch   Run Create";
                    break;
                case 31 :
                    $parcel_status = "Return Branch  Run Start";
                    break;
                case 32 :
                    $parcel_status = "Return Branch  Run Cancel";
                    break;
                case 33 :
                    $parcel_status = "Return Rider Accept";
                    break;
                case 34 :
                    $parcel_status = "Return Rider Reject";
                    break;
                case 35 :
                    $parcel_status = "Return Rider Complete";
                    break;
                case 36 :
                    $parcel_status = "Return Branch  Run Complete";
                    break;
                default :
                    break;
            }
            $parcel->status = $parcel_status;
            $data = [
                'parcel_id' => $parcel->id,
                'parcel_status' => $parcel_status,
                'status' => $parcel_status,
                'parcel_invoice' => $parcel->parcel_invoice,
                'merchant_order_id' => $parcel->merchant_order_id,
                'merchant_order_id' => $parcel->merchant_order_id,
                'customer_name' => $parcel->customer_name,
                'customer_address' => $parcel->customer_address,
                'customer_contact_number' => $parcel->customer_contact_number,
                'total_collect_amount' => $parcel->total_collect_amount,
                'product_details' => $parcel->product_details,
                'area_name' => $parcel->area->name,
                'area_post_code' => $parcel->area->post_code,
                'upazila_name' => $parcel->upazila->name,
                'district_name' => $parcel->district->name,
                'merchant_id' => $parcel->merchant_id,
                'merchant_name' => $parcel->merchant->name,
                'merchant_email' => $parcel->merchant->email,
                'merchant_company_name' => $parcel->merchant->company_name,
                'merchant_address' => $parcel->merchant->address,
                'merchant_contact_number' => $parcel->merchant->contact_number,
                'weight_package_name' => $parcel->weight_package->name,
                'delivery_branch_name' => $parcel->delivery_branch->name,
                'delivery_branch_address' => $parcel->delivery_branch->address,
            ];
            return response()->json([
                'success' => 200,
                'message' => "Parcel found",
                'parcel' => $data,
            ], 200);
        }

        return response()->json([
            'success' => 401,
            'message' => "Delivery Parcel Not found",
            'error' => "Delivery Parcel Not found",
        ], 401);
    }


    public
    function parcelDeliveryRequestAccept(Request $request)
    {
        $rider_id = auth()->guard('rider_api')->user()->id;

        $validator = Validator::make($request->all(), [
            'parcel_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 401,
                'message' => "Validation Error",
                'error' => $validator->errors(),
            ], 401);
        }
        \DB::beginTransaction();
        try {
            $riderRunDetail = RiderRunDetail::where([
                'status' => 2,
                'parcel_id' => $request->parcel_id,
            ])
                ->whereHas('rider_run', function ($query) use ($rider_id) {
                    $query->where([
                        ['run_type', '=', 2],
                        ['status', '=', 2],
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
                    'status' => 19,
                    'parcel_date' => date('Y-m-d'),
                    'delivery_rider_date' => date('Y-m-d'),
                    'delivery_rider_accept_date' => date('Y-m-d'),
                ]);
                ParcelLog::create([
                    'parcel_id' => $request->parcel_id,
                    'delivery_rider_id' => $rider_id,
                    'date' => date('Y-m-d'),
                    'time' => date('H:i:s'),
                    'status' => 19,
                    'delivery_type' => $parcel->delivery_type,
                ]);

                \DB::commit();
                return response()->json([
                    'success' => 200,
                    'message' => "Rider Parcel Delivery Request Accept Successfully",
                ], 200);

            } else {
                return response()->json([
                    'success' => 401,
                    'message' => "Rider Parcel Delivery Request Accept Unsuccessfully",
                    'error' => "Rider Parcel Delivery Request Accept Unsuccessfully",
                ], 401);
            }
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json([
                'success' => 401,
                'message' => "Rider Parcel Delivery Request Accept Unauthorized",
                'error' => "Rider Parcel Delivery Request Accept Unauthorized",
            ], 401);
        }
    }


    public
    function parcelDeliveryRequestReject(Request $request)
    {
        $rider_id = auth()->guard('rider_api')->user()->id;

        $validator = Validator::make($request->all(), [
            'parcel_id' => 'required',
            'note' => 'sometimes',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 401,
                'message' => "Validation Error",
                'error' => $validator->errors(),
            ], 401);
        }
        \DB::beginTransaction();
        try {
            $riderRunDetail = RiderRunDetail::where([
                'status' => 2,
                'parcel_id' => $request->parcel_id,
            ])
                ->whereHas('rider_run', function ($query) use ($rider_id) {
                    $query->where([
                        ['run_type', '=', 2],
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
                    'status' => 20,
                    'parcel_date' => date('Y-m-d'),
                    'delivery_rider_date' => date('Y-m-d'),
                    'delivery_rider_accept_date' => date('Y-m-d'),
                ]);
                ParcelLog::create([
                    'parcel_id' => $request->parcel_id,
                    'delivery_rider_id' => $rider_id,
                    'date' => date('Y-m-d'),
                    'time' => date('H:i:s'),
                    'status' => 20,
                    'delivery_type' => $parcel->delivery_type,
                ]);

                \DB::commit();
                return response()->json([
                    'success' => 200,
                    'message' => "Rider Parcel Delivery Request Reject Successfully",
                ], 200);

            } else {
                return response()->json([
                    'success' => 401,
                    'message' => "Rider Parcel Delivery Request Reject Unsuccessfully",
                    'error' => "Rider Parcel Delivery Request Reject Unsuccessfully",
                ], 401);
            }
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json([
                'success' => 401,
                'message' => "Rider Parcel Delivery Request Reject Unauthorized",
                'error' => "Rider Parcel Delivery Request Reject Unauthorized",
            ], 401);
        }
    }


    public
    function returnConfirmParcelCode(Request $request)
    {

        $rider_id = auth()->guard('rider_api')->user()->id;

        $validator = Validator::make($request->all(), [
            'parcel_id' => 'required',
            'parcel_code' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 401,
                'message' => "Validation Error.",
                'error' => $validator->errors(),
            ], 401);
        }


        $parcel = Parcel::where([
            ['delivery_rider_id', '=', $rider_id],
            ['id', '=', $request->parcel_id],
            ['parcel_code', '=', $request->parcel_code],
            ['status', '=', 19],
        ])
            ->first();

        if ($parcel) {
            return response()->json([
                'success' => 200,
                'message' => "Parcel Code Matched",
            ], 200);
        }

        return response()->json([
            'success' => 401,
            'message' => "Parcel Code not Matching",
        ], 401);
    }

    /** For Customer OTP SEND */
    public
    function parcelDeliveryOtpSendCustomer(Request $request)
    {
        $rider_id = auth()->guard('rider_api')->user()->id;

        $validator = Validator::make($request->all(), [
            'parcel_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 401,
                'message' => "Validation Error",
                'error' => $validator->errors(),
            ], 401);
        }

        try {
            $parcel = Parcel::where('id', $request->get('parcel_id'))
                ->where('delivery_rider_id', $rider_id)->first();
            $message = "Dear " . $parcel->customer_name . ", ";
            $message .= "Your sms OTP " . $parcel->parcel_code . ", ";
            $message .= "For  parcel ID No " . $parcel->parcel_invoice . ".";
            $message .= "Please rate your experience with us in our https://www.facebook.com/eyecondeliver";
            if ($this->send_sms($parcel->customer_contact_number, $message)) {
                return response()->json([
                    'success' => 200,
                    'message' => "Customer OTP Send Successfully",
                ], 200);

            } else {
                return response()->json([
                    'success' => 401,
                    'message' => "Customer OTP Send Failed!",
                    'error' => "Customer OTP Send Failed!",
                ], 401);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => 401,
                'message' => "Oop's, something went wrong. Please try again!",
                'error' => "Oop's, something went wrong. Please try again!",
            ], 401);
        }
    }

    public
    function parcelDeliveryComplete(Request $request)
    {
        $rider_id = auth()->guard('rider_api')->user()->id;

        $validator = Validator::make($request->all(), [
            'parcel_id' => 'required',
            'delivery_type' => 'required',
            'customer_collect_amount' => 'sometimes',
            'parcel_code' => 'sometimes',
            'reschedule_date' => 'sometimes',
            'parcel_note' => 'sometimes',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 401,
                'message' => "Validation Error",
                'error' => $validator->errors(),
            ], 401);
        }
        \DB::beginTransaction();
        try {
            $riderRunDetail = RiderRunDetail::where([
                'status' => 4,
                'parcel_id' => $request->parcel_id,
            ])
                ->whereHas('rider_run', function ($query) use ($rider_id) {
                    $query->where([
                        ['run_type', '=', 2],
                        ['status', '=', 2],
                        ['rider_id', '=', $rider_id],
                    ]);
                })
                ->first();

            if ($riderRunDetail) {
                $customer_collect_amount = $request->customer_collect_amount ?? 0;
                $parcel_code = $request->parcel_code;
                $reschedule_date = $request->reschedule_date;
                $delivery_type = (int)$request->delivery_type;
                $parcel_note = $request->parcel_note;

                if ($delivery_type == 0
                    || is_null($delivery_type)
                    || !($delivery_type == 21 || $delivery_type == 22 || $delivery_type == 23 || $delivery_type == 24)
                ) {
                    return response()->json([
                        'success' => 401,
                        'message' => "Delivery Type required",
                    ], 401);
                }

                if ($delivery_type == 21) {
                    $parcel = Parcel::where([
                        'id' => $request->parcel_id,
//                        'parcel_code'   => $parcel_code,
                        'total_collect_amount' => $customer_collect_amount,
                    ])->first();

                    if (empty($parcel)) {
                        return response()->json([
                            'success' => 401,
                            'message' => "Parcel Code Or Collection not Matching",
                        ], 401);
                    }
                }


                if ($delivery_type == 22) {
                    $parcel = Parcel::where([
                        'id' => $request->parcel_id,
//                        'parcel_code'   => $parcel_code,
                    ])->first();

                    if (empty($parcel)) {
                        return response()->json([
                            'success' => 401,
                            'message' => "Parcel Code not Matching",
                        ], 401);
                    }
                }

                if ($delivery_type == 23 && is_null($reschedule_date)) {
                    return response()->json([
                        'success' => 401,
                        'message' => "Parcel Reschedule Date Required",
                    ], 401);
                }

                if ($delivery_type == 24 && is_null($parcel_note)) {
                    return response()->json([
                        'success' => 401,
                        'message' => "Parcel Return Note Required",
                    ], 401);
                }


                RiderRun::where('id', $riderRunDetail->rider_run_id)->update([
                    'total_run_complete_parcel' => $riderRunDetail->rider_run->total_run_complete_parcel + 1,
                ]);

                RiderRunDetail::where('id', $riderRunDetail->id)->update([
                    'complete_note' => $request->parcel_note,
                    'complete_date_time' => date('Y-m-d H:i:s'),
                    'status' => 7,
                ]);

                $parcel_update_data = [
                    'status' => $delivery_type,
                    'delivery_rider_id' => $rider_id,
                    'parcel_date' => date('Y-m-d'),
                    'delivery_rider_date' => date('Y-m-d'),
                ];
                $parcel_log_update_data = [
                    'parcel_id' => $request->parcel_id,
                    'delivery_rider_id' => $rider_id,
                    'date' => date('Y-m-d'),
                    'time' => date('H:i:s'),
                    'status' => $delivery_type,
                ];

                // Delivery Parcel Complete
                if ($delivery_type == '21') {
                    $parcel_update_data['delivery_type'] = 1;
                    $parcel_update_data['customer_collect_amount'] = $customer_collect_amount;
                } // Delivery Parcel Partial Delivery
                elseif ($delivery_type == '22') {
                    $parcel_update_data['delivery_type'] = 2;
                    $parcel_update_data['customer_collect_amount'] = $customer_collect_amount ?? 0;
                    $parcel_update_data['note'] = $parcel_note;
                } // Delivery Parcel Reschedule
                elseif ($delivery_type == '23') {
                    $parcel_update_data['delivery_type'] = 3;
                    $parcel_update_data['reschedule_parcel_date'] = $reschedule_date;
                    $parcel_update_data['note'] = $parcel_note;
                    $parcel_log_update_data['reschedule_parcel_date'] = $reschedule_date;
                } // Delivery Parcel Return
                elseif ($delivery_type == '24') {
                    $parcel_update_data['delivery_type'] = 4;
                    $parcel_update_data['note'] = $parcel_note;
                }
                Parcel::where('id', $request->parcel_id)->update($parcel_update_data);
                $parcel = Parcel::where('id', $request->parcel_id)->first();
                $parcel_log_update_data['delivery_type'] = $parcel->delivery_type;
                $parcel_log_update_data['note'] = $parcel_note;
                ParcelLog::create($parcel_log_update_data);


                \DB::commit();
                return response()->json([
                    'success' => 200,
                    'message' => "Parcel Delivered Successfully",
                ], 200);

            } else {
                return response()->json([
                    'success' => 401,
                    'message' => "Parcel Delivery Unsuccessfully",
                    'error' => "Parcel Delivery Unsuccessfully",
                ], 401);
            }
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json([
                'success' => 401,
                'message' => "Parcel Delivery Unsuccessfully",
                'error' => "Rider Delivery Unsuccessfully",
            ], 401);
        }
    }

}
