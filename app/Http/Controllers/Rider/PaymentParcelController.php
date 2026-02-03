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

class PaymentParcelController extends Controller {

    public function collectionParcelList() {
        $data               = [];
        $data['main_menu']  = 'payment';
        $data['child_menu'] = 'collectionParcelList';
        $data['page_title'] = 'Collection Parcel List';
        $data['collapse']   = 'sidebar-collapse';
        return view('rider.payment.collectionParcelList', $data);
    }

    public function getCollectionParcelList(Request $request) {
        $rider_id = auth()->guard('rider')->user()->id;
        $model    = Parcel::with(['merchant' => function ($query) {
            $query->select('id', 'name');
        },
        ])
            ->whereRaw('(delivery_rider_id = ? and delivery_type in (1,2) and status in (21,22,25) )', [$rider_id])
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

    public function paidAmountParcelList() {
        $data               = [];
        $data['main_menu']  = 'payment';
        $data['child_menu'] = 'paidAmountParcelList';
        $data['page_title'] = 'Paid Amount Parcel List';
        $data['collapse']   = 'sidebar-collapse';
        return view('rider.payment.paidAmountParcelList', $data);
    }

    public function getPaidAmountParcelList(Request $request) {
        $rider_id = auth()->guard('rider')->user()->id;
        $model    = Parcel::with(['merchant' => function ($query) {
            $query->select('id', 'name');
        },
        ])
            ->whereRaw('(delivery_rider_id = ? and delivery_type in (1,2) and status >= ? )', [$rider_id, 25])
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



}
