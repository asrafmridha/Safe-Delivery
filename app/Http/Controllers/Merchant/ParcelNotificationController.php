<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ParcelNotificationController extends Controller
{

    public function index()
    {
        $data               = [];
        $data['main_menu']  = 'parcel';
        $data['child_menu'] = 'parcelNotification';
        $data['page_title'] = 'Parcel Notification';
        $data['collapse']   = 'sidebar-collapse';
        return view('merchant.parcelNotificationList', $data);
    }

    public function getParcelNotificationList(Request $request) {
        $merchant_user = auth()->guard('merchant')->user();

        $model = $merchant_user->notifications;

//        dd($model);

        return DataTables::of($model)
            ->addIndexColumn()
            ->editColumn('created_at', function ($data) {
                $date_time = date("Y-m-d H:i A", strtotime($data->created_at));

                return $date_time;
            })
            ->editColumn("notification_data", function($data){
                $notify_data    = json_decode(json_encode($data->data));
                $notify = "Parcel <b>{$notify_data->parcel_info->parcel_invoice}</b> is {$notify_data->parcel_info->status_name}";

                return $notify;
            })
//            ->editColumn('status', function ($data) {
//
//                $parcelStatus   = returnParcelStatusNameForMerchant($data->status, $data->delivery_type, $data->payment_type);
//                $status_name    = $parcelStatus['status_name'];
//                $class          = $parcelStatus['class'];
//
//                return '<span class=" text-bold text-' . $class . '" style="font-size:16px;"> ' . $status_name . '</span>';
//            })
            ->addColumn('action', function ($data) {
//                $button = '<button class="btn btn-secondary view-modal btn-sm" data-toggle="modal" data-target="#viewModal" parcel_id="' . $data->id . '"  title="Parcel View">
//                <i class="fa fa-eye"></i> </button>';
//
//                if ($data->status != 3) {
//                    if ($data->status == 1 || $data->status == 4) {
//                        $button .= '&nbsp; <button class="btn btn-warning pickup-hold btn-sm" parcel_id="' . $data->id . '" title="Hold Parcel Processing">
//                        <i class="far fa-pause-circle"></i></button>';
//                    } elseif ($data->status == 2 ) {
//                        $button .= '&nbsp; <button class="btn btn-success pickup-start btn-sm" parcel_id="' . $data->id . '" title="Start Parcel Processing" >
//                            <i class="far fa-play-circle"></i>
//                         </button>';
//                    }
//
//                    if ($data->status < 10) {
//                        $button .= '&nbsp; <button class="btn btn-danger pickup-cancel btn-sm" parcel_id="' . $data->id . '" title="Parcel Cancel">
//                                        <i class="far fa-window-close"></i>
//                                    </button>';
//
//                        $button .= '<a class="btn btn-secondary btn-sm" href="' . route('merchant.parcel.edit', $data->id) . '"   title="Parcel Edit">
//                                        <i class="fas fa-pencil-alt"></i>
//                                </a>';
//                    }
//
//
//                }
                return "";
            })
            ->rawColumns(['notification_data','action'])
            ->make(true);
    }


    public function parcelNotificationRead()
    {

        $merchant_user = auth()->guard('merchant')->user();

        $merchant_user->unreadNotifications->markAsRead();

        return true;
    }
}
