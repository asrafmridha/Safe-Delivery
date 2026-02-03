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

class ReturnParcelController extends Controller {

    public function returnParcelList() {
        $data               = [];
        $data['main_menu']  = 'parcel';
        $data['child_menu'] = 'returnParcelList';
        $data['page_title'] = 'Return Parcel List';
        $data['collapse']   = 'sidebar-collapse';
        return view('rider.parcel.returnParcel.returnParcelList', $data);
    }

    public function getReturnParcelList(Request $request) {
        $rider_id = auth()->guard('rider')->user()->id;
        $model    = Parcel::with(['merchant' => function ($query) {
            $query->select('id', 'name', 'company_name', 'contact_number', 'address');
        },
        ])
        ->whereRaw('(return_rider_id = ? and status in (31, 33) )', [$rider_id])
        ->select(
            'id','parcel_invoice', 'customer_name', 'customer_contact_number', 'total_charge', 'district_id', 'merchant_id', 'status'
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
                $button = '<button class="btn btn-secondary view-modal btn-sm" data-toggle="modal" data-target="#viewModal" parcel_id="' . $data->id . '}" title=" View Return Parcel ">
                <i class="fa fa-eye"></i> </button>';

                switch ($data->status) {
                    case 31:
                        $button .= '&nbsp; <button class="btn btn-success btn-sm return-request-accept-btn" parcel_id="' . $data->id . '" title="Return Request Accept">
                                <i class="fa fa-check"></i> </button>';

                        $button .= '&nbsp; <button class="btn btn-danger btn-sm return-request-reject-btn" parcel_id="' . $data->id . '" title="Return Run Reject">
                                <i class="far fa-window-close"></i> </button>';
                        break;

                    case 33:
                        $button .= '&nbsp;&nbsp;&nbsp;
                            <button class="btn btn-success return-complete-btn btn-sm" data-toggle="modal" data-target="#viewModal" parcel_id="' . $data->id . '}" title="Return Run Complete">
                            <i class="fa fa-check"></i> </button>';
                        break;

                    default:
                        $button .= "";
                        break;
                }
                return $button;
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    public function parcelReturnRequestAccept(Request $request) {
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
                                ['run_type', '=', 3],
                                ['status', '=', 2],
                            ]);
                        })
                        ->first();


                    if ($riderRunDetail) {
                        RiderRunDetail::where('id', $riderRunDetail->id)->update([
                            'status' => 4,
                        ]);
                        Parcel::where('id', $request->parcel_id)->update([
                            'status'            => 33,
                            'parcel_date'       => date('Y-m-d'),
                            'pickup_rider_date' => date('Y-m-d'),
                        ]);
                        $parcel=Parcel::where('id', $request->parcel_id)->first();
                        ParcelLog::create([
                            'parcel_id'       => $request->parcel_id,
                            'pickup_rider_id' => auth()->guard('rider')->user()->id,
                            'date'            => date('Y-m-d'),
                            'time'            => date('H:i:s'),
                            'status'          => 33,
                            'delivery_type' => $parcel->delivery_type,
                        ]);

                        \DB::commit();

                        $parcel = Parcel::where('id', $request->parcel_id)->first();
                        // $this->merchantDashboardCounterEvent($parcel->merchant_id);

                        // $this->branchDashboardCounterEvent($parcel->return_branch_id);

                        // $this->adminDashboardCounterEvent();
                        $response = ['success' => 'Parcel Return Request Accept Successfully'];
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

    public function parcelReturnRequestReject(Request $request) {
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
                            ['run_type', '=', 3],
                            ['status', '=', 2],
                        ]);
                    })
                    ->first();

                    if ($riderRunDetail) {
                        RiderRunDetail::where('id', $riderRunDetail->id)->update([
                            'status' => 5,
                        ]);

                        Parcel::where('id', $request->parcel_id)->update([
                            'status'            => 34,
                            'parcel_date'       => date('Y-m-d'),
                            'return_rider_date' => date('Y-m-d'),
                        ]);
                        $parcel=Parcel::where('id', $request->parcel_id)->first();
                        ParcelLog::create([
                            'parcel_id'       => $request->parcel_id,
                            'return_rider_id' => auth()->guard('rider')->user()->id,
                            'date'            => date('Y-m-d'),
                            'time'            => date('H:i:s'),
                            'status'          => 34,
                            'delivery_type' => $parcel->delivery_type,
                        ]);

                        \DB::commit();

                        $parcel = Parcel::where('id', $request->parcel_id)->first();
                        // $this->merchantDashboardCounterEvent($parcel->merchant_id);

                        // $this->branchDashboardCounterEvent($parcel->return_branch_id);

                        // $this->adminDashboardCounterEvent();
                        $response = ['success' => 'Parcel Return Request Reject Successfully'];
                    } else{
                        $response = ['error' => 'Database Error Found'];
                    }

                } catch (\Exception$e) {
                    \DB::rollback();
                    $response = ['error' => 'Database Error Found'];
                }
            }
        }
        return response()->json($response);
    }


    public function parcelReturnComplete(Request $request, Parcel $parcel) {
        $parcel->load('district', 'upazila', 'area', 'merchant', 'weight_package', 'pickup_branch', 'pickup_rider', 'delivery_branch', 'delivery_rider');
        return view('rider.parcel.returnParcel.parcelReturnComplete', compact('parcel'));
    }

    public function confirmParcelReturnComplete(Request $request, Parcel $parcel) {
        $response = ['error' => 'Error Found'];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'note'      => 'sometimes',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()]);
            } else {

                \DB::beginTransaction();
                try {

                    $riderRunDetail = RiderRunDetail::with('rider_run')
                        ->where([
                            'parcel_id' => $parcel->id,
                            'status'    => 4,
                        ])
                        ->whereHas('rider_run', function ($query) {
                            $query->where([
                                ['run_type', '=', 3],
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

                        Parcel::where('id', $parcel->id)->update([
                            'status'            => 35,
                            'parcel_date'       => date('Y-m-d'),
                            'return_rider_date' => date('Y-m-d'),
                        ]);
                        ParcelLog::create([
                            'parcel_id'       => $parcel->id,
                            'return_rider_id' => auth()->guard('rider')->user()->id,
                            'date'            => date('Y-m-d'),
                            'time'            => date('H:i:s'),
                            'status'          => 35,
                            'delivery_type' => $parcel->delivery_type,
                            'note' =>  $request->note,
                        ]);
                        \DB::commit();

                        //$parcel = Parcel::where('id', $request->parcel_id)->first();
                        // $this->merchantDashboardCounterEvent($parcel->merchant_id);


                        // $this->branchDashboardCounterEvent($parcel->return_branch_id);

                        // $this->adminDashboardCounterEvent();
                        $response = ['success' => 'Parcel Return Complete Successfully'];
                    } else {
                        $response = ['error' => 'Database Error Found'];
                    }
                } catch (\Exception$e) {
                    \DB::rollback();
                    $response = ['error' => 'Database Error Found'];
                }
            }
        }
        return response()->json($response);
    }


}
