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

class PickupParcelController extends Controller {


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
//        ->whereRaw('(pickup_rider_id = ? and status in (6, 8) )', [$rider_id])
        ->whereRaw('(pickup_rider_id = ?)', [$rider_id])
        ->select(
            'id','parcel_invoice', 'customer_name', 'customer_contact_number', 'total_collect_amount','cod_charge','delivery_charge','weight_package_charge','total_charge', 'district_id', 'merchant_id', 'status'
        )->orderBy('id',"desc");

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
                $button = '<button class="btn btn-secondary view-modal btn-sm" data-toggle="modal" data-target="#viewModal" parcel_id="' . $data->id . '" title=" View Pickup Parcel ">
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
                \DB::beginTransaction();
                try {
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

                        \DB::commit();

                        $parcel = Parcel::where('id', $request->parcel_id)->first();
                        // $this->merchantDashboardCounterEvent($parcel->merchant_id);

                        // $this->branchDashboardCounterEvent($parcel->pickup_branch_id);

                        // $this->adminDashboardCounterEvent();
                        $response = ['success' => 'Parcel Pickup Request Accept Successfully'];
                    } else {
                        $response = ['error' => 'Database Error Found'];
                    }
                }
                catch (\Exception $e){
                    \DB::rollback();
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
                \DB::beginTransaction();
                try {
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

                        \DB::commit();

                        $parcel = Parcel::where('id', $request->parcel_id)->first();
                        // $this->merchantDashboardCounterEvent($parcel->merchant_id);

                        // $this->branchDashboardCounterEvent($parcel->pickup_branch_id);

                        // $this->adminDashboardCounterEvent();
                        $response = ['success' => 'Parcel Pickup Request Reject Successfully'];
                    } else{
                        $response = ['error' => 'Database Error Found'];
                    }
                }
                catch (\Exception $e){
                    \DB::rollback();
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
                \DB::beginTransaction();
                try {
                    $riderRunDetail = RiderRunDetail::with('rider_run')
                    ->whereRaw('status in (2,4) AND parcel_id = ? ', $request->parcel_id)
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

                        \DB::commit();

                        $parcel = Parcel::where('id', $request->parcel_id)->first();
                        // $this->merchantDashboardCounterEvent($parcel->merchant_id);

                        // $this->branchDashboardCounterEvent($parcel->pickup_branch_id);

                        // $this->adminDashboardCounterEvent();
                        $response = ['success' => 'Parcel Pickup Complete Successfully'];
                    } else {
                        $response = ['error' => 'Database Error Found'];
                    }
                }
                catch (\Exception $e){
                    \DB::rollback();
                    $this->setMessage('Database Error', 'danger');
                    return redirect()->back()->withInput();
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
                \DB::beginTransaction();
                try {
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
                        \DB::commit();

                        $parcel = Parcel::where('id', $request->parcel_id)->first();
                        // $this->merchantDashboardCounterEvent($parcel->merchant_id);

                        // $this->branchDashboardCounterEvent($parcel->pickup_branch_id);

                        // $this->adminDashboardCounterEvent();
                        $response = ['success' => 'Parcel Pickup Complete Successfully'];
                    } else {
                        $response = ['error' => 'Database Error Found'];
                    }
                }
                catch (\Exception $e){
                    \DB::rollback();
                    $this->setMessage('Database Error', 'danger');
                    return redirect()->back()->withInput();
                }
            }
        }
        return response()->json($response);
    }


}
