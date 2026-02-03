<?php

namespace App\Http\Controllers\Rider;

use App\Http\Controllers\Controller;
use App\Models\Parcel;
use App\Models\ParcelLog;
use App\Models\RiderRun;
use App\Models\RiderRunDetail;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ParcelController extends Controller {

    public function viewParcel(Request $request, Parcel $parcel) {
        $parcel->load('district', 'upazila', 'area', 'merchant', 'weight_package', 'pickup_branch', 'pickup_rider', 'delivery_branch', 'delivery_rider');
        return view('rider.parcel.viewParcel', compact('parcel'));
    }

    public function pickupParcelList() {
        $data               = [];
        $data['main_menu']  = 'parcel';
        $data['child_menu'] = 'pickupParcelList';
        $data['page_title'] = 'Pickup Parcel List';
        $data['collapse']   = 'sidebar-collapse';
        return view('rider.parcel.pickupParcelList', $data);
    }

    public function getPickupParcelList(Request $request) {
        $rider_id = auth()->guard('rider')->user()->id;
        $model    = Parcel::with(['merchant' => function ($query) {
            $query->select('id', 'name', 'company_name', 'contact_number', 'address');
        },
        ])
        ->whereRaw('(pickup_rider_id = ? and status in (6, 8) )', [$rider_id])
        ->select(
            'id','parcel_invoice', 'customer_name', 'customer_contact_number', 'total_collect_amount', 'total_charge', 'district_id', 'merchant_id', 'status'
        );

        return DataTables::of($model)
            ->addIndexColumn()
            ->editColumn('status', function ($data) {
                switch ($data->status) {
                    case 1 : $status_name  = "Pickup Request"; $class  = "success";break;
                    case 2 : $status_name  = "Parcel Hold"; $class  = "warning";break;
                    case 3 : $status_name  = "Parcel Cancel"; $class  = "danger";break;
                    case 4 : $status_name  = "Parcel Reschedule"; $class  = "warning";break;
                    case 5 : $status_name  = "Pickup Run Create"; $class  = "success";break;
                    case 6 : $status_name  = "Pickup Run Start"; $class  = "success";break;
                    case 7 : $status_name  = "Pickup Run Cancel"; $class  = "warning";break;
                    case 8 : $status_name  = "Pickup Rider Accept"; $class  = "success";break;
                    case 9 : $status_name  = "Pickup Rider Reject"; $class  = "warning";break;
                    case 10 : $status_name  = "Pickup Rider Complete"; $class  = "success";break;
                    case 11 : $status_name  = "Pickup Run Complete"; $class  = "success";break;
                    case 12 : $status_name  = "Delivery Branch Assign"; $class  = "success";break;
                    case 13 : $status_name  = "Pickup Branch Cancel Assign"; $class  = "warning";break;
                    case 14 : $status_name  = "Delivery Branch Received"; $class  = "success";break;
                    case 15 : $status_name  = "Delivery Branch Reject"; $class  = "warning";break;
                    case 16 : $status_name  = "Delivery Run Create"; $class  = "success";break;
                    case 17 : $status_name  = "Delivery Run Start"; $class  = "success";break;
                    case 18 : $status_name  = "Delivery Run Cancel"; $class  = "warning";break;
                    case 19 : $status_name  = "Delivery Rider Accept"; $class  = "success";break;
                    case 20 : $status_name  = "Delivery Rider Reject"; $class  = "warning";break;
                    case 21 : $status_name  = "Complete Delivery"; $class  = "success";break;
                    case 22 : $status_name  = "Partial Delivery"; $class  = "success";break;
                    case 23 : $status_name  = "Reschedule"; $class  = "success";break;
                    case 24 : $status_name  = "Parcel Return"; $class  = "warning";break;
                    case 25 : $status_name  = "Delivery Run Complete"; $class  = "success";break;
                    case 26 : $status_name  = "Delivery Return to Assign Branch"; $class  = "success";break;
                    case 27 : $status_name  = "Return Parcel Assign Branch Received"; $class  = "success";break;
                    case 28 : $status_name  = "Return Parcel Assign Branch Reject"; $class  = "success";break;
                    case 29 : $status_name  = "Return Parcel Assign Branch Assign Rider"; $class  = "success";break;
                    case 30 : $status_name  = "Return Parcel Assign Branch Assign Accept"; $class  = "success";break;
                    case 31 : $status_name  = "Return Parcel Assign Branch Assign Reject"; $class  = "success";break;
                    case 32 : $status_name  = "Return Parcel Assign Branch Assign Complete"; $class  = "success";break;
                    case 33 : $status_name  = "Return Parcel Assign Branch Complete"; $class  = "success";break;

                    default:$status_name = "None"; $class = "success";break;
                }
                return '<a class=" text-bold text-' . $class . '" href="javascript:void(0)" style="font-size:16px;"> ' . $status_name . '</a>';
            })
            ->addColumn('action', function ($data) {
                $button = '<button class="btn btn-secondary view-modal btn-sm" data-toggle="modal" data-target="#viewModal" parcel_id="' . $data->id . '}" title=" View Pickup Parcel ">
                <i class="fa fa-eye"></i> </button>';

                switch ($data->status) {
                case 6:
                    $button .= '&nbsp; <button class="btn btn-success btn-sm pickup-request-accept-btn" parcel_id="' . $data->id . '" title="Pickup Request Accept">
                            <i class="fa fa-check"></i> </button>';

                    $button .= '&nbsp; <button class="btn btn-danger btn-sm pickup-request-reject-btn" parcel_id="' . $data->id . '" title="Pickup Run Reject">
                            <i class="far fa-window-close"></i> </button>';

                    $button .= '&nbsp;&nbsp;&nbsp;
                        <button class="btn btn-success pickup-reschedule-btn btn-sm" data-toggle="modal" data-target="#viewModal" parcel_id="' . $data->id . '}" title="Pickup Run Reschedule">
                        <i class="fa fa-clock"></i> </button>';
                    break;

                case 8:
                    $button .= '&nbsp;&nbsp;&nbsp;
                        <button class="btn btn-success pickup-complete-btn btn-sm" data-toggle="modal" data-target="#viewModal" parcel_id="' . $data->id . '}" title="Pickup Run Complete">
                        <i class="fa fa-check"></i> </button>';
                    $button .= '&nbsp;&nbsp;&nbsp;
                        <button class="btn btn-success pickup-reschedule-btn btn-sm" data-toggle="modal" data-target="#viewModal" parcel_id="' . $data->id . '}" title="Pickup Run Reschedule">
                        <i class="fa fa-clock"></i> </button>';
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

    public function parcelPickupRequestAccept(Request $request) {
        $response = ['error' => 'Error Found'];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'parcel_id' => 'required',
            ]);

            if ($validator->fails()) {
                $response = ['error' => 'Error Found'];
            }
            else {
                $riderRunDetail = RiderRunDetail::where([
                    'status'    => 2,
                    'parcel_id' => $request->parcel_id,
                ])
                    ->whereHas('rider_run', function ($query) {
                        $query->where([
                            ['run_type', '=', 1],
                            ['status', '=', 2],
                        ]);
                    })
                    ->first();

                if ($riderRunDetail) {

                    RiderRunDetail::where('id', $riderRunDetail->id)->update([
                        'status' => 4,
                    ]);

                    Parcel::where('id', $request->parcel_id)->update([
                        'status'            => 8,
                        'parcel_date'       => date('Y-m-d'),
                        'pickup_rider_date' => date('Y-m-d'),
                    ]);
                    $parcel=Parcel::where('id', $request->parcel_id)->first();
                    ParcelLog::create([
                        'parcel_id'       => $request->parcel_id,
                        'pickup_rider_id' => auth()->guard('rider')->user()->id,
                        'date'            => date('Y-m-d'),
                        'time'            => date('H:i:s'),
                        'status'          => 8,
                        'delivery_type' => $parcel->delivery_type,
                    ]);

                    // $this->adminDashboardCounterEvent();

                    $response = ['success' => 'Parcel Pickup Request Accept Successfully'];
                } else {
                    $response = ['error' => 'Database Error Found'];
                }
            }
        }
        return response()->json($response);
    }

    public function parcelPickupRequestReject(Request $request) {
        $response = ['error' => 'Error Found'];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'parcel_id' => 'required',
            ]);

            if ($validator->fails()) {
                $response = ['error' => 'Error Found'];
            }
            else {
                $riderRunDetail = RiderRunDetail::where([
                    'parcel_id' => $request->parcel_id,
                    'status'    => 2,
                ])
                ->whereHas('rider_run', function ($query) {
                    $query->where([
                        ['run_type', '=', 1],
                        ['status', '=', 2],
                    ]);
                })
                ->first();

                if ($riderRunDetail) {
                    RiderRunDetail::where('id', $riderRunDetail->id)->update([
                        'status' => 5,
                    ]);

                    Parcel::where('id', $request->parcel_id)->update([
                        'status'            => 9,
                        'parcel_date'       => date('Y-m-d'),
                        'pickup_rider_date' => date('Y-m-d'),
                    ]);
                    $parcel=Parcel::where('id', $request->parcel_id)->first();
                    ParcelLog::create([
                        'parcel_id'       => $request->parcel_id,
                        'pickup_rider_id' => auth()->guard('rider')->user()->id,
                        'date'            => date('Y-m-d'),
                        'time'            => date('H:i:s'),
                        'status'          => 9,
                        'delivery_type' => $parcel->delivery_type,
                    ]);

                    // $this->adminDashboardCounterEvent();
                    $response = ['success' => 'Parcel Pickup Request Reject Successfully'];
                } else{
                    $response = ['error' => 'Database Error Found'];
                }

            }
        }
        return response()->json($response);
    }


    public function parcelPickupReschedule(Request $request, Parcel $parcel) {
        $parcel->load('district', 'upazila', 'area', 'merchant', 'weight_package', 'pickup_branch', 'pickup_rider', 'delivery_branch', 'delivery_rider');
        return view('rider.parcel.parcelPickupReschedule', compact('parcel'));
    }

    public function confirmParcelPickupReschedule(Request $request) {
        $response = ['error' => 'Error Found'];
        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'note'      => 'sometimes',
                'parcel_id' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()]);
            } else {
                $riderRunDetail = RiderRunDetail::with('rider_run')
                    ->where([
                        'parcel_id' => $request->parcel_id,
                        'status'    => 4,
                    ])
                    ->whereHas('rider_run', function ($query) {
                        $query->where([
                            ['run_type', '=', 1],
                            ['status', '=', 2],
                        ]);
                    })
                    ->first();

                if ($riderRunDetail) {

                    RiderRunDetail::where('id', $riderRunDetail->id)->update([
                        'complete_note'      => $request->note,
                        'complete_date_time' => date('Y-m-d H:i:s'),
                        'status'             => 6,
                    ]);

                    Parcel::where('id', $request->parcel_id)->update([
                        'parcel_note'       => $request->note,
                        'status'            => 4,
                        'parcel_date'       => date('Y-m-d'),
                        'pickup_rider_date' => date('Y-m-d'),
                    ]);
                    $parcel=Parcel::where('id', $request->parcel_id)->first();
                    ParcelLog::create([
                        'note'            => $request->note,
                        'parcel_id'       => $request->parcel_id,
                        'pickup_rider_id' => auth()->guard('rider')->user()->id,
                        'date'            => date('Y-m-d'),
                        'time'            => date('H:i:s'),
                        'status'          => 4,
                        'delivery_type' => $parcel->delivery_type,
                    ]);

                    // $this->adminDashboardCounterEvent();

                    $response = ['success' => 'Parcel Pickup Complete Successfully'];
                } else {
                    $response = ['error' => 'Database Error Found'];
                }
            }
        }
        return response()->json($response);
    }



    public function parcelPickupComplete(Request $request, Parcel $parcel) {
        $parcel->load('district', 'upazila', 'area', 'merchant', 'weight_package', 'pickup_branch', 'pickup_rider', 'delivery_branch', 'delivery_rider');
        return view('rider.parcel.parcelPickupComplete', compact('parcel'));
    }

    public function confirmParcelPickupComplete(Request $request) {
        $response = ['error' => 'Error Found'];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'note'      => 'sometimes',
                'parcel_id' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()]);
            } else {
                $riderRunDetail = RiderRunDetail::with('rider_run')
                    ->where([
                        'parcel_id' => $request->parcel_id,
                        'status'    => 4,
                    ])
                    ->whereHas('rider_run', function ($query) {
                        $query->where([
                            ['run_type', '=', 1],
                            ['status', '=', 2],
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

                    Parcel::where('id', $request->parcel_id)->update([
                        'status'            => 10,
                        'parcel_date'       => date('Y-m-d'),
                        'pickup_rider_date' => date('Y-m-d'),
                    ]);
                    $parcel=Parcel::where('id', $request->parcel_id)->first();
                    ParcelLog::create([
                        'parcel_id'       => $request->parcel_id,
                        'pickup_rider_id' => auth()->guard('rider')->user()->id,
                        'date'            => date('Y-m-d'),
                        'time'            => date('H:i:s'),
                        'status'          => 10,
                        'delivery_type' => $parcel->delivery_type,
                    ]);

                    // $this->adminDashboardCounterEvent();

                    $response = ['success' => 'Parcel Pickup Complete Successfully'];
                } else {
                    $response = ['error' => 'Database Error Found'];
                }
            }
        }
        return response()->json($response);
    }





    public function deliveryParcelList() {
        $data               = [];
        $data['main_menu']  = 'parcel';
        $data['child_menu'] = 'deliveryParcelList';
        $data['page_title'] = 'Delivery Parcel List';
        $data['collapse']   = 'sidebar-collapse';
        return view('rider.parcel.deliveryParcelList', $data);
    }

    public function getDeliveryParcelList(Request $request) {
        $rider_id = auth()->guard('rider')->user()->id;
        $model    = Parcel::with(['merchant' => function ($query) {
            $query->select('id', 'name');
        },
        ])
            ->whereRaw('(delivery_rider_id = ? and status in (17,19) )', [$rider_id])
            ->select();

        return DataTables::of($model)
            ->addIndexColumn()
            ->editColumn('status', function ($data) {

                switch ($data->status) {
                    case 1 : $status_name  = "Pickup Request"; $class  = "success";break;
                    case 2 : $status_name  = "Parcel Hold"; $class  = "warning";break;
                    case 3 : $status_name  = "Parcel Cancel"; $class  = "danger";break;
                    case 4 : $status_name  = "Parcel Reschedule"; $class  = "warning";break;
                    case 5 : $status_name  = "Pickup Run Create"; $class  = "success";break;
                    case 6 : $status_name  = "Pickup Run Start"; $class  = "success";break;
                    case 7 : $status_name  = "Pickup Run Cancel"; $class  = "warning";break;
                    case 8 : $status_name  = "Pickup Rider Accept"; $class  = "success";break;
                    case 9 : $status_name  = "Pickup Rider Reject"; $class  = "warning";break;
                    case 10 : $status_name  = "Pickup Rider Complete"; $class  = "success";break;
                    case 11 : $status_name  = "Pickup Run Complete"; $class  = "success";break;
                    case 12 : $status_name  = "Delivery Branch Assign"; $class  = "success";break;
                    case 13 : $status_name  = "Pickup Branch Cancel Assign"; $class  = "warning";break;
                    case 14 : $status_name  = "Delivery Branch Received"; $class  = "success";break;
                    case 15 : $status_name  = "Delivery Branch Reject"; $class  = "warning";break;
                    case 16 : $status_name  = "Delivery Run Create"; $class  = "success";break;
                    case 17 : $status_name  = "Delivery Run Start"; $class  = "success";break;
                    case 18 : $status_name  = "Delivery Run Cancel"; $class  = "warning";break;
                    case 19 : $status_name  = "Delivery Rider Accept"; $class  = "success";break;
                    case 20 : $status_name  = "Delivery Rider Reject"; $class  = "warning";break;
                    case 21 : $status_name  = "Complete Delivery"; $class  = "success";break;
                    case 22 : $status_name  = "Partial Delivery"; $class  = "success";break;
                    case 23 : $status_name  = "Reschedule"; $class  = "success";break;
                    case 24 : $status_name  = "Parcel Return"; $class  = "warning";break;
                    case 25 : $status_name  = "Delivery Run Complete"; $class  = "success";break;
                    case 26 : $status_name  = "Delivery Return to Assign Branch"; $class  = "success";break;
                    case 27 : $status_name  = "Return Parcel Assign Branch Received"; $class  = "success";break;
                    case 28 : $status_name  = "Return Parcel Assign Branch Reject"; $class  = "success";break;
                    case 29 : $status_name  = "Return Parcel Assign Branch Assign Rider"; $class  = "success";break;
                    case 30 : $status_name  = "Return Parcel Assign Branch Assign Accept"; $class  = "success";break;
                    case 31 : $status_name  = "Return Parcel Assign Branch Assign Reject"; $class  = "success";break;
                    case 32 : $status_name  = "Return Parcel Assign Branch Assign Complete"; $class  = "success";break;
                    case 33 : $status_name  = "Return Parcel Assign Branch Complete"; $class  = "success";break;

                    default:$status_name = "None"; $class = "success";break;
                }

                return '<a class=" text-bold text-' . $class . '" href="javascript:void(0)" style="font-size:16px;"> ' . $status_name . '</a>';
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

    public function parcelDeliveryRequestAccept(Request $request) {
        $response = ['error' => 'Error Found'];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'parcel_id' => 'required',
            ]);

            if ($validator->fails()) {
                $response = ['error' => 'Error Found'];
            } else {
                $riderRunDetail = RiderRunDetail::where([
                    'status'    => 2,
                    'parcel_id' => $request->parcel_id,
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
                        'status' => 4,
                    ]);

                    Parcel::where('id', $request->parcel_id)->update([
                        'status'              => 19,
                        'parcel_date'         => date('Y-m-d'),
                        'delivery_rider_date' => date('Y-m-d'),
                    ]);
                    $parcel=Parcel::where('id', $request->parcel_id)->first();
                    ParcelLog::create([
                        'parcel_id'         => $request->parcel_id,
                        'delivery_rider_id' => auth()->guard('rider')->user()->id,
                        'date'              => date('Y-m-d'),
                        'time'              => date('H:i:s'),
                        'status'            => 19,
                        'delivery_type' => $parcel->delivery_type,
                    ]);

                    // $this->adminDashboardCounterEvent();
                    $response = ['success' => 'Parcel Delivery Request Accept Successfully'];
                } else {
                    $response = ['error' => 'Database Error Found'];
                }
            }
        }
        return response()->json($response);
    }

    public function parcelDeliveryRequestReject(Request $request) {
        $response = ['error' => 'Error Found'];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'parcel_id' => 'required',
            ]);
            if ($validator->fails()) {
                $response = ['error' => 'Error Found'];
            }
            else {
                $riderRunDetail = RiderRunDetail::where([
                        'parcel_id' => $request->parcel_id,
                        'status'    => 2,
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
                        'status'              => 20,
                        'parcel_date'         => date('Y-m-d'),
                        'delivery_rider_date' => date('Y-m-d'),
                    ]);
                    $parcel=Parcel::where('id', $request->parcel_id)->first();
                    ParcelLog::create([
                        'parcel_id'         => $request->parcel_id,
                        'delivery_rider_id' => auth()->guard('rider')->user()->id,
                        'date'              => date('Y-m-d'),
                        'time'              => date('H:i:s'),
                        'status'            => 20,
                        'delivery_type' => $parcel->delivery_type,
                    ]);

                    // $this->adminDashboardCounterEvent();
                    $response = ['success' => 'Parcel Delivery Request Reject Successfully'];
                } else {
                    $response = ['error' => 'Database Error Found'];
                }
            }
        }
        return response()->json($response);
    }

    public function parcelDeliveryComplete(Request $request, Parcel $parcel) {
        $parcel->load('district', 'upazila', 'area', 'merchant', 'weight_package');
        return view('rider.parcel.parcelDeliveryComplete', compact('parcel'));
    }

    public function returnConfirmParcelCode(Request $request) {
        $response = ['error' => 1];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'parcel_id'   => 'required',
                'parcel_code' => 'required',
            ]);

            if ($validator->fails()) {
                $response = ['error' => 1];
            } else {
                $data = [
                    ['id', '=', $request->parcel_id],
                    ['parcel_code', '=', $request->parcel_code],
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

    public function confirmParcelDeliveryComplete(Request $request) {
        $response = ['error' => 'Error Found'];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'parcel_id'               => 'required',
                'delivery_type'           => 'sometimes',
                'customer_collect_amount' => 'sometimes',
                'total_collect_amount'    => 'sometimes',
                'parcel_code'             => 'sometimes',
                'reschedule_date'         => 'sometimes',
                'parcel_note'             => 'sometimes',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()]);
            } else {
                $riderRunDetail = RiderRunDetail::with('rider_run')
                    ->where([
                        'parcel_id' => $request->parcel_id,
                        'status'    => 4,
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
                    $total_collect_amount    = $request->total_collect_amount;
                    $parcel_code             = $request->parcel_code;
                    $reschedule_date         = $request->reschedule_date;
                    $delivery_type           = $request->delivery_type;
                    $parcel_note             = $request->parcel_note;

                    RiderRun::where('id', $riderRunDetail->rider_run_id)->update([
                        'total_run_complete_parcel' => $riderRunDetail->rider_run->total_run_complete_parcel + 1,
                    ]);

                    RiderRunDetail::where('id', $riderRunDetail->id)->update([
                        'complete_note'      => $parcel_note,
                        'complete_date_time' => date('Y-m-d H:i:s'),
                        'status'             => 7,
                    ]);

                    $parcel_update_data = [
                        'status'                => $delivery_type,
                        'parcel_date'           => date('Y-m-d'),
                        'delivery_rider_date'   => date('Y-m-d'),
                    ];
                    $parcel_log_update_data = [
                        'parcel_id'             => $request->parcel_id,
                        'delivery_rider_id'     => auth()->guard('rider')->user()->id,
                        'date'                  => date('Y-m-d'),
                        'time'                  => date('H:i:s'),
                        'status'                => $delivery_type,
                    ];

                    // Delivery Parcel Complete
                    if($delivery_type == '21'){
                        $parcel_update_data['delivery_type']       = 1;
                        $parcel_update_data['customer_collect_amount']       = $customer_collect_amount;
                    }
                    // Delivery Parcel Partial Delivery
                    elseif($delivery_type == '22'){
                        $parcel_update_data['delivery_type']       = 2;
                        $parcel_update_data['customer_collect_amount']       = $customer_collect_amount ?? 0;
                        $parcel_update_data['parcel_note']                   = $parcel_note;
                    }
                    // Delivery Parcel Reschedule
                    elseif($delivery_type == '23'){
                        $parcel_update_data['delivery_type']       = 3;
                        $parcel_update_data['reschedule_parcel_date']       = $reschedule_date;
                        $parcel_update_data['parcel_note']                  = $parcel_note;
                        $parcel_log_update_data['reschedule_parcel_date']   = $reschedule_date;
                    }
                    // Delivery Parcel Return
                    elseif($delivery_type == '24'){
                        $parcel_update_data['delivery_type']       = 4;
                        $parcel_update_data['parcel_note']         = $parcel_note;
                    }

                    Parcel::where('id', $request->parcel_id)->update($parcel_update_data);
                    $parcel=Parcel::where('id', $request->parcel_id)->first();
                    $parcel_log_update_data['delivery_type'] = $parcel->delivery_type;
                    ParcelLog::create($parcel_log_update_data);


                    // $this->adminDashboardCounterEvent();

                    $response = ['success' => 'Parcel Delivery Complete Successfully'];
                }
                else {
                    $response = ['error' => 'Database Error Found'];
                }
            }
        }
        return response()->json($response);
    }


    public function list() {
        $data               = [];
        $data['main_menu']  = 'parcel';
        $data['child_menu'] = 'parcelList';
        $data['page_title'] = 'Parcel List';
        $data['collapse']   = 'sidebar-collapse';
        return view('rider.parcel.parcelList', $data);
    }

    public function getParcelList(Request $request) {

        $rider_id = auth()->guard('rider')->user()->id;

        $model = Parcel::with(['district', 'upazila', 'area'])
            ->whereRaw('(pickup_rider_id = ? and status in (4, 6)) or (delivery_rider_id = ? and status in (10,11,12,13))', [$rider_id, $rider_id])
            ->select();

        return DataTables::of($model)
            ->addIndexColumn()
            ->editColumn('status', function ($data) {

                switch ($data->status) {
                case 1:$status_name  = "Pickup Request"; $class  = "success";break;
                case 2:$status_name  = "Pickup Branch Request Accept"; $class  = "success";break;
                case 3:$status_name  = "Pickup Rider Assign"; $class  = "success";break;
                case 4:$status_name  = "Pickup Rider Request Accept"; $class  = "success";break;
                case 5:$status_name  = "Pickup Rider Pick Parcel"; $class  = "success";break;
                case 6:$status_name  = "Pickup Branch Received Parcel"; $class  = "success";break;
                case 7:$status_name  = "Pickup Branch Assign Delivery Branch"; $class  = "success";break;
                case 8:$status_name  = "Delivery Branch Received"; $class  = "success";break;
                case 9:$status_name  = "Delivery Branch Reject"; $class  = "success";break;
                case 10:$status_name = "Delivery Branch Assign Rider"; $class = "success";break;
                case 11:$status_name = "Delivery  Rider Accept"; $class = "success";break;
                case 12:$status_name = "Delivery Rider Complete"; $class = "success";break;
                case 12:$status_name = "Delivery Rider Reschedule"; $class = "success";break;
                default:$status_name = "None"; $class = "success";break;
                }

                return '<a class=" text-bold text-' . $class . '" href="javascript:void(0)" style="font-size:16px;"> ' . $status_name . '</a>';
            })
            ->addColumn('action', function ($data) {

                $button = '<button class="btn btn-secondary view-modal btn-sm" data-toggle="modal" data-target="#viewModal" parcel_id="' . $data->id . '}" title=" View Pickup Parcel ">
                <i class="fa fa-eye"></i> </button>';

                switch ($data->status) {
                case 4:
                    $button .= '&nbsp; <button class="btn btn-success btn-sm pickup-request-accept-btn" parcel_id="' . $data->id . '" title="Pickup Request Accept">
                            <i class="fa fa-check"></i> </button>';

                    $button .= '&nbsp;&nbsp;&nbsp; <button class="btn btn-info btn-sm pickup-confirm-btn" parcel_id="' . $data->id . '" title="Pickup Confirm">
                            <i class="fa fa-check"></i> </button>';
                    break;

                case 10:
                    $button .= '&nbsp;&nbsp;&nbsp; <button class="btn btn-success btn-sm delivery-request-accept-btn" parcel_id="' . $data->id . '" title="Delivery Request Accept">
                                <i class="fa fa-check"></i> </button>';
                    break;

                case 11:
                    $button .= '<button class="btn btn-secondary processParcel btn-sm" data-toggle="modal" data-target="#viewModal" parcel_id="' . $data->id . '}"title=" Process Parcel " >
                                <i class="fas fa-share-square"></i> </button>';
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

    public function parcelPickupConfirm(Request $request) {
        $response = ['error' => 'Error Found'];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'parcel_id' => 'required',
            ]);

            if ($validator->fails()) {
                $response = ['error' => 'Error Found'];
            } else {
                $riderRunDetail = RiderRunDetail::with('rider_run')->where([
                    'parcel_id' => $request->parcel_id,
                ])
                    ->whereHas('rider_run', function ($query) {
                        $query->where([
                            ['run_type', '=', 1],
                            ['status', '=', 1],
                        ]);
                    })
                    ->first();

                if ($riderRunDetail) {

                    $data = [
                        'status'            => 5,
                        'parcel_date'       => date('Y-m-d'),
                        'pickup_rider_date' => date('Y-m-d'),
                    ];
                    $parcel = Parcel::where('id', $request->parcel_id)->update($data);
                    $parcel=Parcel::where('id', $request->parcel_id)->first();
                    if ($parcel) {
                        $data = [
                            'parcel_id'       => $request->parcel_id,
                            'pickup_rider_id' => auth()->guard('rider')->user()->id,
                            'date'            => date('Y-m-d'),
                            'time'            => date('H:i:s'),
                            'status'          => 5,
                            'delivery_type' => $parcel->delivery_type,
                        ];
                        ParcelLog::create($data);

                        RiderRunDetail::where('id', $riderRunDetail->id)->update([
                            'status'            => 1,
                            'run_complete_date' => date('Y-m-d'),
                            'run_complete_time' => date('H:i:s'),
                        ]);

                        RiderRun::where('id', $riderRunDetail->rider_run->id)->update([
                            'total_run_complete_parcel' => 1 + $riderRunDetail->rider_run->total_run_complete_parcel,
                        ]);

                        // $this->adminDashboardCounterEvent();

                        $response = ['success' => 'Parcel Pickup Request Accept Successfully'];
                    } else {
                        $response = ['error' => 'Database Error Found'];
                    }

                } else {
                    $response = ['error' => 'Parcel Pickup Request Error Found'];
                }

            }

        }

        return response()->json($response);
    }

    public function processParcel(Request $request, Parcel $parcel) {
        $parcel->load('district', 'upazila', 'area', 'merchant', 'weight_package');
        return view('rider.parcel.processParcel', compact('parcel'));
    }

}
