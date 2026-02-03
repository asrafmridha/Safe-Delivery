<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\Parcel;
use App\Models\ParcelLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\MerchantServiceAreaCharge;
use App\Models\MerchantServiceAreaReturnCharge;
use App\Models\WeightPackage;

class ParcelController extends Controller {

public function orderStatusUpdate(Request $request) {

        // =================================================================
        // STEP 1: PATHAO WEBHOOK VERIFICATION (সবচেয়ে গুরুত্বপূর্ণ অংশ)
        // =================================================================
        // Pathao যখন ইন্টিগ্রেশন চেক করবে, তখন বডিতে { "event": "webhook_integration" } পাঠাবে।
        // তখন আমাদের ঠিক 202 কোড এবং তাদের দেওয়া সিক্রেট কি (Secret Key) রিটার্ন করতে হবে।
        
        if ($request->isMethod('post') && $request->input('event') === 'webhook_integration') {
            
            return response()->json([
                'message' => 'Integration successful'
            ], 202) // শর্ত: Status code 202 হতে হবে
            ->header('X-Pathao-Merchant-Webhook-Integration-Secret', 'CHFyKqEfAovxp5ov6qLoXO7TEkirKw2kSpi8QTBW'); 
            // শর্ত: এই হেডার এবং ভ্যালু ঠিক এভাবেই থাকতে হবে।
        }

        // =================================================================
        // STEP 2: YOUR SECURITY CHECK (ভেরিফিকেশনের পরে)
        // =================================================================
        // আপনার সিস্টেমে যদি অন্য কোনো সোর্স থেকে রিকোয়েস্ট আসে, সেটার জন্য টোকেন চেক।
        // (Pathao এর রিয়েল অর্ডার আপডেটের সময় তারা এই টোকেন পাঠায় না, তাই এটি সাবধানে ব্যবহার করবেন)
        
        // if (!$request->hasHeader('token') || $request->header('token') != 'safedelivery') {
        //     return response()->json([
        //         'success' => 401,
        //         'message' => "Unauthorized Request",
        //     ], 401);
        // }

        // =================================================================
        // STEP 3: ORDER UPDATE LOGIC
        // =================================================================

        $validator = Validator::make($request->all(), [
            'order_reference'   => 'required',
            'status'            => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 403,
                'message' => "Validation Error.",
                'error'   => error_processor($validator),
            ], 403);
        }

        $order_reference    = $request->input('order_reference');
        $status             = $request->input('status');
        $parcel_status      = 1;
        $delivery_type      = null;

        switch($status){
            case "Picked" : $parcel_status = 11; break;
            case "Point Received" : $parcel_status = 14; break;
            case "Delivered" :
                $parcel_status = 25;
                $delivery_type = 1;
                break;
            case "Partial" :
                $parcel_status = 25;
                $delivery_type = 2;
                break;
            case "Return" :
                $parcel_status = 25;
                $delivery_type = 4;
                break;
            case "Hold" : // অনেক সময় হোল্ড স্ট্যাটাস আসে
                 $parcel_status = 1; 
                 break;
            default :
                $parcel_status = 1;
                $delivery_type = null;
                break;
        }

        $data = [
            'status'        => $parcel_status,
            'delivery_type' => $delivery_type,
        ];

        // পার্সেল আপডেট করা
        $check = Parcel::where('tracking_number', $order_reference)->update($data);

        if ($check) {
            $parcel = Parcel::where('tracking_number', $order_reference)->first();

            // লগ এন্ট্রি করা
            $logData = [
                'parcel_id'     => $parcel->id,
                'date'          => date('Y-m-d'),
                'time'          => date('H:i:s'),
                'status'        => $parcel_status,
                'delivery_type' => $parcel->delivery_type,
                'note'          => "Updated by Pathao Webhook: " . $status // বুঝার সুবিধার্থে নোট রাখা ভালো
            ];
            ParcelLog::create($logData);

            return response()->json([
                'success' => 200,
                'message' => "Parcel Information Updated Successfully",
            ], 200);
        }

        return response()->json([
            'success' => 404, // 401 এর বদলে 404 দেওয়া ভালো যদি পার্সেল না পাওয়া যায়
            'message' => "Parcel not found with this Tracking Number.",
        ], 404);
    }
    public function orderStatusUpdate2(Request $request) {

        if (!$request->hasHeader('token') || $request->header('token') != 'safedelivery') {
            return response()->json([
                'success' => 401,
                'message' => "Validation Error.",
                'error'   => "Unauthorized Request",
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'order_reference'   => 'required',
            'status'            => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 403,
                'message' => "Validation Error.",
                'error'   => error_processor($validator),
            ], 403);
        }

        $order_reference    = $request->input('order_reference');
        $status             = $request->input('status');
        $parcel_status      = 1;
        $delivery_type      = null;
        switch($status){
            case "Picked" : $parcel_status = 11; break;

            case "Point Received" : $parcel_status = 14; break;

            case "Delivered" :
                $parcel_status = 25;
                $delivery_type = 1;
                break;

            case "Partial" :
                $parcel_status = 25;
                $delivery_type = 2;
                break;

            case "Return" :
                $parcel_status = 25;
                $delivery_type = 4;
                break;

            default :
                $parcel_status = 1;
                $delivery_type = null;
                break;
        }
        $data = [
            'status'        => $parcel_status,
            'delivery_type' => $delivery_type,
        ];



        $check = Parcel::where('tracking_number', $order_reference)->update($data)  ? true : false;
        if ($check) {
            $parcel = Parcel::where('tracking_number', $order_reference)->first();

            $data = [
                'parcel_id'        => $parcel->id,
                'date'             => date('Y-m-d'),
                'time'             => date('H:i:s'),
                'status'           => $parcel_status,
                'delivery_type' => $parcel->delivery_type,
            ];
            ParcelLog::create($data);


            return response()->json([
                'success' => 200,
                'message' => "Update Parcel Information",
            ], 200);
        }

        return response()->json([
            'success' => 401,
            'message' => "Validation Error.",
            'error'   => "Order Tracking Number Invalid Request",
        ], 401);
    }


    public function parcelStart(Request $request) {
        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'parcel_id' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => 401,
                    'message' => "Validation Error",
                    'error'   => $validator->errors(),
                ], 401);
            } else {
                $data = [
                    'status'      => 1,
                    'parcel_date' => date('Y-m-d'),
                ];
                $parcel = Parcel::where('id', '=', $request->parcel_id)
                ->whereRaw("status = 2 or status = 4")
                ->update($data);
                if ($parcel) {
                    $data = [
                        'parcel_id'   => $request->parcel_id,
                        'merchant_id' => auth()->guard('merchant_api')->user()->id,
                        'date'        => date('Y-m-d'),
                        'time'        => date('H:i:s'),
                        'status'      => 1,
                        'delivery_type' => $parcel->delivery_type,
                    ];
                    ParcelLog::create($data);

                    return response()->json([
                        'success'   => 200,
                        'message'   => "Parcel Start Successfully",
                        'parcel_id' => $parcel->id,
                    ], 200);
                } else {
                    return response()->json([
                        'success' => 401,
                        'message' => "Parcel Start Unsuccessfully",
                        'message' => "Parcel Start Unsuccessfully",
                    ], 401);
                }
            }
        }

        return response()->json([
            'success' => 401,
            'message' => "Parcel Start Unsuccessfully",
            'error'   => "Parcel Start Unsuccessfully",
        ], 401);

    }


    public function parcelHold(Request $request) {
        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'parcel_id' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => 401,
                    'message' => "Validation Error",
                    'error'   => $validator->errors(),
                ], 401);
            } else {
                $data = [
                    'status'      => 2,
                    'parcel_date' => date('Y-m-d'),
                ];
                $parcel = Parcel::where('id', '=', $request->parcel_id)
                ->whereRaw("status = 1 or status = 4")
                ->update($data);
                if ($parcel) {
                    $data = [
                        'parcel_id'   => $request->parcel_id,
                        'merchant_id' => auth()->guard('merchant_api')->user()->id,
                        'date'        => date('Y-m-d'),
                        'time'        => date('H:i:s'),
                        'status'      => 2,
                        'delivery_type' => $parcel->delivery_type,
                    ];
                    ParcelLog::create($data);

                    return response()->json([
                        'success'   => 200,
                        'message'   => "Parcel Hold Successfully",
                        'parcel_id' => $parcel->id,
                    ], 200);
                } else {
                    return response()->json([
                        'success' => 401,
                        'message' => "Parcel Hold Unsuccessfully",
                        'message' => "Parcel Hold Unsuccessfully",
                    ], 401);
                }
            }
        }
        return response()->json([
            'success' => 401,
            'message' => "Parcel Hold Unsuccessfully",
            'error'   => "Parcel Hold Unsuccessfully",
        ], 401);
    }


    public function parcelCancel(Request $request) {
        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'parcel_id' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'success' => 401,
                    'message' => "Validation Error",
                    'error'   => $validator->errors(),
                ], 401);
            } else {
                $data = [
                    'status'      => 3,
                    'parcel_date' => date('Y-m-d'),
                ];
                $parcel = Parcel::where('id', '=', $request->parcel_id)
                ->whereRaw("status < 10")
                ->update($data);
                if ($parcel) {
                    $data = [
                        'parcel_id'   => $request->parcel_id,
                        'merchant_id' => auth()->guard('merchant_api')->user()->id,
                        'date'        => date('Y-m-d'),
                        'time'        => date('H:i:s'),
                        'status'      => 3,
                        'delivery_type' => $parcel->delivery_type,
                    ];
                    ParcelLog::create($data);

                    return response()->json([
                        'success'   => 200,
                        'message'   => "Parcel Cancel Successfully",
                        'parcel_id' => $parcel->id,
                    ], 200);
                } else {
                    return response()->json([
                        'success' => 401,
                        'message' => "Parcel Cancel Unsuccessfully",
                        'message' => "Parcel Cancel Unsuccessfully",
                    ], 401);
                }
            }
        }
        return response()->json([
            'success' => 401,
            'message' => "Parcel Hold Unsuccessfully",
            'error'   => "Parcel Hold Unsuccessfully",
        ], 401);
    }

    public function pathaoParcelStatus(Request $request)
    {
        try {
            //            dd($request->ip());
            $sendBoxData = [
                'ip' => $request->ip(),
                'body' => json_encode($request->all()),
                'header' => json_encode($request->header()),
            ];
            //            dd($sendBoxData);
            $sendBox = SendBox::create($sendBoxData);

            $signature = "619c5e3ba54f834fe42420f137cde8533ff63ca9";
            $x_pathao_signature = $request->header('x-pathao-signature');
            if ($x_pathao_signature == $signature) {
                $consignment_id = $request->input('consignment_id');
                $tracking_number = $request->input('tracking_number');
                $order_status = $request->input('order_status');
                $comments = $request->input('comments');
                $reason = $request->input('reason');
                $merchant_booking_id = $request->input('merchant_booking_id');
                $updated_at = $request->input('updated_at');

                $pathaoOrderDetail = PathaoOrderDetail::where('consignment_id', $consignment_id)->with('parcel', 'rider_run_detail')->first();
                if ($pathaoOrderDetail) {
                    //            dd($pathaoOrderDetail->rider_run_detail->rider_run->rider_id);
                    $riderRunDetail = $pathaoOrderDetail->rider_run_detail;
                    $riderRun = $pathaoOrderDetail->rider_run_detail->rider_run;
                    $parcel = $pathaoOrderDetail->parcel;
                    $rider_id = $pathaoOrderDetail->rider_run_detail->rider_run->rider_id;
                    $pathaoOrderDetail->update([
                        'complete_note' => $comments,
                    ]);
                    $parcel->update([
                        'note' => $comments . $reason,
                        'pathao_status' => $order_status,
                    ]);
                    PathaoOrderDetailLog::create([
                        'pathao_order_detail_id' => $pathaoOrderDetail->id,
                        'parcel_id' => $pathaoOrderDetail->parcel->id,
                        'status' => $order_status,
                        'note' => $comments . $reason,
                        'date' => $updated_at,
                    ]);
                    if ($order_status == "Picked") {
                        $riderRunDetail->update([
                            'status' => 4,
                        ]);
                        $parcel->update([
                            'status' => 19,
                            'parcel_date' => date('Y-m-d'),
                            'delivery_rider_date' => date('Y-m-d'),
                        ]);
                        ParcelLog::create([
                            'parcel_id' => $parcel->id,
                            'delivery_rider_id' => $rider_id,
                            'date' => date('Y-m-d'),
                            'time' => date('H:i:s'),
                            'status' => 19,
                            'delivery_type' => $parcel->delivery_type,
                        ]);

                        /* $message = "";
                         $message .= "Dear " . $parcel->customer_name . ", ";
                         $message .= "Your OTP " . $parcel->parcel_code . ". \n";
                         $message .= "Parcel from " . $parcel->merchant->company_name . " (TK " . $parcel->total_collect_amount . ")";
                         $message .= " will be delivered by " . $parcel->delivery_rider->name . ", " . $parcel->delivery_rider->contact_number . ".\n";
                         $message .= " Track here: " . route('frontend.orderTracking') . "?trackingBox=" . $parcel->parcel_invoice . "   \n- Foring Move";
                         send_bl_sms($parcel->customer_contact_number, $message);*/
                    } elseif ($order_status == "Pickup_Cancelled") {
                        $riderRunDetail->update([
                            'status' => 5,
                        ]);
                        $parcel->update([
                            'status' => 20,
                            'parcel_date' => date('Y-m-d'),
                            'delivery_rider_date' => date('Y-m-d'),
                        ]);
                        ParcelLog::create([
                            'parcel_id' => $request->parcel_id,
                            'delivery_rider_id' => $rider_id,
                            'date' => date('Y-m-d'),
                            'time' => date('H:i:s'),
                            'status' => 20,
                            'delivery_type' => $parcel->delivery_type,
                        ]);
                    } elseif ($order_status == "Delivered") {
                        //                dd($riderRun);
                        $riderRun->update([
                            'total_run_complete_parcel' => $riderRun->total_run_complete_parcel + 1,
                        ]);
                        $riderRunDetail->update([
                            'complete_note' => $comments,
                            'complete_date_time' => date('Y-m-d H:i:s'),
                            'status' => 7,
                        ]);

                        $parcel_update_data = [
                            'status' => 21,
                            'delivery_type' => 1,
                            'customer_collect_amount' => $parcel->total_collect_amount,
                            'parcel_date' => date('Y-m-d'),
                            'delivery_rider_date' => date('Y-m-d'),
                        ];

                        $parcel_log_update_data = [
                            'parcel_id' => $parcel->id,
                            'delivery_type' => 1,
                            'delivery_rider_id' => $rider_id,
                            'date' => date('Y-m-d'),
                            'time' => date('H:i:s'),
                            'note' => $comments,
                            'status' => 21,
                        ];

                        $parcel->update($parcel_update_data);
                        ParcelLog::create($parcel_log_update_data);

                        // $message = "Dear " . $parcel->customer_name . ", ";
                        // $message .= " Your parcel is successfully delivered. To Rate your experience visit https://www.facebook.com/ \n- Foring Move";
                        // $this->send_sms($parcel->customer_contact_number, $message);

                    } elseif ($order_status == "Partial_Delivery") {
                        //                dd($riderRun);
                        $riderRun->update([
                            'total_run_complete_parcel' => $riderRun->total_run_complete_parcel + 1,
                        ]);
                        $riderRunDetail->update([
                            'complete_note' => $comments . $reason,
                            'complete_date_time' => date('Y-m-d H:i:s'),
                            'status' => 7,
                        ]);

                        $parcel_update_data = [
                            'status' => 22,
                            'delivery_type' => 2,
                            'customer_collect_amount' => 0,
                            'parcel_date' => date('Y-m-d'),
                            'delivery_rider_date' => date('Y-m-d'),
                        ];

                        $parcel_log_update_data = [
                            'parcel_id' => $parcel->id,
                            'delivery_type' => 2,
                            'delivery_rider_id' => $rider_id,
                            'date' => date('Y-m-d'),
                            'time' => date('H:i:s'),
                            'note' => $comments,
                            'status' => 22,
                        ];

                        $parcel->update($parcel_update_data);
                        ParcelLog::create($parcel_log_update_data);

                        // $message = "Dear " . $parcel->customer_name . ", ";
                        // $message .= " Your parcel is successfully delivered. To Rate your experience visit https://www.facebook.com/ \n- Foring Move";
                        // $this->send_sms($parcel->customer_contact_number, $message);

                    } elseif ($order_status == "Return") {
                        //                dd($reason);
                        $riderRun->update([
                            'total_run_complete_parcel' => $riderRun->total_run_complete_parcel + 1,
                        ]);
                        $riderRunDetail->update([
                            'complete_note' => $comments . $reason,
                            'complete_date_time' => date('Y-m-d H:i:s'),
                            'status' => 7,
                        ]);
                        /*dd($riderRunDetail->update([
                            'complete_note' => $comments.$reason,
                            'complete_date_time' => date('Y-m-d H:i:s'),
                            'status' => 7,
                        ]));*/

                        $parcel_update_data = [
                            'status' => 24,
                            'delivery_type' => 4,
                            'customer_collect_amount' => 0,
                            'parcel_date' => date('Y-m-d'),
                            'delivery_rider_date' => date('Y-m-d'),
                        ];

                        $parcel_log_update_data = [
                            'parcel_id' => $parcel->id,
                            'delivery_type' => 4,
                            'delivery_rider_id' => $rider_id,
                            'date' => date('Y-m-d'),
                            'time' => date('H:i:s'),
                            'note' => $comments . $reason,
                            'status' => 24,
                        ];

                        $parcel->update($parcel_update_data);
                        ParcelLog::create($parcel_log_update_data);
                    }
                    $response = [
                        'success' => true,
                        "message" => "Status Updated"
                    ];
                    $code = 200;
                } else {
                    $response = [
                        'success' => false,
                        "message" => "Not Found"
                    ];
                    $code = 404;
                }
            } else {
                $response = [
                    'success' => false,
                    "message" => "Unauthorized! Invalied Signature"
                ];
                $code = 401;
            }

            $sendBox->update(['response' => json_encode($response)]);
            return response()->json($response, $code);
        } catch (Exception $exception) {
            $response = [
                'success' => false,
                "message" => $exception->getMessage()
            ];
            $code = 400;
            $sendBox->update(['response' => json_encode($response)]);
            return response()->json($response, $code);
        }
    }
}
