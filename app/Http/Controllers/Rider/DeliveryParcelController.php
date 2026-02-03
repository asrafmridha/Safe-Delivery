<?php

namespace App\Http\Controllers\Rider;

use App\Http\Controllers\Controller;
use App\Models\Merchant;
use App\Models\Parcel;
use App\Models\ParcelLog;
use App\Models\RiderRun;
use App\Models\RiderRunDetail;
use App\Notifications\MerchantParcelNotification;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DeliveryParcelController extends Controller
{

    public function deliveryParcelList()
    {
        $data = [];
        $data['main_menu'] = 'parcel';
        $data['child_menu'] = 'deliveryParcelList';
        $data['page_title'] = 'Delivery Parcel List';
        $data['collapse'] = 'sidebar-collapse';
        return view('rider.parcel.deliveryParcelList', $data);
    }

    public function getDeliveryParcelList(Request $request)
    {
        $rider_id = auth()->guard('rider')->user()->id;
        $model = Parcel::with(['merchant' => function ($query) {
            $query->select('id', 'name', 'company_name', 'contact_number', 'address');
        }])
            ->whereRaw('(delivery_rider_id = ? and status in (17,19)  )', [$rider_id])
//            ->whereRaw('(delivery_rider_id = ?)', [$rider_id])
            ->orderBy('id', "desc")
            ->select();

        return DataTables::of($model)
            ->addIndexColumn()
            ->editColumn('status', function ($data) {

                switch ($data->status) {
                    case 1 :
                        $status_name = "Pickup Request";
                        $class = "success";
                        break;
                    case 2 :
                        $status_name = "Parcel Hold";
                        $class = "warning";
                        break;
                    case 3 :
                        $status_name = "Parcel Cancel";
                        $class = "danger";
                        break;
                    case 4 :
                        $status_name = "Parcel Reschedule";
                        $class = "warning";
                        break;
                    case 5 :
                        $status_name = "Pickup Run Create";
                        $class = "success";
                        break;
                    case 6 :
                        $status_name = "Pickup Run Start";
                        $class = "success";
                        break;
                    case 7 :
                        $status_name = "Pickup Run Cancel";
                        $class = "warning";
                        break;
                    case 8 :
                        $status_name = "Pickup Rider Accept";
                        $class = "success";
                        break;
                    case 9 :
                        $status_name = "Pickup Rider Reject";
                        $class = "warning";
                        break;
                    case 10 :
                        $status_name = "Pickup Rider Complete";
                        $class = "success";
                        break;
                    case 11 :
                        $status_name = "Pickup Run Complete";
                        $class = "success";
                        break;
                    case 12 :
                        $status_name = "Delivery Branch Assign";
                        $class = "success";
                        break;
                    case 13 :
                        $status_name = "Pickup Branch Cancel Assign";
                        $class = "warning";
                        break;
                    case 14 :
                        $status_name = "Delivery Branch Received";
                        $class = "success";
                        break;
                    case 15 :
                        $status_name = "Delivery Branch Reject";
                        $class = "warning";
                        break;
                    case 16 :
                        $status_name = "Delivery Run Create";
                        $class = "success";
                        break;
                    case 17 :
                        $status_name = "Delivery Run Start";
                        $class = "success";
                        break;
                    case 18 :
                        $status_name = "Delivery Run Cancel";
                        $class = "warning";
                        break;
                    case 19 :
                        $status_name = "Delivery Rider Accept";
                        $class = "success";
                        break;
                    case 20 :
                        $status_name = "Delivery Rider Reject";
                        $class = "warning";
                        break;
                    case 21 :
                        $status_name = "Complete Delivery";
                        $class = "success";
                        break;
                    case 22 :
                        $status_name = "Partial Delivery";
                        $class = "success";
                        break;
                    case 23 :
                        $status_name = "Reschedule";
                        $class = "success";
                        break;
                    case 24 :
                        $status_name = "Parcel Return";
                        $class = "warning";
                        break;
                    case 25 :
                        $status_name = "Delivery Run Complete";
                        $class = "success";
                        break;
                    case 26 :
                        $status_name = "Delivery Return to Assign Branch";
                        $class = "success";
                        break;
                    case 27 :
                        $status_name = "Return Parcel Assign Branch Received";
                        $class = "success";
                        break;
                    case 28 :
                        $status_name = "Return Parcel Assign Branch Reject";
                        $class = "success";
                        break;
                    case 29 :
                        $status_name = "Return Parcel Assign Branch Assign Rider";
                        $class = "success";
                        break;
                    case 30 :
                        $status_name = "Return Parcel Assign Branch Assign Accept";
                        $class = "success";
                        break;
                    case 31 :
                        $status_name = "Return Parcel Assign Branch Assign Reject";
                        $class = "success";
                        break;
                    case 32 :
                        $status_name = "Return Parcel Assign Branch Assign Complete";
                        $class = "success";
                        break;
                    case 33 :
                        $status_name = "Return Parcel Assign Branch Complete";
                        $class = "success";
                        break;

                    default:
                        $status_name = "None";
                        $class = "success";
                        break;
                }

                return '<a class=" text-bold text-' . $class . '" href="javascript:void(0)" style="font-size:16px;"> ' . $status_name . '</a>';
            })
            ->editColumn("total_collect_amount", function ($data) {
                return number_format($data->total_collect_amount, 2);
            })
            ->addColumn('action', function ($data) {

                $button = '<button class="btn btn-secondary view-modal btn-sm" data-toggle="modal" data-target="#viewModal" parcel_id="' . $data->id . '" title=" View Delivery Parcel ">
                <i class="fa fa-eye"></i> </button>';

                switch ($data->status) {
                    case 17:
                        $button .= '&nbsp; <button class="btn btn-success btn-sm delivery-request-accept-btn" parcel_id="' . $data->id . '" title="Delivery Request Accept">
                            <i class="fa fa-check"></i> </button>';

                        $button .= '&nbsp; <button class="btn btn-danger btn-sm delivery-request-reject-btn" parcel_id="' . $data->id . '" title="Delivery Run Reject">
                            <i class="far fa-window-close"></i> </button>';
                        break;

                    case 19:
                        $button .= '&nbsp;&nbsp;&nbsp;
                        <button class="btn btn-success delivery-complete-btn btn-sm" data-toggle="modal" data-target="#viewModal" parcel_id="' . $data->id . '" title="Delivery Run Complete">
                        <i class="fa fa-check"></i> </button>';
                        break;

                    default:
                        $button = "";
                        break;
                }
                return $button;
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    public function deliveryCompleteParcelList()
    {
        $data = [];
        $data['main_menu'] = 'parcel';
        $data['child_menu'] = 'deliveryCompleteParcelList';
        $data['page_title'] = 'Delivery Complete Parcel List';
        $data['collapse'] = 'sidebar-collapse';
        return view('rider.parcel.deliveryCompleteParcelList', $data);
    }

    public function getDeliveryCompleteParcelList(Request $request)
    {
        $rider_id = auth()->guard('rider')->user()->id;
        $model = Parcel::with(['merchant' => function ($query) {
            $query->select('id', 'name', 'company_name', 'contact_number', 'address');
        }])
            ->whereRaw('(delivery_rider_id = ? and status in (21,22,25)  )', [$rider_id])
            ->orderBy('id', "desc")
            ->select();

        return DataTables::of($model)
            ->addIndexColumn()
            ->editColumn('status', function ($data) {

                switch ($data->status) {
                    case 1 :
                        $status_name = "Pickup Request";
                        $class = "success";
                        break;
                    case 2 :
                        $status_name = "Parcel Hold";
                        $class = "warning";
                        break;
                    case 3 :
                        $status_name = "Parcel Cancel";
                        $class = "danger";
                        break;
                    case 4 :
                        $status_name = "Parcel Reschedule";
                        $class = "warning";
                        break;
                    case 5 :
                        $status_name = "Pickup Run Create";
                        $class = "success";
                        break;
                    case 6 :
                        $status_name = "Pickup Run Start";
                        $class = "success";
                        break;
                    case 7 :
                        $status_name = "Pickup Run Cancel";
                        $class = "warning";
                        break;
                    case 8 :
                        $status_name = "Pickup Rider Accept";
                        $class = "success";
                        break;
                    case 9 :
                        $status_name = "Pickup Rider Reject";
                        $class = "warning";
                        break;
                    case 10 :
                        $status_name = "Pickup Rider Complete";
                        $class = "success";
                        break;
                    case 11 :
                        $status_name = "Pickup Run Complete";
                        $class = "success";
                        break;
                    case 12 :
                        $status_name = "Delivery Branch Assign";
                        $class = "success";
                        break;
                    case 13 :
                        $status_name = "Pickup Branch Cancel Assign";
                        $class = "warning";
                        break;
                    case 14 :
                        $status_name = "Delivery Branch Received";
                        $class = "success";
                        break;
                    case 15 :
                        $status_name = "Delivery Branch Reject";
                        $class = "warning";
                        break;
                    case 16 :
                        $status_name = "Delivery Run Create";
                        $class = "success";
                        break;
                    case 17 :
                        $status_name = "Delivery Run Start";
                        $class = "success";
                        break;
                    case 18 :
                        $status_name = "Delivery Run Cancel";
                        $class = "warning";
                        break;
                    case 19 :
                        $status_name = "Delivery Rider Accept";
                        $class = "success";
                        break;
                    case 20 :
                        $status_name = "Delivery Rider Reject";
                        $class = "warning";
                        break;
                    case 21 :
                        $status_name = "Complete Delivery";
                        $class = "success";
                        break;
                    case 22 :
                        $status_name = "Partial Delivery";
                        $class = "success";
                        break;
                    case 23 :
                        $status_name = "Reschedule";
                        $class = "success";
                        break;
                    case 24 :
                        $status_name = "Parcel Return";
                        $class = "warning";
                        break;
                    case 25 :
                        $status_name = "Delivery Run Complete";
                        $class = "success";
                        break;
                    case 26 :
                        $status_name = "Delivery Return to Assign Branch";
                        $class = "success";
                        break;
                    case 27 :
                        $status_name = "Return Parcel Assign Branch Received";
                        $class = "success";
                        break;
                    case 28 :
                        $status_name = "Return Parcel Assign Branch Reject";
                        $class = "success";
                        break;
                    case 29 :
                        $status_name = "Return Parcel Assign Branch Assign Rider";
                        $class = "success";
                        break;
                    case 30 :
                        $status_name = "Return Parcel Assign Branch Assign Accept";
                        $class = "success";
                        break;
                    case 31 :
                        $status_name = "Return Parcel Assign Branch Assign Reject";
                        $class = "success";
                        break;
                    case 32 :
                        $status_name = "Return Parcel Assign Branch Assign Complete";
                        $class = "success";
                        break;
                    case 33 :
                        $status_name = "Return Parcel Assign Branch Complete";
                        $class = "success";
                        break;

                    default:
                        $status_name = "None";
                        $class = "success";
                        break;
                }

                return '<a class=" text-bold text-' . $class . '" href="javascript:void(0)" style="font-size:16px;"> ' . $status_name . '</a>';
            })
            ->editColumn("total_collect_amount", function ($data) {
                return number_format($data->total_collect_amount, 2);
            })
            ->addColumn('action', function ($data) {

                $button = '<button class="btn btn-secondary view-modal btn-sm" data-toggle="modal" data-target="#viewModal" parcel_id="' . $data->id . '" title=" View Delivery Parcel ">
                <i class="fa fa-eye"></i> </button>';

                switch ($data->status) {
                    case 17:
                        $button .= '&nbsp; <button class="btn btn-success btn-sm delivery-request-accept-btn" parcel_id="' . $data->id . '" title="Delivery Request Accept">
                            <i class="fa fa-check"></i> </button>';

                        $button .= '&nbsp; <button class="btn btn-danger btn-sm delivery-request-reject-btn" parcel_id="' . $data->id . '" title="Delivery Run Reject">
                            <i class="far fa-window-close"></i> </button>';
                        break;

                    case 19:
                        $button .= '&nbsp;&nbsp;&nbsp;
                        <button class="btn btn-success delivery-complete-btn btn-sm" data-toggle="modal" data-target="#viewModal" parcel_id="' . $data->id . '" title="Delivery Run Complete">
                        <i class="fa fa-check"></i> </button>';
                        break;

                    default:
                        $button = "";
                        break;
                }
                return $button;
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    public function parcelDeliveryRequestAccept(Request $request)
    {
        $rider_id = auth()->guard('rider')->user()->id;
        $response = ['error' => 'Error Found'];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'parcel_id' => 'required',
            ]);

            if ($validator->fails()) {
                $response = ['error' => 'Error Found'];
            } else {

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
                        Parcel::where('id', $request->parcel_id)->update([
                            'status' => 19,
                            'parcel_date' => date('Y-m-d'),
                            'delivery_rider_date' => date('Y-m-d'),
                        ]);
                        $parcel=Parcel::where('id', $request->parcel_id)->first();
                        ParcelLog::create([
                            'parcel_id' => $request->parcel_id,
                            'delivery_rider_id' => auth()->guard('rider')->user()->id,
                            'date' => date('Y-m-d'),
                            'time' => date('H:i:s'),
                            'status' => 19,
                            'delivery_type' => $parcel->delivery_type,
                        ]);


                        $parcel = Parcel::where('id', $request->parcel_id)->first();
                        $message = "";
                        /*$message .= "Your OTP ".$parcel->parcel_code.", ";
                        $message .= "For  parcel ID No ".$parcel->parcel_invoice.".";
                        $message .= "Please rate your experience with us in our https://www.facebook.com/.com.bd.";*/
                        $message .= "Dear " . $parcel->customer_name . ", ";
                        $message .= "Your OTP " . $parcel->parcel_code . ". \n";
                        $message .= "Parcel from " . $parcel->merchant->company_name . " (TK " . $parcel->total_collect_amount . ")";
                        $message .= " will be delivered by " . $parcel->delivery_rider->name . ", " . $parcel->delivery_rider->contact_number . ".\n";
                        $message .= " Track here: " . route('frontend.orderTracking') . "?trackingBox=" . $parcel->parcel_invoice . "   \n- Eyecon Courier";
                        $this->send_sms($parcel->customer_contact_number, $message);
                        
                       // send_bl_sms($parcel->customer_contact_number,$message);

                        \DB::commit();

                        // $this->merchantDashboardCounterEvent($parcel->merchant_id);
                        // $this->adminDashboardCounterEvent();
                        $response = ['success' => 'Parcel Delivery Request Accept Successfully'];
                    } else {
                        $response = ['error' => 'Database Error Found'];
                    }
                } catch (\Exception $e) {
                    \DB::rollback();
                    $response = ['error' => 'Database Error Found'];
                }
            }
        }
        return response()->json($response);
    }

    public function parcelDeliveryRequestReject(Request $request)
    {
        $response = ['error' => 'Error Found'];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'parcel_id' => 'required',
            ]);
            if ($validator->fails()) {
                $response = ['error' => 'Error Found'];
            } else {
                \DB::beginTransaction();
                try {

                    $riderRunDetail = RiderRunDetail::where([
                        'parcel_id' => $request->parcel_id,
                        'status' => 2,
                    ])
                        ->whereHas('rider_run', function ($query) {
                            $query->where([
                                ['run_type', '=', 2],
                                ['status', '=', 2],
                            ]);
                        })
                        ->first();

                    if ($riderRunDetail) {
                        RiderRunDetail::where('id', $riderRunDetail->id)->update([
                            'status' => 5,
                        ]);

                        Parcel::where('id', $request->parcel_id)->update([
                            'status' => 20,
                            'parcel_date' => date('Y-m-d'),
                            'delivery_rider_date' => date('Y-m-d'),
                        ]);
                        $parcel=Parcel::where('id', $request->parcel_id)->first();
                        ParcelLog::create([
                            'parcel_id' => $request->parcel_id,
                            'delivery_rider_id' => auth()->guard('rider')->user()->id,
                            'date' => date('Y-m-d'),
                            'time' => date('H:i:s'),
                            'status' => 20,
                            'delivery_type' => $parcel->delivery_type,
                        ]);

                        \DB::commit();

                        $parcel = Parcel::where('id', $request->parcel_id)->first();

                        // $this->merchantDashboardCounterEvent($parcel->merchant_id);
                        // $this->adminDashboardCounterEvent();
                        $response = ['success' => 'Parcel Delivery Request Reject Successfully'];
                    } else {
                        $response = ['error' => 'Database Error Found'];
                    }
                } catch (\Exception $e) {
                    \DB::rollback();
                    $response = ['error' => 'Database Error Found'];
                }
            }
        }
        return response()->json($response);
    }

    public function parcelDeliveryComplete(Request $request, Parcel $parcel)
    {
        $parcel->load('district', 'upazila', 'area', 'merchant', 'weight_package');
        return view('rider.parcel.parcelDeliveryComplete', compact('parcel'));
    }

    public function returnConfirmParcelCode(Request $request)
    {
        $response = ['error' => 1];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'parcel_id' => 'required',
                'parcel_code' => 'required',
            ]);

            if ($validator->fails()) {
                $response = ['error' => 1];
            } else {

                $data = [
                    ['id', '=', $request->parcel_id],
                    ['parcel_code', '=', $request->parcel_code],
                    ['status', '=', 19],
                ];
                $parcel = Parcel::where($data)->first();

                if (!empty($parcel)) {
                    $response = ['success' => 1];
                } else {
                    $response = ['error' => 1];
                }
            }
        }
        return response()->json($response);
    }

    public function confirmParcelDeliveryComplete(Request $request)
    {
        $response = ['error' => 'Error Found'];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'parcel_id' => 'required',
                'delivery_type' => 'required',
                'customer_collect_amount' => 'sometimes',
                'total_collect_amount' => 'sometimes',
                'parcel_code' => 'sometimes',
                'reschedule_date' => 'sometimes',
                'note' => 'sometimes',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()]);
            } else {
                \DB::beginTransaction();
                try {
                    $riderRunDetail = RiderRunDetail::with('rider_run')
                        ->where([
                            'parcel_id' => $request->parcel_id,
                            'status' => 4,
                        ])
                        ->whereHas('rider_run', function ($query) {
                            $query->where([
                                ['run_type', '=', 2],
                                ['status', '=', 2],
                            ]);
                        })
                        ->first();

                    if ($riderRunDetail) {
                        $customer_collect_amount = $request->customer_collect_amount;
                        $total_collect_amount = $request->total_collect_amount;
                        $parcel_code = $request->parcel_code;
                        $reschedule_date = $request->reschedule_date;
                        $delivery_type = (int)$request->delivery_type;
                        $parcel_note = $request->parcel_note;
                        if ($delivery_type == 0
                            || is_null($delivery_type)
                            || !($delivery_type == 21 || $delivery_type == 22 || $delivery_type == 23 || $delivery_type == 24)
                        ) {
                            return response()->json([
                                'error' => "Delivery Type required",
                            ]);
                        }

                        if ($delivery_type == 21) {
                            $parcel = Parcel::where([
                                'id' => $request->parcel_id,
//                                'parcel_code' => $request->parcel_code,
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
//                                'parcel_code' => $request->parcel_code,
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
                                'error' => "Parcel Reschedule Date Required",
                            ]);
                        }

                        if ($delivery_type == 24 && is_null($parcel_note)) {
                            return response()->json([
                                'error' => "Parcel Return Note Required",
                            ]);
                        }

                        RiderRun::where('id', $riderRunDetail->rider_run_id)->update([
                            'total_run_complete_parcel' => $riderRunDetail->rider_run->total_run_complete_parcel + 1,
                        ]);

                        RiderRunDetail::where('id', $riderRunDetail->id)->update([
                            'complete_note' => $parcel_note,
                            'complete_date_time' => date('Y-m-d H:i:s'),
                            'status' => 7,
                        ]);

                        $parcel_update_data = [
                            'status' => $delivery_type,
                            'parcel_date' => date('Y-m-d'),
                            'delivery_rider_date' => date('Y-m-d'),
                        ];

                        $parcel_log_update_data = [
                            'parcel_id' => $request->parcel_id,
                            'delivery_rider_id' => auth()->guard('rider')->user()->id,
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
                        $parcel=Parcel::where('id', $request->parcel_id)->first();
                        $parcel_log_update_data['delivery_type'] = $parcel->delivery_type;
                        $parcel_log_update_data['note'] = $parcel_note;
                        ParcelLog::create($parcel_log_update_data);


                        if ($delivery_type == 22 || $delivery_type == 21) {
                            $message = "Dear " . $parcel->customer_name . ", ";
                            $message .= " Your parcel is successfully delivered. To Rate your experience visit https://www.facebook.com/eyeconstar \n- Eyecon Courier";
                          //  $this->send_sms($parcel->customer_contact_number, $message);
                        }

                        \DB::commit();

                        $parcel = Parcel::where('id', $request->parcel_id)->first();

                        // $merchant_user = Merchant::where('id', $parcel->merchant_id)->first();
                        // $merchant_user->notify(new MerchantParcelNotification($parcel));

                        // $this->merchantDashboardCounterEvent($parcel->merchant_id);

                        // $this->branchDashboardCounterEvent($parcel->delivery_branch_id);

                        // $this->adminDashboardCounterEvent();
                        $response = ['success' => 'Parcel Delivery Complete Successfully'];
                    } else {
                        $response = ['error' => 'Database Error Found'];
                    }
                } catch (\Exception $e) {
                    \DB::rollback();
                    $response = ['error' => 'Database Error Found'];
                }
            }
        }
        return response()->json($response);
    }


}
